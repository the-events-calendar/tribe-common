<?php

/**
 * Class Tribe__Repository__Update
 *
 * @since TBD
 *
 * The basic Update repository; this is the kind of object you get back from a
 * method like `tribe_events()->update()`.
 */
class Tribe__Repository__Update
	extends Tribe__Repository__Read
	implements Tribe__Repository__Update_Interface {
	protected static $blocked_keys = array(
		'ID',
		'post_type',
	);
	/**
	 * @var array The post IDs that will be updated.
	 */
	protected $ids = array();
	/**
	 * @var bool Whether the post IDs to update have already been fetched or not.
	 */
	protected $has_ids = false;

	/**
	 * @var array The updates that will be saved to the database.
	 */
	protected $updates = array();

	/**
	 * @var array A list of taxonomies this repository will recognize.
	 */
	protected $taxonomies = array();

	/**
	 * @var array A map detailing which fields should be converted from a
	 *            GMT time and date to a local one.
	 */
	protected $to_local_time_map = array(
		'post_date_gmt'     => 'post_date',
		'post_modified_gmt' => 'post_modified',
	);

	/**
	 * @var array A map detailing which fields should be converted from a
	 *            localized time and date to a GMT one.
	 */
	protected $to_gmt_map = array(
		'post_date'     => 'post_date_gmt',
		'post_modified' => 'post_modified_gmt',
	);

	/**
	 * Tribe__Repository__Update constructor.
	 *
	 * @since TBD
	 *
	 * @param array                            $schema Similarly to a Read repository the Update
	 *                                                 repository will first need to fetch the post
	 *                                                 to update them; the schema defines the available
	 *                                                 repository filters.
	 * @param Tribe__Repository__Query_Filters $query_filters
	 * @param array                            $default_args
	 */

	public function __construct( array $schema, Tribe__Repository__Query_Filters $query_filters, array $default_args = array() ) {
		parent::__construct( $schema, $query_filters, $default_args );
		$post_types       = Tribe__Utils__Array::get( $this->default_args, 'post_type', array() );
		$this->taxonomies = get_taxonomies( $post_types );
	}

	/**
	 * Commits the updates to the selected post IDs to the database.
	 *
	 * @since TBD
	 *
	 * @param bool $sync Whether to apply the updates in a synchronous process
	 *                   or in an asynchronous one.
	 *
	 * @return array A list of the post IDs that have been (synchronous) or will
	 *               be (asynchronous) updated. When running in sync mode the return
	 *               value will be a map in the shape [ <id> => <update_result> ] where
	 *               `true` indicates a correct update.
	 *
	 * @throws Tribe__Repository__Usage_Error If trying to update a field that cannot be
	 *                                        updated.
	 */
	public function save( $sync = true ) {
		$ids = $this->get_ids();

		if ( empty( $ids ) ) {
			return array();
		}

		$postarrs = array();

		foreach ( $ids as $id ) {
			$postarr = array(
				'ID'         => $id,
				'tax_input'  => array(),
				'meta_input' => array(),
			);

			foreach ( $this->updates as $key => $value ) {
				if ( is_callable( $value ) ) {
					$value = call_user_func( $value, $id, $key, $this );
				}

				if ( ! $this->can_be_udpated( $key ) ) {
					throw Tribe__Repository__Usage_Error::because_this_field_cannot_be_updated( $key, $this );
				}

				if ( $this->is_a_post_field( $key ) ) {
					if ( $this->requires_converted_date( $key ) ) {
						$this->update_postarr_dates( $key, $value, $postarr );
					} else {
						$postarr[ $key ] = $value;
					}
				} elseif ( $this->is_a_taxonomy( $key ) ) {
					$postarr['tax_input'][ $key ] = $value;
				} else {
					// it's a custom field
					$postarr['meta_input'][ $key ] = $value;
				}
			}

			$postarrs[ $id ] = $postarr;
		}

		// @todo actually implement async

		foreach ( $postarrs as $id => $postarr ) {
			$this_exit   = wp_update_post( $postarr );
			$exit[ $id ] = $id === $this_exit ? true : $this_exit;
		}

		return $exit;
	}

	/**
	 * Whether the current key can be updated by this repository or not.
	 *
	 * @since TBD
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	protected function can_be_udpated( $key ) {
		return ! in_array( $key, self::$blocked_keys, true );
	}

	/**
	 * Whether the key is a field of the posts table or not.
	 *
	 * @since TBD
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	protected function is_a_post_field( $key ) {
		return in_array( $key, array(
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
		), true );
	}

	/**
	 * Whether the current key is a date one requiring a converted key pair too or not.
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	protected function requires_converted_date( $key ) {
		return array_key_exists( $key, $this->to_local_time_map ) || array_key_exists( $key, $this->to_gmt_map );
	}

	/**
	 * Updates the update post payload to add dates that should be provided in GMT
	 * and localized version.
	 *
	 * @since TBD
	 *
	 * @param       string     $key
	 * @param       string|int $value
	 * @param array            $postarr
	 */
	protected function update_postarr_dates( $key, $value, array &$postarr ) {
		if ( array_key_exists( $key, $this->to_gmt_map ) ) {
			$postarr[ $this->to_gmt_map[ $key ] ] = Tribe__Timezones::to_tz( $value, 'UTC' );
		} elseif ( array_key_exists( $key, $this->to_local_time_map ) ) {
			$postarr[ $this->to_local_time_map[ $key ] ] = Tribe__Timezones::to_tz( $value, Tribe__Timezones::wp_timezone_string() );
		}
		$postarr[ $key ] = $value;
	}

	/**
	 * Whether the current key identifies one of the supported taxonomies or not.
	 *
	 * @since TBD
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	protected function is_a_taxonomy( $key ) {
		return in_array( $key, $this->taxonomies, true );
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_args( array $update_map ) {
		foreach ( $update_map as $key => $value ) {
			$this->set( $key, $value );
		}

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set( $key, $value ) {
		if ( ! is_string( $key ) ) {
			throw Tribe__Repository__Usage_Error::because_udpate_key_should_be_a_string( $this );
		}

		$this->updates[ $key ] = $value;

		return $this;
	}

	/**
	 * Gets the post IDs that should be updated.
	 *
	 * @since TBD
	 *
	 * @return array An array containing the post IDs to update.
	 */
	protected function get_ids() {
		$query = $this->build_query();
		$query->set( 'fields', 'ids' );

		return $query->get_posts();
	}

	public function set_main_repository( Tribe__Repository__Interface $main_repository ) {
		// TODO: Implement set_main_repository() method.
	}
}