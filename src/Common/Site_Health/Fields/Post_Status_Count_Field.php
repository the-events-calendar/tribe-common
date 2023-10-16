<?php

namespace TEC\Common\Site_Health\Fields;

use TEC\Common\Site_Health\Info_Field_Abstract;

/**
 * Class Post_Status_Count_Field
 *
 * @since   5.1.0
 *
 * @package TEC\Common\Site_Health
 */
class Post_Status_Count_Field extends Generic_Info_Field {

	/**
	 * Stores the post type for the field.
	 *
	 * @since 5.1.0
	 *
	 * @var string
	 */
	protected string $post_type;

	/**
	 * Configure all the params for a generic field.
	 *
	 * @param string                           $id
	 * @param string                           $label
	 * @param array<string,string>|string|null $value
	 * @param int                              $priority
	 */
	public function __construct( string $id, string $post_type = null, int $priority = 50 ) {
		$this->id         = $id;
		$this->post_type  = $post_type;
		$this->priority   = $priority;
		$this->is_private = true;
		$this->debug      = false;
	}

	/**
	 * @inheritDoc
	 */
	public function get_label(): string {
		$post_type_obj = get_post_type_object( $this->post_type );
		$name = $post_type_obj->label;

		if ( ! empty( $post_type_obj->labels->singular_name ) ) {
			$name = $post_type_obj->labels->singular_name;
		}

		return sprintf(
			/* Translators: %1$s the post type label. */
			esc_html__( '%1$s counts', 'tribe-common' ),
			$name
		);
	}

	/**
	 * @inheritDoc
	 */
	public function get_value() {
		return $this->get_counts();
	}

	/**
	 * Converts a post status count object to an array in the format
	 *            [ (string) status_slug => (int) count]
	 *
	 * @since 5.1.0
	 *
	 * @param stdClass $obj The object returned from wp_count_posts().
	 *
	 * @return array<string,int> An array of stati (key) with counts (value).
	 */
	protected function get_counts(): array {
		$counts = (array) wp_count_posts( $this->post_type );
		$stati  = [
			'publish',
			'future',
			'draft',
			'pending',
		];

		/**
		 * Allows other plugins to add/remove stati to track.
		 *
		 * @param array<string|bool> $stati An array of stati to track.
		 * @param self $field The field instance.
		 */
		$stati = apply_filters( 'tec_site_heath_event_stati', $stati, $this );

		$keys = array_keys( $counts );
		foreach( $keys as $key ) {
			if ( ! in_array( $key, $stati ) ) {
				unset( $counts[ $key ] );
			}
		}

		return $counts;
	}
}
