<?php

/**
 * Class Tribe__Meta__Chunker
 *
 * Chunks large meta to avoid killing the database in queries.
 *
 * Databases can have a `max_allowed_packet` value set as low as 1M; we often need to store large blobs of data way
 * over that and doing so would kill the database ("MySQL server has gone away"...); registering a meta to be chunked
 * if needed avoids that.
 *
 * Example usage:
 *
 *      $chunker = tribe( 'chunker' );
 *      $chunker->register_for_chunking( $post_id, 'my_meta_key' );
 *
 *      // ... some code later...
 *
 *      // data will be transparently chunked if needed...
 *      update_meta( $post_id, 'my_meta_key', $some_looooooooooong_string );
 *
 *      // ...and glued back together when reading
 *      get_post_meta( $post_id, 'my_meta_key', true );
 *
 * By default the Chunker supports the `post` post type only, filter the `tribe_meta_chunker_post_types` to add yours:
 *
 *      add_filter( 'tribe_meta_chunker_post_types`, 'my_chunkable_post_types' );
 *      function my_chunkable_post_types( $post_types ) {
 *          $post_types[] = 'book';
 *
 *          return $post_types;
 *      }
 *
 * or filter the `tribe_meta_chunker_post_types` filter.
 */
class Tribe__Meta__Chunker {
	/**
	 * @var array The cache that will store chunks to avoid middleware operations from fetching the database.
	 */
	protected $chunks_cache = array();

	/**
	 * @var string The separator that's used to mark the start of each chunk.
	 */
	protected $chunk_separator = '{{{TCSEP}}}';

	/**
	 * @var array The post types supported by the Chunker.
	 */
	protected $post_types = array( 'post' );

	/**
	 * @var int The filter priority at which Chunker will operate on meta CRUD operations.
	 */
	protected $filter_priority = - 1;

	/**
	 * @var string The meta key prefix applied ot any Chunker related post meta.
	 */
	protected $meta_key_prefix = '_tribe_chunker_';

	/**
	 * @var int The largest size allowed by the Chunker.
	 */
	protected $max_chunk_size;

	/**
	 * Hooks the chunker on metadata operations for each supported post types.
	 *
	 * When changing post types unhook and rehook it like:
	 *
	 *      $chunker = tribe( 'chunker' );
	 *      $chunker->set_post_types( array_merge( $my_post_types, $chunker->get_post_types() );
	 *      $chunker->unhook();
	 *      $chunker->hook();
	 */
	public function hook() {
		if ( empty( $this->post_types ) ) {
			return;
		}

		$this->prime_chunked_cache();

		add_filter( 'update_post_metadata', array( $this, 'filter_update_metadata' ), $this->filter_priority, 4 );
		add_filter( 'delete_post_metadata', array( $this, 'filter_delete_metadata' ), $this->filter_priority, 3 );
		add_filter( 'add_post_metadata', array( $this, 'filter_add_metadata' ), $this->filter_priority, 4 );
		add_filter( 'get_post_metadata', array( $this, 'filter_get_metadata' ), $this->filter_priority, 3 );
	}

	/**
	 * Primes the chunked cache.
	 *
	 * This will just fetch the keys for the supported post types, not the values.
	 */
	protected function prime_chunked_cache() {
		/** @var wpdb $wpdb */
		global $wpdb;
		$query = $wpdb->prepare( "SELECT post_id, meta_key FROM {$wpdb->postmeta}
			WHERE meta_key LIKE %s
			AND meta_key NOT LIKE %s",
			$this->meta_key_prefix . '%', $this->meta_key_prefix . '%_chunk'
		);
		$results = $wpdb->get_results( $query );

		$this->chunks_cache = array();
		foreach ( $results as $result ) {
			$real_meta_key = str_replace( $this->meta_key_prefix, '', $result->meta_key );
			$this->chunks_cache[ $this->get_key( $result->post_id, $real_meta_key ) ] = null;
		}
	}

	/**
	 * Gets the key used to identify a post ID and meta key in the chunks cache.
	 *
	 * @param int    $post_id
	 * @param string $meta_key
	 * @return string
	 */
	protected function get_key( $post_id, $meta_key ) {
		return "{$post_id}::{$meta_key}";
	}

	/**
	 * Register a post ID and meta key to be chunked if needed.
	 *
	 * @param int    $post_id
	 * @param string $meta_key
	 *
	 * @return bool `false` if the post type is not supported, `true` otherwise
	 */
	public function register_chunking_for( $post_id, $meta_key ) {
		if ( ! $this->is_supported_post_type( $post_id ) ) {
			return false;
		}

		$this->tag_as_chunkable( $post_id, $meta_key );

		return true;
	}

	/**
	 * Whether a post type is supported or not.
	 *
	 * @param int $object_id
	 *
	 * @return bool
	 */
	protected function is_supported_post_type( $object_id ) {
		$post = get_post( $object_id );
		if ( empty( $post ) || ! in_array( $post->post_type, $this->post_types ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Tags a post ID and meta key couple as "chunkable" if needed.
	 *
	 * @param $post_id
	 * @param $meta_key
	 */
	protected function tag_as_chunkable( $post_id, $meta_key ) {
		$key = $this->get_key( $post_id, $meta_key );
		if ( ! array_key_exists( $key, $this->chunks_cache ) ) {
			$this->chunks_cache[ $key ] = null;
		}
		update_post_meta( $post_id, $this->get_chunkable_meta_key( $meta_key ), true );
	}

	/**
	 * Returns the meta key used to indicate if a meta key for a post is marked as chunkable.
	 *
	 * @param string $meta_key
	 *
	 * @return string
	 */
	public function get_chunkable_meta_key( $meta_key ) {
		return $this->meta_key_prefix . $meta_key;
	}

	/**
	 * Filters the add operations.
	 *
	 * Due to how the system works no more than one chunked entry can be stored.
	 *
	 * @param mixed  $check
	 * @param int    $object_id
	 * @param string $meta_key
	 * @param string $meta_value
	 *
	 * @see add_metadata()
	 *
	 * @return bool
	 */
	public function filter_add_metadata( $check, $object_id, $meta_key, $meta_value ) {
		return $this->filter_update_metadata( $check, $object_id, $meta_key, $meta_value );
	}

	/**
	 * Filters the updated operations.
	 *
	 * @param mixed  $check
	 * @param int    $object_id
	 * @param string $meta_key
	 * @param string $meta_value
	 *
	 * @see update_metadata()
	 *
	 * @return bool
	 */
	public function filter_update_metadata( $check, $object_id, $meta_key, $meta_value ) {
		if ( ! $this->applies( $object_id, $meta_key ) ) {
			return $check;
		}

		$this->delete_chunks( $object_id, $meta_key );
		$this->remove_checksum_for( $object_id, $meta_key );

		if ( $this->should_be_chunked( $object_id, $meta_key, $meta_value ) ) {
			$this->insert_chunks( $object_id, $meta_key );

			return true;
		} else {
			$this->cache_delete( $object_id, $meta_key );
			$this->insert_meta( $object_id, $meta_key, $meta_value );

			return true;
		}

		return $check;
	}

	/**
	 * Whether the chunker should operate on this post ID and meta key couple or not.
	 *
	 * @param int    $object_id
	 * @param string $meta_key
	 *
	 * @return bool
	 */
	protected function applies( $object_id, $meta_key ) {
		$applies = ! $this->is_chunker_logic_meta_key( $meta_key )
		           && $this->is_supported_post_type( $object_id )
		           && ( empty( $meta_key ) || $this->is_chunkable( $object_id, $meta_key ) );

		return $applies;
	}

	/**
	 * Whether the meta key is one used by the chunker to keep track of its operations or not.
	 *
	 * @param string $meta_key
	 *
	 * @return bool
	 */
	protected function is_chunker_logic_meta_key( $meta_key ) {
		return 0 === strpos( $meta_key, $this->meta_key_prefix );
	}

	/**
	 * Whether a post ID and meta key couple is registered as chunkable or not.
	 *
	 * @param int    $post_id
	 * @param string $meta_key
	 *
	 * @return bool
	 */
	public function is_chunkable( $post_id, $meta_key ) {
		$key = $this->get_key( $post_id, $meta_key );

		return array_key_exists( $key, $this->chunks_cache );
	}

	/**
	 * Deletes all the chunks for a post ID and meta key couple.
	 *
	 * @param int    $object_id
	 * @param string $meta_key
	 */
	protected function delete_chunks( $object_id, $meta_key ) {
		/** @var wpdb $wpdb */
		global $wpdb;
		$chunk_meta_key = $this->get_chunk_meta_key( $meta_key );
		$delete = "DELETE FROM {$wpdb->postmeta} WHERE (meta_key = %s OR meta_key = %s) AND post_id = %d";
		$wpdb->query( $wpdb->prepare( $delete, $chunk_meta_key, $meta_key, $object_id ) );
	}

	/**
	 * Returns the meta key used to indicate a chunk for a meta key.
	 *
	 * @param string $meta_key
	 *
	 * @return string
	 */
	public function get_chunk_meta_key( $meta_key ) {
		return $this->get_chunkable_meta_key( $meta_key ) . '_chunk';
	}

	/**
	 * Removes the checksum used to verify the integrity of the chunked values.
	 *
	 * @param int    $object_id
	 * @param string $meta_key
	 */
	protected function remove_checksum_for( $object_id, $meta_key ) {
		/** @var wpdb $wpdb */
		global $wpdb;
		$data = array(
			'post_id'  => $object_id,
			'meta_key' => $this->get_checksum_key( $meta_key ),
		);
		$wpdb->delete( $wpdb->postmeta, $data );
	}

	/**
	 * Returns the meta_key used to store the chunked meta checksum for a specified meta key.
	 *
	 * @param string $meta_key
	 *
	 * @return string
	 */
	public function get_checksum_key( $meta_key ) {
		return $this->meta_key_prefix . $meta_key . '_checksum';
	}

	/**
	 * Whether a value should be chunked or not.
	 *
	 * @param int    $post_id
	 * @param string $meta_key
	 * @param mixed  $meta_value
	 *
	 * @return bool
	 */
	public function should_be_chunked( $post_id, $meta_key, $meta_value ) {
		$should_be_chunked = false;

		$max_allowed_packet = $this->get_max_chunk_size();
		$serialized = maybe_serialize( $meta_value );
		$byte_size = $this->get_byte_size( $serialized );
		// we use .8 and not 1 to allow for MySQL instructions to use 20% of the string size
		if ( $byte_size > .8 * $max_allowed_packet ) {
			$chunk_size = ceil( $max_allowed_packet * 0.75 );
			$key = $this->get_key( $post_id, $meta_key );
			$this->chunks_cache[ $key ] = $this->prefix_chunks( $this->chunk( $serialized, $chunk_size ) );
			$should_be_chunked = true;
		}

		return $should_be_chunked;
	}

	/**
	 * Returns the max chunk size in bytes.
	 *
	 * @return array|int|null|object
	 */
	public function get_max_chunk_size() {
		if ( ! empty( $this->max_chunk_size ) ) {
			return $this->max_chunk_size;
		}
		/** @var wpdb $wpdb */
		global $wpdb;
		$max_size = $wpdb->get_results( "SHOW VARIABLES LIKE 'max_allowed_packet';", ARRAY_A );
		$max_size = ! empty( $max_size[0]['Value'] ) ? $max_size[0]['Value'] : 1048576;

		/**
		 * Filters the max size of the of the chunks in bytes.
		 *
		 * @param int $max_size By default the `max_allowed_packet` from the database.
		 */
		$this->max_chunk_size = apply_filters( 'tribe_meta_chunker_max_size', $max_size );

		return $max_size;
	}

	/**
	 * Sets the max chunk size.
	 *
	 * @param int $max_chunk_size The max chunk size in bytes.
	 */
	public function set_max_chunk_size( $max_chunk_size ) {
		$this->max_chunk_size = $max_chunk_size;
	}

	/**
	 * Gets the size in bytes of something.
	 *
	 * @param mixed $data
	 *
	 * @return int
	 */
	public function get_byte_size( $data ) {
		return strlen( utf8_decode( maybe_serialize( $data ) ) );
	}

	/**
	 * Prefixes each chunk with a sequence number.
	 *
	 * @param array $chunks
	 *
	 * @return array An array of chunks each prefixed with sequence number.
	 */
	protected function prefix_chunks( array $chunks ) {
		$count = count( $chunks );
		$prefixed = array();
		for ( $i = 0; $i < $count; $i ++ ) {
			$prefixed[] = "{$i}{$this->chunk_separator}{$chunks[$i]}";
		}

		return $prefixed;
	}

	/**
	 * Chunks a string.
	 *
	 * The chunks are not prefixed!
	 *
	 * @param string $serialized
	 * @param int    $chunk_size
	 *
	 * @return array An array of unprefixed chunks.
	 */
	protected function chunk( $serialized, $chunk_size ) {
		$sep = $this->chunk_separator;
		$chunks = array_filter( explode( $sep, chunk_split( $serialized, $chunk_size, $sep ) ) );

		return $chunks;
	}

	/**
	 * Inserts the chunks for a post ID and meta key couple in the database.
	 *
	 * The chunks are read from the array cache.
	 *
	 * @param int    $object_id
	 * @param string $meta_key
	 */
	protected function insert_chunks( $object_id, $meta_key ) {
		/** @var wpdb $wpdb */
		global $wpdb;

		$key = $this->get_key( $object_id, $meta_key );
		$chunks = $this->chunks_cache[ $key ];
		$chunk_meta_key = $this->get_chunk_meta_key( $meta_key );
		$this->insert_meta( $object_id, $meta_key, $chunks[0] );
		foreach ( $chunks as $chunk ) {
			$wpdb->insert( $wpdb->postmeta, array(
				'post_id'    => $object_id,
				'meta_key'   => $chunk_meta_key,
				'meta_value' => $chunk,
			) );
		}

		$glued = $this->glue_chunks( $this->get_chunks_for( $object_id, $meta_key ) );
		$checksum_key = $this->get_checksum_key( $meta_key );
		$wpdb->delete( $wpdb->postmeta, array( 'post_id' => $object_id, 'meta_key' => $checksum_key ) );
		$wpdb->insert( $wpdb->postmeta, array(
			'post_id'    => $object_id,
			'meta_key'   => $checksum_key,
			'meta_value' => md5( $glued ),
		) );
	}

	/**
	 * Inserts a meta value in the database.
	 *
	 * Convenience method to avoid infinite loop in hooks.
	 *
	 * @param int    $object_id
	 * @param string $meta_key
	 * @param mixed  $meta_value
	 */
	protected function insert_meta( $object_id, $meta_key, $meta_value ) {
		/** @var wpdb $wpdb */
		global $wpdb;
		$data = array(
			'post_id'    => $object_id,
			'meta_key'   => $meta_key,
			'meta_value' => maybe_serialize( $meta_value ),
		);
		$wpdb->insert( $wpdb->postmeta, $data );
	}

	/**
	 * Glues the provided chunks.
	 *
	 * This method is sequence aware and should be used with what the `get_chunks_for` method returns.
	 *
	 * @param array $chunks
	 *
	 * @return string
	 *
	 * @see Tribe__Meta__Chunker::get_chunks_for()
	 */
	public function glue_chunks( array $chunks ) {
		$ordered_chunks = array();
		foreach ( $chunks as $chunk ) {
			preg_match( '/(\\d+)' . preg_quote( $this->chunk_separator ) . '(.*)/', $chunk, $matches );
			$ordered_chunks[ $matches[1] ] = $matches[2];
		}
		ksort( $ordered_chunks );

		return implode( '', array_values( $ordered_chunks ) );
	}

	/**
	 * Returns the chunks stored in the database for a post ID and meta key couple.
	 *
	 * The chunks are returned as they are with prefix.
	 *
	 * @param int    $object_id
	 * @param string $meta_key
	 *
	 * @return array|mixed
	 */
	public function get_chunks_for( $object_id, $meta_key ) {
		$key = $this->get_key( $object_id, $meta_key );

		if ( ! empty( $this->chunks_cache[ $key ] ) ) {
			return $this->chunks_cache[ $key ];
		}

		/** @var wpdb $wpdb */
		global $wpdb;

		$chunk_meta_key = $this->get_chunk_meta_key( $meta_key );

		$meta_ids = $wpdb->get_col( $wpdb->prepare( "SELECT meta_id FROM {$wpdb->postmeta}
			WHERE post_id = %d
			AND meta_key = %s",
			$object_id, $chunk_meta_key
		) );

		$meta_values = array();
		foreach ( $meta_ids as $meta_id ) {
			$query = $wpdb->prepare( "SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_id = %d", $meta_id );
			$meta_values[] = $wpdb->get_var( $query );
		}

		if ( ! empty( $meta_values ) ) {
			$this->chunks_cache[ $this->get_key( $object_id, $meta_key ) ] = $meta_values;
		} else {
			$this->chunks_cache[ $this->get_key( $object_id, $meta_key ) ] = null;
		}

		return $meta_values;
	}

	/**
	 * Resets a post ID and meta key couple cache.
	 *
	 * @param int    $object_id
	 * @param string $meta_key
	 */
	protected function cache_delete( $object_id, $meta_key ) {
		$key = $this->get_key( $object_id, $meta_key );
		if ( isset( $this->chunks_cache[ $key ] ) ) {
			$this->chunks_cache[ $key ] = null;
		}
	}

	/**
	 * Filters the delete operations.
	 *
	 * @param mixed  $check
	 * @param int    $object_id
	 * @param string $meta_key
	 *
	 * @return bool
	 *
	 * @see delete_metadata()
	 */
	public function filter_delete_metadata( $check, $object_id, $meta_key ) {
		if ( ! $this->applies( $object_id, $meta_key ) ) {
			return $check;
		}

		$has_chunked_meta = $this->is_chunked( $object_id, $meta_key );
		if ( ! $has_chunked_meta ) {
			return $check;
		}
		$this->cache_delete( $object_id, $meta_key );
		$this->delete_chunks( $object_id, $meta_key );

		return true;
	}

	/**
	 * Whether a post ID and meta key couple has chunked meta or not.
	 *
	 * @param int    $object_id
	 * @param string $meta_key
	 * @param bool   $check_db Do verify the chunking state on the database.
	 *
	 * @return mixed
	 */
	public function is_chunked( $object_id, $meta_key, $check_db = false ) {
		$key = $this->get_key( $object_id, $meta_key );
		$chunked_in_cache = array_key_exists( $key, $this->chunks_cache ) && is_array( $this->chunks_cache[ $key ] );

		return false === $check_db ? $chunked_in_cache : $this->verify_chunks_for( $object_id, $meta_key );
	}

	/**
	 * Verifies that the chunks stored on the database for an object meta still form a coherent value.
	 *
	 * @param int    $object_id
	 * @param string $meta_key
	 *
	 * @return bool `true` if the meta is still valid, `false` otherwise.
	 */
	public function verify_chunks_for( $object_id, $meta_key ) {
		$chunks = $this->get_chunks_for( $object_id, $meta_key );
		$glued = $this->glue_chunks( $chunks );

		return md5( maybe_serialize( $glued ) ) === $this->get_checksum_for( $object_id, $meta_key );
	}

	/**
	 * Returns the checksum for the stored meta key to spot meta value corruption malforming.
	 *
	 * @param int    $object_id
	 * @param string $meta_key
	 *
	 * @return string
	 */
	public function get_checksum_for( $object_id, $meta_key ) {
		/** @var wpdb $wpdb */
		global $wpdb;

		$query = "SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = %s";
		$checksum = $wpdb->get_var( $wpdb->prepare( $query, $object_id, $this->get_checksum_key( $meta_key ) ) );

		return ! empty( $checksum ) ? $checksum : '';
	}

	/**
	 * Handles the object destruction cycle to leave no traces behind.
	 */
	public function __destruct() {
		$this->unhook();
	}

	/**
	 * Unhooks the Chunker from the metadata operations.
	 */
	public function unhook() {
		foreach ( $this->post_types as $post_type ) {
			remove_filter( "update_{$post_type}_metadata", array( $this, 'filter_update_metadata' ), $this->filter_priority );
			remove_filter( "delete_{$post_type}_metadata", array( $this, 'filter_delete_metadata' ), $this->filter_priority );
			remove_filter( "add_{$post_type}_metadata", array( $this, 'filter_add_metadata' ), $this->filter_priority );
			remove_filter( "get_{$post_type}_metadata", array( $this, 'filter_get_metadata' ), $this->filter_priority );
		}
	}

	/**
	 * Filters the get operations.
	 *
	 * @param mixed  $check
	 * @param int    $object_id
	 * @param string $meta_key
	 * @param bool   $single
	 *
	 * @return array|mixed
	 *
	 * @see get_metadata()
	 */
	public function filter_get_metadata( $check, $object_id, $meta_key ) {
		if ( ! $this->applies( $object_id, $meta_key ) ) {
			return $check;
		}

		// getting all the meta
		if ( empty( $meta_key ) ) {
			return $this->get_all_meta_for( $object_id );
		}

		$key = $this->get_key( $object_id, $meta_key );
		if ( $this->is_chunked( $object_id, $meta_key ) ) {
			$glued = maybe_unserialize( $this->glue_chunks( $this->chunks_cache[ $key ] ) );
		} elseif ( $this->is_chunked( $object_id, $meta_key, true ) ) {
			$chunks = $this->get_chunks_for( $object_id, $meta_key );
			$glued = maybe_unserialize( $this->glue_chunks( $chunks ) );
		}

		if ( ! empty( $glued ) ) {
			// why not take $single into account? See condition check on the filter to understand.
			return array( $glued );
		}

		return $check;
	}

	/**
	 * Returns all the meta for a post ID.
	 *
	 * The meta includes the chunked one but not the chunker logic meta keys.
	 * The return format is the same used by the `get_post_meta( $post_id )` function.
	 *
	 * @param int $object_id
	 *
	 * @return array An array containing all meta including the chunked one.
	 *
	 * @see get_post_meta() with empty `$meta_key` argument.
	 */
	public function get_all_meta_for( $object_id ) {
		$all_meta = $this->get_all_meta( $object_id );

		if ( empty( $all_meta ) ) {
			return array();
		}

		$grouped = array();
		foreach ( $all_meta as $entry ) {
			if ( ! isset( $grouped[ $entry['meta_key'] ] ) ) {
				$grouped[ $entry['meta_key'] ] = array( $entry['meta_value'] );
			} else {
				$grouped[ $entry['meta_key'] ][] = $entry['meta_value'];
			}
		}

		$chunker_meta_keys = array_filter( array_keys( $grouped ), array( $this, 'is_chunker_logic_meta_key' ) );

		if ( empty( $chunker_meta_keys ) ) {
			return $grouped;
		}

		$chunker_meta_canary_keys = array_filter( $chunker_meta_keys, array( $this, 'is_chunker_canary_key' ) );

		if ( empty( $chunker_meta_canary_keys ) ) {
			return array_diff_key( $grouped, array_combine( $chunker_meta_keys, $chunker_meta_keys ) );
		}

		$chunker_meta = array_intersect_key( $grouped, array_combine( $chunker_meta_keys, $chunker_meta_keys ) );
		$normal_meta = array_diff_key( $grouped, array_combine( $chunker_meta_keys, $chunker_meta_keys ) );
		foreach ( $chunker_meta_canary_keys as $canary_key ) {
			$normal_meta_key = str_replace( $this->meta_key_prefix, '', $canary_key );
			if ( ! isset( $normal_meta[ $normal_meta_key ] ) ) {
				continue;
			}
			$chunk_meta_key = $this->get_chunk_meta_key( $normal_meta_key );
			if ( empty( $chunker_meta[ $chunk_meta_key ] ) ) {
				continue;
			}
			$normal_meta[ $normal_meta_key ] = array( $this->glue_chunks( $chunker_meta[ $chunk_meta_key ] ) );
		}

		return $normal_meta;
	}

	/**
	 * Fetches all the meta for a post.
	 *
	 * @param int $object_id
	 *
	 * @return array|null|object
	 */
	protected function get_all_meta( $object_id ) {
		/** @var wpdb $wpdb */
		global $wpdb;
		$query = $wpdb->prepare( "SELECT meta_key, meta_value FROM {$wpdb->postmeta} WHERE post_id = %d", $object_id );
		$results = $wpdb->get_results( $query, ARRAY_A );

		return ! empty( $results ) && is_array( $results ) ? $results : array();
	}

	/**
	 * Returns the post types supported by the chunker.
	 *
	 * @return array
	 */
	public function get_post_types() {
		return $this->post_types;
	}

	/**
	 * Sets the post types the Chunker should support.
	 *
	 * @param array $post_types
	 */
	public function set_post_types( array $post_types = null ) {
		if ( null === $post_types ) {
			/**
			 * Filters the chunk-able post types.
			 *
			 * @param array $post_types
			 */
			$this->post_types = apply_filters( 'tribe_meta_chunker_post_types', $this->post_types );

			return;
		}

		$this->post_types = $post_types;
	}

	/**
	 * Asserts that a meta key is not a chunk meta key.
	 *
	 * @param string $meta_key
	 *
	 * @return bool
	 */
	protected function is_chunker_canary_key( $meta_key ) {
		return 0 === strpos( $meta_key, $this->meta_key_prefix ) && ! preg_match( '/_chunk$/', $meta_key );
	}
}