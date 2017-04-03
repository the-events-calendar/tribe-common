<?php
class Tribe__Tracker {
	public static $field_key = '_tribe_modified_fields';

	/**
	 * Hooks up the methods that will actually track the fields we are looking for.
	 */
	public function hook() {
		// Track the Meta Updates for Meta That came from the correct Post Types
		add_filter( 'update_post_metadata', array( $this, 'filter_watch_updated_meta' ), PHP_INT_MAX - 1, 5 );

		// Track the Post Fields Updates for Meta in the correct Post Types
		add_action( 'post_updated', array( $this, 'filter_watch_post_fields' ), 10, 3 );
	}

	/**
	 * Determines if a post value has been changed
	 *
	 * @param string $field Field to compare against
	 * @param array $new New data
	 * @param array $old WP_Post pre-update
	 *
	 * @return boolean
	 */
	public function has_field_changed( $field, $new, $old ) {
		if ( ! is_object( $new ) ) {
			$new = (object) $new;
		}

		if ( ! is_object( $old ) ) {
			$old = (object) $old;
		}

		if ( ! isset( $new->$field ) ) {
			return false;
		}

		if ( isset( $new->$field ) && ! isset( $old->$field ) ) {
			return true;
		}

		if ( $new->$field !== $old->$field ) {
			return true;
		}

		return false;
	}

	/**
	 * Easy way to see currenlty which post types are been tracked by our code
	 *
	 * @return array
	 */
	public function get_post_types() {
		// By default we are not tracking anything
		$tracked_post_types = array(
			Tribe__Events__Main::POSTTYPE,
			Tribe__Events__Venue::POSTTYPE,
			Tribe__Events__Organizer::POSTTYPE,
		);

		/**
		 * Adds a way for Developers to add and remove which post types will be tracked
		 *
		 * Note: Removing any of the default methods will affect how we deal with fields
		 *       affected by the authority settings defined on this installation
		 *
		 * @var array
		 */
		$tracked_post_types = (array) apply_filters( 'tribe_tracker_post_types', $tracked_post_types );

		return $tracked_post_types;
	}

	/**
	 * Make sure we are tracking all meta fields related to Event Aggregator
	 *
	 * @since 4.5
	 *
	 * @param null|bool $check      Whether to allow updating metadata for the given type.
	 * @param int       $post_id    Post ID.
	 * @param string    $meta_key   Meta key.
	 * @param mixed     $meta_value Meta value. Must be serializable if non-scalar.
	 * @param mixed     $prev_value Previous Value of the Meta, allowing to check the data
	 *
	 * @return null|bool            This should be ignored, only used to not break the WordPress filters
	 */
	public function filter_watch_updated_meta( $check, $post_id, $meta_key, $meta_value, $prev_value = null ) {
		bdump( $meta_key );
		/**
		 * Allows toggling the Modified fields tracking
		 * @var bool
		 */
		$is_tracking_modified_fields = (bool) apply_filters( 'tribe_tracker_enabled', true );

		// Bail if we shouldn't be tracking modifications
		if ( false === $is_tracking_modified_fields ) {
			return $check;
		}

		// Matching the WordPress filter, we actually don't care about Check at all!
		if ( null !== $check ) {
			return (bool) $check;
		}

		// Try to fetch the post object
		$post = get_post( $post_id );

		// We only go forward if we have the Post Object
		if ( ! $post instanceof WP_Post ) {
			return $check;
		}

		// Bail when it's the actual modified field key
		if ( self::$field_key === $meta_key ) {
			return $check;
		}

		// Fetch from a unified method which post types are been tracked
		$tracked_post_types = $this->get_post_types();

		// Only track if the meta is from a post that is been tracked
		if ( ! in_array( $post->post_type, $tracked_post_types ) ) {
			return $check;
		}

		// If we don't have any Prev Value we check for it from the Database
		if ( is_null( $prev_value ) || '' === $prev_value ) {
			$prev_value = get_post_meta( $post->ID, $meta_key, true );
		}

		// We don't care if the value didn't actually change
		if ( $prev_value == $meta_value ) {
			return $check;
		}

		// Gets the Current Timestamp
		$now = current_time( 'timestamp' );

		// Fetch the current data from the modified fields
		$modified = get_post_meta( $post->ID, self::$field_key, true );
		if ( ! is_array( $modified ) ) {
			$modified = array();
		}

		// If we got here we will update the Modified Meta
		$modified[ $meta_key ] = $now;

		bdump( $modified );

		// Actually do the Update
		update_post_meta( $post->ID, self::$field_key, $modified );

		// We need to return this, because we are still on a filter
		return $check;
	}

	/**
	 * Tracks fields that are changed when an event is updated
	 *
	 * @param int $post_id Post ID
	 * @param WP_Post $post_after New post object
	 * @param WP_Post $post_before Old post object
	 */
	public function filter_watch_post_fields( $post_id, $post_after, $post_before ) {
		/**
		 * Allows toggling the Modified fields tracking
		 * @var bool
		 */
		$is_tracking_modified_fields = (bool) apply_filters( 'tribe_tracker_enabled', true );

		// Bail if we shouldn't be tracking modifications
		if ( false === $is_tracking_modified_fields ) {
			return;
		}

		// Fetch from a unified method which post types are been tracked
		$tracked_post_types = $this->get_post_types();

		// Only track if the meta is from a post that is been tracked
		if ( ! in_array( $post_before->post_type, $tracked_post_types ) ) {
			return;
		}

		// Fetch the current Time
		$now = current_time( 'timestamp' );

		if ( ! $modified = get_post_meta( $post_id, self::$field_key, true ) ) {
			$modified = array();
		}

		$fields_to_check_for_changes = array(
			'post_title',
			'post_content',
			'post_status',
			'post_type',
			'post_parent',
		);

		foreach ( $fields_to_check_for_changes as $field ) {
			if ( ! $this->has_field_changed( $field, $post_after, $post_before ) ) {
				continue;
			}

			$modified[ $field ] = $now;
		}

		if ( $modified ) {
			update_post_meta( $post_id, self::$field_key, $modified );
		}
	}
}