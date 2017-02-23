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
	protected $blocked_post_fields = [
		'ID',
		'post_name',
		'post_modified',
		'post_modified_gmt',
		'post_parent',
		'guid',
		'comment_count',
	];

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
		list( $from, $to ) = $this->cast_to_post( $from, $to );

		if ( in_array( $field, $this->blocked_post_fields ) ) {
			return false;
		}

		if ( ! $this->is_post_field( $field ) ) {
			return $this->propagate_meta_field( $from, $to, $field );
		}

		return $this->propagate_post_field( $from, $to, $field );
	}

	/**
	 * @param $from
	 * @param $to
	 *
	 * @return array
	 */
	protected function cast_to_post( $from, $to ) {
		$from = get_post( $from );
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
		list( $from, $to ) = $this->cast_to_post( $from, $to );

		return parent::propagate( $from, $to );
	}

	/**
	 * Propagates all the values of a post meta field from the source to the destination.
	 *
	 * @param WP_Post        $from
	 * @param WP_Post        $to
	 * @param         string $field
	 *
	 * @return bool Whether the custom field was propagated or not.
	 */
	protected function propagate_meta_field( WP_Post $from, WP_Post $to, $field ) {
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
	 * @param WP_Post        $from
	 * @param WP_Post        $to
	 * @param         string $field
	 *
	 * @return bool Whether the field was propagated or not.
	 */
	protected function propagate_post_field( WP_Post $from, WP_Post $to, $field ) {
		return (bool) wp_update_post( array( 'ID' => $to->ID, $field => $from->{$field} ) );
	}
}