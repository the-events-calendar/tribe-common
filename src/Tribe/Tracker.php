<?php

/**
 * Class Tribe__Tracker
 *
 * Tracks changes of post attributes.
 */
class Tribe__Tracker {
	/**
	 * @var string The meta field used to track changes.
	 */
	public static $field_key = '_tribe_modified_fields';

	/**
	 * @var bool Whether the class is tracking terms or not.
	 */
	protected $track_terms = true;

	/**
	 * @var array An array of the tracked post types.
	 */
	protected $tracked_post_types = array();

	/**
	 * @var array An array of the tracked taxonomies.
	 */
	protected $tracked_taxonomies = array();

	/**
	 * An array detailing the linking post types tracked by the tracker.
	 * The array has a shape like [ <post_type> => [ 'from_type' => <post_type>, 'with_key' => <meta_key> ] ]
	 * where the `from_type` entry can be a string or an array of post types.
	 *
	 * @var array
	 */
	protected $linked_post_types = array();

	/**
	 * Hooks up the methods that will actually track the fields we are looking for.
	 */
	public function hook() {
		// Track the Meta Updates for Meta That came from the correct Post Types
		add_filter( 'update_post_metadata', array( $this, 'filter_watch_updated_meta' ), PHP_INT_MAX - 1, 5 );

		// After a meta is added we mark that is has been modified
		add_action( 'added_post_meta', array( $this, 'register_added_deleted_meta' ), PHP_INT_MAX - 1, 3 );

		// Before a meta is removed we mark that is has been modified
		add_action( 'delete_post_meta', array( $this, 'register_added_deleted_meta' ), PHP_INT_MAX - 1, 3 );

		// Track the Post Fields Updates for Meta in the correct Post Types
		add_action( 'post_updated', array( $this, 'filter_watch_post_fields' ), 10, 3 );

		// Track the Post term updates
		add_action( 'set_object_terms', array( $this, 'track_taxonomy_term_changes' ), 10, 6 );

		// Track the Post term deletions
		add_action( 'delete_term_relationships', array( $this, 'track_taxonomy_term_deletions' ), 10, 6 );

		// Track post field updates
		add_action( 'post_updated', array( $this, 'on_post_updated' ) );

		// Clean up modified fields if the post is removed.
		add_action( 'delete_post', array( $this, 'on_delete_post' ) );
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
	 * Easy way to see currently which post types are been tracked by our code.
	 *
	 * @return array
	 */
	public function get_post_types() {
		// By default we are not tracking anything
		$tracked_post_types = array();

		/**
		 * Adds a way for Developers to add and remove which post types will be tracked
		 *
		 * Note: Removing any of the default methods will affect how we deal with fields
		 *       affected by the authority settings defined on this installation
		 *
		 * @var array
		 */
		$tracked_post_types = (array) apply_filters( 'tribe_tracker_post_types', $this->tracked_post_types );

		return $tracked_post_types;
	}

	/**
	 * Easy way to see currenlty which meta values are been tracked by our code
	 *
	 * @return array
	 */
	public function get_excluded_meta_keys() {
		// By default we are not tracking anything
		$excluded_keys = array(
			'_edit_lock',
			self::$field_key,
		);

		/**
		 * Adds a way for Developers remove Meta Keys that shouldn't be tracked
		 *
		 * Note: Removing any of the default methods will affect how we deal with fields
		 *       affected by the authority settings defined on this installation
		 *
		 * @var array
		 */
		$excluded_keys = (array) apply_filters( 'tribe_tracker_excluded_meta_keys', $excluded_keys );

		return $excluded_keys;
	}

	/**
	 * Make sure we are tracking all meta fields related on the correct Post Types
	 *
	 * @since 4.5
	 *
	 * @param int       $meta_id    Meta ID
	 * @param int       $post_id    Post ID.
	 * @param string    $meta_key   Meta key.
	 */
	public function register_added_deleted_meta( $meta_id, $post_id, $meta_key ) {
		/**
		 * Allows toggling the Modified fields tracking
		 * @var bool
		 */
		$is_tracking_modified_fields = (bool) apply_filters( 'tribe_tracker_enabled', true );

		// Bail if we shouldn't be tracking modifications
		if ( false === $is_tracking_modified_fields ) {
			return;
		}

		// Try to fetch the post object
		$post = get_post( $post_id );

		// We only go forward if we have the Post Object
		if ( ! $post instanceof WP_Post ) {
			return;
		}

		// Fetch from a unified method which meta keys are been excluded
		$excluded_keys = $this->get_excluded_meta_keys();

		// Bail when this meta is set to be excluded
		if ( in_array( $meta_key, $excluded_keys ) ) {
			return;
		}

		// Fetch from a unified method which post types are been tracked
		$tracked_post_types = $this->get_post_types();

		// Only track if the meta is from a post that is been tracked
		if ( ! in_array( $post->post_type, $tracked_post_types ) ) {
			return;
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

		// Actually do the Update
		$this->update_tracked_fields( $post, $modified );
	}

	/**
	 * Make sure we are tracking all meta fields related to the correct Post Types
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

		// Fetch from a unified method which meta keys are been excluded
		$excluded_keys = $this->get_excluded_meta_keys();

		// Bail when this meta is set to be excluded
		if ( in_array( $meta_key, $excluded_keys ) ) {
			return $check;
		}

		// Fetch from a unified method which post types are been tracked
		$tracked_post_types = $this->get_post_types();

		// Only track if the meta is from a post that is been tracked
		if ( ! in_array( $post->post_type, $tracked_post_types ) ) {
			return $check;
		}

		if ( empty( $prev_value ) ) {
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

		// Actually do the Update
		$this->update_tracked_fields( $post, $modified );

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
			'post_excerpt',
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

	/**
	 * Track term changes for the tracked post types and  terms.
	 *
	 * Meant to run on the `set_object_terms` action.
	 *
	 * @see wp_set_object_terms()
	 *
	 * @param $object_id
	 * @param $terms
	 * @param $tt_ids
	 * @param $taxonomy
	 * @param $append
	 * @param $old_tt_ids
	 *
	 * @return bool `true` if the post type and taxonomy are tracked, `false` otherwise.
	 */
	public function track_taxonomy_term_changes( $object_id, $terms, $tt_ids, $taxonomy, $append, $old_tt_ids ) {
		/**
		 * Allows toggling the post taxonomy terms tracking
		 *
		 * @var bool $track_terms Whether the class is currently tracking terms or not.
		 */
		$is_tracking_taxonomy_terms = (bool) apply_filters( 'tribe_tracker_enabled_for_terms', $this->track_terms );

		if ( false === $is_tracking_taxonomy_terms ) {
			return false;
		}

		$tracked_post_types = $this->get_post_types();

		$post_id = tribe_post_exists( $object_id );

		if (
			empty( $post_id )
			|| ! ( $post = get_post( $post_id ) )
			|| ! in_array( $post->post_type, $tracked_post_types )
		) {
			return false;
		}

		$tracked_taxonomies = $this->get_taxonomies();

		if ( ! in_array( $taxonomy, $tracked_taxonomies ) ) {
			return false;
		}

		if ( ! $modified = get_post_meta( $post->ID, self::$field_key, true ) ) {
			$modified = array();
		}

		if ( $tt_ids == $old_tt_ids ) {
			// nothing to update, still we did the job
			return true;
		}

		$modified[ $taxonomy ] = time();

		$this->update_tracked_fields( $post, $modified );

		return true;
	}

	/**
	 * Easy way to see currently which taxonomies are been tracked by our code.
	 *
	 * @return array
	 */
	public function get_taxonomies() {
		/**
		 * Adds a way for Developers to add and remove which taxonomies will be tracked
		 *
		 * Note: Removing any of the default methods will affect how we deal with fields
		 *       affected by the authority settings defined on this installation
		 *
		 * @var array $tracked_taxonomies An array of the tracker taxonomies names.
		 */
		$tracked_taxonomies = (array) apply_filters( 'tribe_tracker_taxonomies', $this->tracked_taxonomies );

		return $tracked_taxonomies;
	}

	/**
	 * Whether taxonomy term changes should be tracked or not by the class.
	 *
	 * @param bool $track_terms
	 */
	public function should_track_terms( $track_terms ) {
		$this->track_terms = $track_terms;
	}

	/**
	 * Sets the taxonomies the tracker should track.
	 *
	 * @param array $tracked_taxonomies
	 */
	public function set_tracked_taxonomies( array $tracked_taxonomies ) {
		$this->tracked_taxonomies = $tracked_taxonomies;
	}

	/**
	 * Sets the post types the tracker should track.
	 *
	 * @param array $tracked_post_types
	 */
	public function set_tracked_post_types( array $tracked_post_types ) {
		$this->tracked_post_types = $tracked_post_types;
	}

	/**
	 * Fires on the post deletion to remove the changed field if the post is deleted to remove the meta fields
	 * and update the linking posts.
	 *
	 * @since 4.7.6
	 *
	 * @param int  Post ID
	 *
	 * @return bool
	 */
	public function on_delete_post( $post_id ) {
		$deleted = delete_post_meta( (int) $post_id, self::$field_key );
		$this->update_linking_posts( $post_id );

		return $deleted;
	}

	/**
	 * Updates the modified fields custom field.
	 *
	 * Additionally, if the post that is being updated is a linked post, linking posts (the "from"
	 * side of the post-to-post relation) are updated.
	 *
	 * @since TBD
	 *
	 * @param WP_Post $post The post object that's being updated.
	 * @param array $modified The list of modified fields w/ shape [ <field> => <date> ].
	 */
	protected function update_tracked_fields( WP_Post $post, array $modified ) {
		$this->unhook();
		wp_update_post( array(
			'ID'         => $post->ID,
			'meta_input' => array( self::$field_key => $modified ),
			// we set this to avoid the `post_date` from being reset
			'edit_date'  => true,
		) );
		$this->update_linking_posts( $post );
		$this->hook();
	}

	/**
	 * Fires after a term relationship was removed to track removed object terms.
	 *
	 * @since TBD
	 *
	 * @param int $object_id Object ID.
	 * @param array $tt_ids An array of term taxonomy IDs.
	 * @param string $taxonomy Taxonomy slug.
	 *
	 * @return bool Whether the taxonomy term deletion was tracked or not.
	 */
	public function track_taxonomy_term_deletions( $object_id, $tt_ids, $taxonomy ) {
		/**
		 * Allows toggling the post taxonomy terms tracking
		 *
		 * @var bool $track_terms Whether the class is currently tracking terms or not.
		 */
		$is_tracking_taxonomy_terms = (bool) apply_filters( 'tribe_tracker_enabled_for_terms', $this->track_terms );

		if ( false === $is_tracking_taxonomy_terms ) {
			return false;
		}

		$tracked_post_types = $this->get_post_types();

		$post_id = tribe_post_exists( $object_id );

		if (
			empty( $post_id )
			|| ! ( $post = get_post( $post_id ) )
			|| ! in_array( $post->post_type, $tracked_post_types, true )
		) {
			return false;
		}

		$tracked_taxonomies = $this->get_taxonomies();

		if ( ! in_array( $taxonomy, $tracked_taxonomies, true ) ) {
			return false;
		}

		if ( ! $modified = get_post_meta( $post->ID, self::$field_key, true ) ) {
			$modified = array();
		}

		// if we're here we know at least one taxonomy term has been removed
		$modified[ $taxonomy ] = time();

		$this->update_tracked_fields( $post, $modified );

		return true;
	}

	/**
	 * Returns the linked post types the tracker should handle.
	 *
	 * A "linked" post type is the "to" end of a post to post relation.
	 * E.g. Venues are linked by Events.
	 *
	 * @since TBD
	 *
	 * @return array An array defining the linked post types, the linking post type(s)
	 *               and the meta key used by the linking post types to link to this post
	 *               type. The array has shape [ <post_type> => [ 'from_type' => <post_type>, 'with_key' => <meta_key> ] ]
	 */
	public function get_linked_post_types() {
		// By default we are not tracking any linking post type
		$linked_post_types = array();

		/**
		 * Adds a way for Developers to add and remove which post types should be considered
		 * linked.
		 *
		 * @since TBD
		 *
		 * @var array An array defining the linked post types in the shape
		 *            [ <post_type> => [ 'from_type' => <post_type>, 'with_key' => <meta_key> ] ];
		 *            The `from_type`  entry can be a string or an array of linked post types.
		 *            As an example [ 'venue' => [ 'from_type' => 'event', 'with_key' => 'venue_id' ] ].
		 */
		$linked_post_types = (array) apply_filters( 'tribe_tracker_linked_post_types', $this->linked_post_types );

		return $linked_post_types;
	}

	/**
	 * Updates the linking posts if the post type of `post_id` is a linked post type.
	 *
	 * E.g. update the event ("linking to") when the venue is updated ("linked from").
	 *
	 * @since TBD
	 *
	 * @param int|WP_Post $post_id
	 *
	 * @return bool Whether the linked posts have been updated or not; `false` if there
	 *              are no linking posts to update.
	 */
	public function update_linking_posts( $post_id ) {
		$post_id           = $post_id instanceof WP_Post ? $post_id->ID : $post_id;
		$linked_post_types = $this->get_linked_post_types();
		$post_type         = get_post_type( $post_id );

		if ( ! array_key_exists( $post_type, $linked_post_types ) ) {
			return false;
		}

		$post_types = $linked_post_types[ $post_type ]['from_type'];
		$meta_key   = $linked_post_types[ $post_type ]['with_key'];

		/**
		 * Get all the linking posts, as they might be events let's make sure
		 * to remove date filters.
		 */
		$linking_posts = get_posts( array(
			'fields' => 'ids',
			'posts_per_page'            => - 1,
			'tribe_remove_date_filters' => true,
			'post_type'                 => $post_types,
			'post_status'               => 'any',
			'meta_query'                => array(
				'key'   => $meta_key,
				'value' => (int) $post_id,
			),
		) );

		if ( empty( $linking_posts ) ) {
			return false;
		}

		$updated = true;

		$this->unhook();
		foreach ( $linking_posts as $linking_post ) {
			/**
			 * Here we leverage the fact that WordPress will override the `post_updated`
			 * input to set it to "now". Mind that here we do not care about the linking
			 * post coherence or information about a deleted linked post: that logic should be,
			 * and is, handled elsewhere.
			 */
			$updated &= (bool) wp_update_post( array(
				'ID'           => $linking_post,
				'post_updated' => 'now',
				// we set this to avoid the `post_date` from being reset
				'edit_date' => true,
			) );
		}
		$this->hook();

		return $updated;
	}

	/**
	 * Sets the linked post types the tracker should track.
	 *
	 * @since TBD
	 *
	 * @param array $linked_post_types An array defining the linked post types, shape
	 *                                 [ <post_type> => [ 'from_type' => <post_type>, 'with_key' => <meta_key> ] ].
	 */
	public function set_linked_post_types( array $linked_post_types ) {
		$this->linked_post_types = $linked_post_types;
	}

	/**
	 * Fires after a post has been updated to update the posts that might be linking to this.
	 *
	 * @since TBD
	 *
	 * @param int $post_id The updated post ID.
	 */
	public function on_post_updated( $post_id ) {
		/**
		 * Allows toggling the updating of linked posts.
		 *
		 * @param bool           $will_update_links Whether to update the linked posts.
		 * @param int            $post_id           The updated post ID.
		 * @param Tribe__Tracker $tracker           The tracker object.
		 *
		 * @since TBD
		 */
		$will_update_links = (bool) apply_filters( 'tribe_tracker_post_update_links', false, $post_id, $this );

		if ( false === $will_update_links ) {
			return;
		}

		$this->update_linking_posts( $post_id );
	}

	/**
	 * Un-hooks the Tracker actions and filters.
	 *
	 * @since TBD
	 */
	public function unhook() {
		remove_filter( 'update_post_metadata', array( $this, 'filter_watch_updated_meta' ), PHP_INT_MAX - 1 );
		remove_action( 'added_post_meta', array( $this, 'register_added_deleted_meta' ), PHP_INT_MAX - 1 );
		remove_action( 'delete_post_meta', array( $this, 'register_added_deleted_meta' ), PHP_INT_MAX - 1 );
		remove_action( 'post_updated', array( $this, 'filter_watch_post_fields' ) );
		remove_action( 'set_object_terms', array( $this, 'track_taxonomy_term_changes' ) );
		remove_action( 'delete_term_relationships', array( $this, 'track_taxonomy_term_deletions' ) );
		remove_action( 'post_updated', array( $this, 'on_post_updated' ) );
		remove_action( 'delete_post', array( $this, 'on_delete_post' ) );
	}
}
