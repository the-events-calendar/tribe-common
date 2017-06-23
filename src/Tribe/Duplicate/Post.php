<?php

/**
 * Class Tribe__Duplicate__Post
 *
 * Provides the functionality to find an existing post starting from the post data.
 *
 * @since TBD
 */
class Tribe__Duplicate__Post {
	/**
	 * @var array The columns of the post table.
	 */
	public static $post_table_columns = array(
		'ID',
		'post_author',
		'post_date',
		'post_date_gmt',
		'post_content',
		'post_title',
		'post_excerpt',
		'post_status',
		'comment_status',
		'ping_status',
		'post_password',
		'post_name',
		'to_ping',
		'pinged',
		'post_modified',
		'post_modified_gmt',
		'post_content_filtered',
		'post_parent',
		'guid',
		'menu_order',
		'post_type',
		'post_mime_type',
		'comment_count',
	);

	/**
	 * @var array The post fields that should be used to find a duplicate.
	 */
	protected $post_fields = array();

	/**
	 * @var array The custom fields that should be used to find a duplicate.
	 */
	protected $custom_fields = array();

	/**
	 * @var Tribe__Duplicate__Strategy_Factory
	 */
	protected $factory;

	/**
	 * Tribe__Duplicate__Post constructor.
	 *
	 * @param Tribe__Duplicate__Strategy_Factory|null $factory
	 *
	 * @since TBD
	 */
	public function __construct( Tribe__Duplicate__Strategy_Factory $factory = null ) {
		$this->factory = null !== $factory ? $factory : tribe( 'post-duplicate.strategy-factory' );
	}

	/**
	 * Sets the post fields that should be used to find a duplicate in the database.
	 *
	 * Each entry should be in the [ <post field> => [ 'match' => <strategy> ]] format.
	 * If not the strategy will be set to the default one.
	 *
	 * @param array $post_fields
	 *
	 * @since TBD
	 */
	public function use_post_fields( array $post_fields ) {
		if ( empty( $post_fields ) ) {
			$this->post_fields = array();

			return;
		}

		$cast = $this->cast_to_strategy( $post_fields );
		$this->post_fields = array_intersect_key( $cast, array_combine( self::post_table_columns, self::post_table_columns ) );
	}

	/**
	 * Converts an array of fields to the format required by the class.
	 *
	 * @param array $fields
	 *
	 * @return array
	 *
	 * @since TBD
	 */
	protected function cast_to_strategy( array $fields ) {
		$cast = array();

		foreach ( $fields as $key => $value ) {
			if ( is_numeric( $key ) ) {
				$cast[ $value ] = array( 'match' => 'same' );
			} elseif ( is_array( $value ) ) {
				if ( ! empty( $value['match'] ) ) {
					$cast[ $key ] = $value;
				} else {
					$cast[ $key ] = array_merge( $value, array( 'match' => 'same' ) );
				}
			}
		}

		return $cast;
	}

	/**
	 * Finds a duplicate with the data provided.
	 *
	 * The more post and custom fields are used to find a match the less likely it is to find one and the more
	 * likely it is for a duplicate to be a good match.
	 *
	 * @param array $postarr An array of post data, post fields and custom fields, that should be used to find the duplicate.
	 *
	 * @return bool|int `false` if a duplicate was not found, the post ID of the duplicate if found.
	 *
	 * @since TBD
	 */
	public function find_for( array $postarr ) {
		if ( empty( $this->post_fields ) && empty( $this->custom_fields ) ) {
			return false;
		}

		$where_frags = array();
		$join = '';

		/** @var wpdb $wpdb */
		global $wpdb;

		if ( ! empty( $this->post_fields ) ) {
			$queryable_post_fields = array_intersect_key( $postarr, $this->post_fields );
			if ( empty( $queryable_post_fields ) ) {
				return false;
			}
			foreach ( $queryable_post_fields as $key => $value ) {
				$match_strategy = $this->factory->make( $this->post_fields[ $key ]['match'] );
				$where_frags[] = $match_strategy->where( $key, $postarr[ $key ] );
			}
		}

		if ( ! empty( $this->custom_fields ) ) {
			// we had post fields and found a match
			$queryable_custom_fields = array_intersect_key( $postarr, $this->custom_fields );
			$i = 0;
			foreach ( $queryable_custom_fields as $key => $value ) {
				$match_strategy = $this->factory->make( $this->custom_fields[ $key ]['match'] );
				$meta_value = is_array( $value ) ? reset( $value ) : $value;
				$where_frags[] = $match_strategy->where_custom_field( $key, $meta_value, "pm{$i}" );
				$i ++;
			}
			$join = '';
			$count = count( $where_frags );
			for ( $i = 0; $i < $count; $i ++ ) {
				$join .= " \nLEFT JOIN {$wpdb->postmeta} pm{$i} ON pm{$i}.post_id = {$wpdb->posts}.ID ";
			}
		}

		$where = implode( " \nAND ", $where_frags );
		$prepared = "SELECT ID from {$wpdb->posts} {$join} \nWHERE {$where}";
		$id = $wpdb->get_var( $prepared );

		return ! empty( $id ) ? $id : false;
	}

	/**
	 * Sets the custom fields that should be used to find a duplicate in the database.
	 *
	 * Each entry should be in the [ <custom field> => [ 'match' => <strategy> ]] format.
	 * If not the strategy will be set to the default one.
	 *
	 * @param array $custom_fields
	 *
	 * @since TBD
	 */
	public function use_custom_fields( array $custom_fields ) {
		$cast = $this->cast_to_strategy( $custom_fields );
		$this->custom_fields = $cast;
	}
}