<?php

/**
 * Class Tribe__Meta__Chunker
 *
 * Chunks large meta to avoid kiling the database in queries.
 *
 * Databases can have a `max_allowed_packet` value set as low as 1M; we often need to store large blobs of data way
 * over that and doing so would kill hte database ("MySQL server has gone away"...); registering a meta to be chunked
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
	protected $filter_p = - 1;

	/**
	 * @var string The meta key prefix applied ot any Chunker related post meta.
	 */
	protected $meta_key_prefix = "_tribe_chunker_";

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

		foreach ( $this->post_types as $post_type ) {
			add_filter( "update_{$post_type}_metadata", array( $this, 'filter_update_metadata' ), $this->filter_p, 4 );
			add_filter( "delete_{$post_type}_metadata", array( $this, 'filter_delete_metadata' ), $this->filter_p, 3 );
			add_filter( "add_{$post_type}_metadata", array( $this, 'filter_add_metadata' ), $this->filter_p, 4 );
			add_filter( "get_{$post_type}_metadata", array( $this, 'filter_get_metadata' ), $this->filter_p, 4 );
		}
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
		$post = get_post( $post_id );

		if ( empty( $post ) || ! in_array( $post->post_type, $this->post_types ) ) {
			return false;
		}

		$this->tag_as_chunkable( $post_id, $meta_key );

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
		if ( $this->is_chunker_logic_meta( $meta_key ) ) {
			return $check;
		}

		remove_filter( current_filter(), array( $this, 'filter_update_metadata' ), $this->filter_p );

		if ( $this->is_chunkable( $object_id, $meta_key )
		) {
			$this->delete_chunks( $object_id, $meta_key );

			if ( $this->should_be_chunked( $object_id, $meta_key, $meta_value ) ) {
				$this->insert_chunks( $object_id, $meta_key );

				return true;
			}

			$this->cache_delete( $object_id, $meta_key );
		}

		add_filter( current_filter(), array( $this, 'filter_update_metadata' ), $this->filter_p, 4 );

		return $check;
	}

	/**
	 * Whether the meta key is one used by the chunker to keep track of its operations or not.
	 *
	 * @param string $meta_key
	 *
	 * @return bool
	 */
	protected function is_chunker_logic_meta( $meta_key ) {
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
		$criteria = array(
			'post_id'  => $object_id,
			'meta_key' => $this->get_chunk_meta_key( $meta_key )
		);
		$wpdb->delete( $wpdb->postmeta, $criteria );
	}

	/**
	 * Returns the meta key used to indicate a chunk for a meta key.
	 *
	 * @param string $meta_key
	 *
	 * @return string
	 */
	protected function get_chunk_meta_key( $meta_key ) {
		return $this->get_chunkable_meta_key( $meta_key ) . '_chunk';
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
		$prepared_chunks = array();
		foreach ( $chunks as $chunk ) {
			$prepared_chunks[] = $wpdb->prepare( "(%d, %s, %s)", $object_id, $chunk_meta_key, $chunk );
		}
		$query = "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUES ";
		$query .= implode( ",\n", $prepared_chunks );
		$wpdb->query( $query );
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
		if ( $this->is_chunker_logic_meta( $meta_key ) ) {
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
	 *
	 * @return mixed
	 */
	public function is_chunked( $object_id, $meta_key ) {
		$key = $this->get_key( $object_id, $meta_key );

		return array_key_exists( $key, $this->chunks_cache ) && is_array( $this->chunks_cache[ $key ] );
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
	public function filter_get_metadata( $check, $object_id, $meta_key, $single = true ) {
		if ( $this->is_chunker_logic_meta( $meta_key ) ) {
			return $check;
		}

		$key = $this->get_key( $object_id, $meta_key );
		if ( array_key_exists( $key, $this->chunks_cache ) && is_array( $this->chunks_cache[ $key ] ) ) {
			return $this->glue_chunks( $this->chunks_cache[ $key ] );
		}

		if ( $this->is_chunked( $object_id, $meta_key ) ) {
			$chunks = $this->get_chunks_for( $object_id, $meta_key );
			$check = maybe_unserialize( $this->glue_chunks( $chunks ) );

			return $single ? $check : array( $check );
		}

		return $check;
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
			preg_match( "/(\\d+)" . preg_quote( $this->chunk_separator ) . "(.*)/", $chunk, $matches );
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

		$this->chunks_cache[ $this->get_key( $object_id, $meta_key ) ] = $meta_values;

		return $meta_values;
	}

	/**
	 * Unhooks the Chunker from the metadata operations.
	 */
	public function unhook() {
		foreach ( $this->post_types as $post_type ) {
			remove_filter( "update_{$post_type}_metadata", array( $this, 'filter_update_metadata' ), $this->filter_p );
			remove_filter( "delete_{$post_type}_metadata", array( $this, 'filter_delete_metadata' ), $this->filter_p );
			remove_filter( "add_{$post_type}_metadata", array( $this, 'filter_add_metadata' ), $this->filter_p );
			remove_filter( "get_{$post_type}_metadata", array( $this, 'filter_get_metadata' ), $this->filter_p );
		}
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
	public function set_post_types( array $post_types = array() ) {
		/**
		 * Filters the chunk-able post types.
		 *
		 * @param array $post_types
		 */
		$this->post_types = apply_filters( 'tribe_meta_chunker_post_types', $post_types );
	}
}