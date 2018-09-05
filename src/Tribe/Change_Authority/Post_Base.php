<?php

/**
 * Class Tribe__Change_Authority__Post_Base
 *
 * The basic change authority to handle post fields and meta propagation.
 */
abstract class Tribe__Change_Authority__Post_Base extends Tribe__Change_Authority__Base {
	/**
	 * The fields that indicate a `posts` table column.
	 * @var array
	 */
	protected $post_fields = array(
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
		'to_ping',
		'pinged',
		'post_content_filtered',
		'menu_order',
		'post_type',
		'post_mime_type',
	);

	/** The fields that indicate a `posts` table column that should never be propagated.
	 * @var array
	 */
	protected $blocked_post_fields = array(
		'ID',
		'post_name',
		'post_modified',
		'post_modified_gmt',
		'post_parent',
		'guid',
		'comment_count',
	);

	/**
	 * An array specifying the taxonomies that should be propagated from the source post to the destination post.
	 * @var array
	 */
	protected $propagate_taxonomies = array();

	/**
	 * An array that will be used to store the data to batch update the post fields.
	 * @var array
	 */
	protected $batched_post_fields = array();

	/**
	 * Whether a field should be propagated from the source to the destination.
	 *
	 * @param mixed  $from  The source object or data.
	 * @param mixed  $to    The destination object or data.
	 * @param string $field The name of the field that's to be evaluated for propagation.
	 *
	 * @return bool Whether the field should be propagated or not.
	 */
	public function should_propagate( $from, $to, $field ) {
		return ! in_array( $field, $this->blocked_post_fields ) && parent::should_propagate( $from, $to, $field );
	}

	/**
	 * Propagates a field from the source to the destination.
	 *
	 * @param mixed  $from  The source object or data.
	 * @param mixed  $to    The destination object or data.
	 * @param string $field The name of the field that's to be evaluated for propagation.
	 *
	 * @return bool Whether the field was propagated or not.
	 */
	public function propagate_field( $from, $to, $field ) {
		list( $from, $to ) = $this->cast_to_objects( $from, $to );

		if ( in_array( $field, $this->blocked_post_fields ) ) {
			return false;
		}

		if ( $this->is_taxonomy( $field ) ) {
			return $this->propagate_taxonomy_terms( $from, $to, $field );
		}

		if ( ! $this->is_post_field( $field ) ) {
			return $this->propagate_meta_field( $from, $to, $field );
		}

		return $this->propagate_post_field( $from, $to, $field );
	}

	/**
	 * Casts the source and destinations to parseable objects.
	 *
	 * @param $from
	 * @param $to
	 *
	 * @return array
	 */
	protected function cast_to_objects( $from, $to ) {
		if ( is_numeric( $from ) ) {
			$from = get_post( $from );
		} elseif ( ! is_object( $from ) ) {
			$from = (object) $from;
		}

		$to = get_post( $to );

		return array( $from, $to );
	}

	/**
	 * Whether the specified field is a post field from the `posts` table or not.
	 *
	 * @param string $field The field name
	 *
	 * @return bool
	 */
	protected function is_post_field( $field ) {
		return in_array( $field, $this->post_fields );
	}

	/**
	 * Propagates the changes from the source to the destination.
	 *
	 * @param mixed $from The source object or data.
	 * @param mixed $to   The destination object or data.
	 *
	 * @return array An associative array in the format [ <field> => <propagated> ]
	 */
	public function propagate( $from, $to ) {
		list( $from, $to ) = $this->cast_to_objects( $from, $to );

		$result = parent::propagate( $from, $to );

		if ( ! empty( $this->batched_post_fields ) ) {
			$result = $this->batch_update_post_fields( $result );
		}

		return $result;
	}

	/**
	 * Propagates all the values of a post meta field from the source to the destination.
	 *
	 * @param WP_Post|stdClass $from
	 * @param WP_Post          $to
	 * @param         string   $field
	 *
	 * @return bool Whether the custom field was propagated or not.
	 */
	protected function propagate_meta_field( $from, WP_Post $to, $field ) {
		// we need this due to WP_Post magic __get method
		$from_array = (array) $from;
		if ( isset( $from_array[ $field ] ) ) {
			$from_meta = $from_array[ $field ];
		} else {
			$from_meta = get_post_meta( $from->ID, $field );
		}

		delete_post_meta( $to->ID, $field );

		if ( ! is_array( $from_meta ) ) {
			$from_meta = array( $from_meta );
		}

		if ( empty( $from_meta ) ) {
			return true;
		}

		$done = true;
		foreach ( $from_meta as $entry ) {
			$done &= (bool) add_post_meta( $to->ID, $field, $entry );
		}

		return $done;
	}

	/**
	 * Propagates a post field from the source to the destination.
	 *
	 * @param WP_Post|stdClass $from
	 * @param WP_Post          $to
	 * @param string           $field
	 * @param bool             $batch Whether the field should be immediately propagated, with a dedicated
	 *                                `wp_update_post` call, or not.
	 *
	 * @return bool Whether the field was propagated or not.
	 */
	protected function propagate_post_field( $from, WP_Post $to, $field, $batch = true ) {
		$postarr = array( 'ID' => $to->ID, $field => $from->$field );

		if ( in_array( $field, array( 'post_date', 'post_date_gmt' ) ) ) {
			// to be able to set the post date(s) we need to explicitly
			// declare we want to edit the date
			$postarr['edit_date'] = true;
		}

		if ( $batch ) {
			$this->batched_post_fields = array_merge( $this->batched_post_fields, $postarr );

			return true;
		}

		return (bool) wp_update_post( $postarr );
	}

	/**
	 * Sets the names of the taxonomies that should be propagated from the source to the destination.
	 *
	 * @param array $taxonomies
	 */
	public function set_taxonomies( array $taxonomies ) {
		$this->propagate_taxonomies = $taxonomies;
	}

	/**
	 * Whether the field is a taxonomy name or not.
	 *
	 * @param string $field
	 *
	 * @return bool
	 */
	protected function is_taxonomy( $field ) {
		return taxonomy_exists( $field );
	}

	/**
	 * Replaces the destination taxonomy terms with the source taxonomy terms.
	 *
	 * @param WP_Post|stdClass $from
	 * @param WP_Post $to
	 * @param  string $field
	 *
	 * @return array|bool
	 */
	protected function propagate_taxonomy_terms( $from, WP_Post $to, $field ) {
		if ( isset( $from->{$field} ) ) {
			/*
			 * If the taxonomy terms have been provided already then let's use those.
			 * An empty array here is fine as it might reflect the intention to remove
			 * the object terms.
			 */
			$from_terms = $from->{$field};
		} else {
			// Else fetch them from the db.
			$from_terms = wp_get_object_terms( $from->ID, $field, array( 'fields' => 'ids' ) );
		}

		/*
		 * Terms can be provided by ID or slug.
		 * Terms can have numeric slugs that reflect the term name; here we make sure that
		 * any numeric string is cast to integer if it's not representing an existing slug.
		 */
		foreach ( $from_terms as &$term ) {
			if ( ! ( is_string( $term ) && is_numeric( $term ) ) ) {
				continue;
			}

			$exists = get_term_by( 'slug', $term, $field );

			if ( $exists ) {
				continue;
			}

			$term = (int) $term;
		}

		$set = wp_set_object_terms( $to->ID, $from_terms, $field, false );

		return is_wp_error( $set ) ? false : $set;
	}

	/**
	 * To avoid calling `wp_update_post` for each post field we batch update
	 * the post fields.
	 *
	 * @since TBD
	 *
	 * @return array An associative array in the format [ <field> => <propagated> ]
	 *
	 * @return array The updated associative array in the format [ <field> => <propagated> ]
	 */
	protected function batch_update_post_fields( $result ) {
		if ( ! isset( $this->batched_post_fields['ID'] ) ) {
			return $result;
		}

		if ( 1 === count( $this->batched_post_fields ) ) {
			return $result;
		}

		/**
		 * If `post_date` and `post_date_gmt` both are set we assume the farthest away of the two is correct.
		 */
		if ( isset( $this->batched_post_fields['post_date'], $this->batched_post_fields['post_date_gmt'] ) ) {
			if ( get_gmt_from_date( $this->batched_post_fields['post_date'] ) < $this->batched_post_fields['post_date_gmt'] ) {
				unset( $this->batched_post_fields['post_date'] );
			} else {
				$this->batched_post_fields['post_date_gmt'] = get_gmt_from_date( $this->batched_post_fields['post_date'] );
			}
		}

		$post_updated = (bool) wp_update_post( $this->batched_post_fields );

		// remove some "utility" variables
		unset( $this->batched_post_fields['ID'], $this->batched_post_fields['edit_date'] );

		if ( $post_updated ) {
			$result = array_merge(
				$result,
				array_combine(
					array_keys( $this->batched_post_fields ),
					array_fill( 0, count( $this->batched_post_fields ), true )
				)
			);
		}

		$this->batched_post_fields = array();

		return $result;
	}
}