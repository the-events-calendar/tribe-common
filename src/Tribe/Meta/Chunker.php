<?php


class Tribe__Meta__Chunker {
	protected $chunks_cache = array();
	protected $chunk_separator = '{{{TCSEP}}}';
	protected $post_types = array( 'post' );
	protected $filter_p = - 1;
	protected $meta_key_prefix = "_tribe_chunker_";
	protected $max_chunk_size;

	public function set_post_types( array $post_types = array() ) {
		$this->post_types = apply_filters( 'tribe_meta_chunker_post_types', $post_types );
	}

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
	 * @param $post_id
	 * @param $meta_key
	 * @return string
	 */
	protected function get_key( $post_id, $meta_key ) {
		return "{$post_id}::{$meta_key}";
	}

	public function register_chunking_for( $post_id, $meta_key, $meta_value ) {
		$post = get_post( $post_id );

		if ( empty( $post ) || ! in_array( $post->post_type, $this->post_types ) ) {
			return false;
		}

		$should_be_chunked = $this->should_be_chunked( $post_id, $meta_key, $meta_value );
		if ( $should_be_chunked ) {
			$this->tag_as_chunkable( $post_id, $meta_key );
		}

		return $should_be_chunked;
	}

	//$check = apply_filters( "update_{$meta_type}_metadata", null, $object_id, $meta_key, $meta_value, $prev_value );

	/**
	 * @param $post_id
	 * @param $meta_key
	 * @param $meta_value
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

	protected function get_max_chunk_size() {
		if ( ! empty( $this->max_chunk_size ) ) {
			return $this->max_chunk_size;
		}
		/** @var wpdb $wpdb */
		global $wpdb;
		$max_allowed_packet = $wpdb->get_results( "SHOW VARIABLES LIKE 'max_allowed_packet';" );
		if ( empty( $max_allowed_packet ) ) {
			// let's assume 1M
			$max_allowed_packet = 1048576;
		}
		$this->max_chunk_size = $max_allowed_packet;

		return $max_allowed_packet;
	}

	public function set_max_chunk_size( $max_chunk_size ) {
		$this->max_chunk_size = $max_chunk_size;
	}

//$check = apply_filters( "add_{$meta_type}_metadata", null, $object_id, $meta_key, $meta_value, $unique );

	/**
	 * @param $data
	 * @return int
	 */
	public function get_byte_size( $data ) {
		return strlen( utf8_decode( maybe_serialize( $data ) ) );
	}

	protected function prefix_chunks( array $chunks ) {
		$count = count( $chunks );
		$prefixed = array();
		for ( $i = 0; $i < $count; $i ++ ) {
			$prefixed[] = "{$i}{$this->chunk_separator}{$chunks[$i]}";
		}

		return $prefixed;
	}

	protected function chunk( $serialized, $chunk_size ) {
		$sep = $this->chunk_separator;
		$chunks = array_filter( explode( $sep, chunk_split( $serialized, $chunk_size, $sep ) ) );

		return $chunks;
	}

	/**
	 * @param $post_id
	 * @param $meta_key
	 */
	public function tag_as_chunkable( $post_id, $meta_key ) {
		update_post_meta( $post_id, $this->get_chunkable_meta_key( $meta_key ), true );
	}

	/**
	 * @param $meta_key
	 * @return string
	 */
	public function get_chunkable_meta_key( $meta_key ) {
		return $this->meta_key_prefix . $meta_key;
	}

	public function filter_add_metadata( $check, $object_id, $meta_key, $meta_value ) {
		return $this->filter_update_metadata( $check, $object_id, $meta_key, $meta_value );
	}

	public function filter_update_metadata( $check, $object_id, $meta_key, $meta_value ) {
		if ( $this->is_chunker_logic_meta( $meta_key ) ) {
			return $check;
		}

		remove_filter( current_filter(), array( $this, 'filter_update_metadata' ), $this->filter_p );

		if ( $this->is_chunkable( $object_id, $meta_key ) && $this->should_be_chunked( $object_id, $meta_key, $meta_value )
		) {
			$this->tag_as_chunkable( $object_id, $meta_key );
			$this->delete_chunks( $object_id, $meta_key );
			$this->insert_chunks( $object_id, $meta_key );

			return true;
		}

		add_filter( current_filter(), array( $this, 'filter_update_metadata' ), $this->filter_p, 4 );

		return $check;
	}

	/**
	 * @param $meta_key
	 * @return bool
	 */
	public function is_chunker_logic_meta( $meta_key ) {
		return 0 === strpos( $meta_key, $this->meta_key_prefix );
	}

	public function is_chunkable( $post_id, $meta_key ) {
		$key = $this->get_key( $post_id, $meta_key );

		return array_key_exists( $key, $this->chunks_cache );
	}

	/**
	 * @param $object_id
	 * @param $meta_key
	 */
	public function delete_chunks( $object_id, $meta_key ) {
		/** @var wpdb $wpdb */
		global $wpdb;
		$criteria = array(
			'post_id'  => $object_id,
			'meta_key' => $this->get_chunk_meta_key( $meta_key )
		);
		$wpdb->delete( $wpdb->postmeta, $criteria );
	}

	protected function get_chunk_meta_key( $meta_key ) {
		return $this->get_chunkable_meta_key( $meta_key ) . '_chunk';
	}

	/**
	 * @param $object_id
	 * @param $meta_key
	 */
	public function insert_chunks( $object_id, $meta_key ) {
		/** @var wpdb $wpdb */
		global $wpdb;

		$chunks = $this->chunks_cache[ $this->get_key( $object_id, $meta_key ) ];
		$chunks_count = count( $chunks );
		$chunk_meta_key = $this->chunk_meta_key( $meta_key );
		$prepared_chunks = array();
		$prefixed_chunks = $this->prefix_chunks( $chunks );
		foreach ( $prefixed_chunks as $chunk ) {
			$prepared_chunks[] = $wpdb->prepare( "(%d, %s, %s)", $object_id, $chunk_meta_key, $chunk );
		}
		for ( $i = 0; $i < $chunks_count; $i ++ ) {
			$this_chunk = $prepared_chunks[ $i ];
		}
		$query = "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUES ";
		$query .= implode( ",\n", $prepared_chunks );
		$wpdb->query( $query );
	}

	/**
	 * @param $meta_key
	 * @return string
	 */
	public function chunk_meta_key( $meta_key ) {
		return $this->get_chunk_meta_key( $meta_key );
	}

	public function filter_delete_metadata( $check, $object_id, $meta_key ) {
		if ( $this->is_chunker_logic_meta( $meta_key ) ) {
			return $check;
		}

		$has_chunked_meta = $this->has_chunked_meta( $object_id, $meta_key );
		if ( ! $has_chunked_meta ) {
			return $check;
		}
		$this->cache_delete( $object_id, $meta_key );
		$this->delete_chunks( $object_id, $meta_key );

		return true;
	}

	/**
	 * @param $object_id
	 * @param $meta_key
	 * @return mixed
	 */
	public function has_chunked_meta( $object_id, $meta_key ) {
		return array_key_exists( $this->get_key( $object_id, $meta_key ), $this->chunks_cache );
	}

	protected function cache_delete( $object_id, $meta_key ) {
		$key = $this->get_key( $object_id, $meta_key );
		if ( isset( $this->chunks_cache[ $key ] ) ) {
			unset( $this->chunks_cache[ $key ] );
		}
	}

	public function filter_get_metadata( $check, $object_id, $meta_key, $single = true ) {
		if ( $this->is_chunker_logic_meta( $meta_key ) ) {
			return $check;
		}

		if ( $this->has_chunked_meta( $object_id, $meta_key ) ) {
			$chunks = $this->get_chunks_for( $object_id, $meta_key );
			$check = maybe_unserialize( $this->glue_chunks( $chunks ) );

			return $single ? $check : array( $check );
		}

		return $check;
	}

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

		return $meta_values;
	}

	public function glue_chunks( array $chunks ) {
		$ordered_chunks = array();
		foreach ( $chunks as $chunk ) {
			preg_match( "/(\\d+)" . preg_quote( $this->chunk_separator ) . "(.*)/", $chunk, $matches );
			$ordered_chunks[ $matches[1] ] = $matches[2];
		}
		ksort( $ordered_chunks );

		return implode( '', array_values( $ordered_chunks ) );
	}

	public function unhook() {
		foreach ( $this->post_types as $post_type ) {
			remove_filter( "update_{$post_type}_metadata", array( $this, 'filter_update_metadata' ), $this->filter_p );
			remove_filter( "delete_{$post_type}_metadata", array( $this, 'filter_delete_metadata' ), $this->filter_p );
			remove_filter( "add_{$post_type}_metadata", array( $this, 'filter_add_metadata' ), $this->filter_p );
			remove_filter( "get_{$post_type}_metadata", array( $this, 'filter_get_metadata' ), $this->filter_p );
		}
	}
}