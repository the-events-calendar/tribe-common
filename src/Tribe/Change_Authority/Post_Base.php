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
		if ( is_object( $from ) && ! $from instanceof WP_Post ) {
			$from = (array) $from;
		}

		$from_post = get_post( $from );
		if ( null !== $from_post ) {
			$from = $from_post;
		} else {
			$from = is_array( $from ) || is_object( $from ) ? (object) $from : null;
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

		return parent::propagate( $from, $to );
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
		$from_meta = get_post_meta( $from->ID, $field );
		delete_post_meta( $to->ID, $field );

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
	 *
	 * @return bool Whether the field was propagated or not.
	 */
	protected function propagate_post_field( $from, WP_Post $to, $field ) {
		return (bool) wp_update_post( array( 'ID' => $to->ID, $field => $from->$field ) );
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
		$from_terms = wp_get_object_terms( $from->ID, $field, array( 'fields' => 'ids' ) );

		$set = wp_set_object_terms( $to->ID, $from_terms, $field, false );

		return is_wp_error( $set ) ? false : $set;
	}
}