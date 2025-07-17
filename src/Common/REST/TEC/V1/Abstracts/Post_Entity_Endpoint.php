<?php
/**
 * Endpoint class.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Abstracts
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Abstracts;

use TEC\Common\REST\TEC\V1\Contracts\Post_Entity_Endpoint_Interface;
use WP_REST_Request;
use WP_REST_Posts_Controller;
use WP_Post_Type;

/**
 * Endpoint class.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Abstracts
 */
abstract class Post_Entity_Endpoint extends Endpoint implements Post_Entity_Endpoint_Interface {
	/**
	 * The allowed statuses.
	 *
	 * @since TBD
	 *
	 * @var string[]
	 */
	public const ALLOWED_STATUS = [ 'publish', 'pending', 'draft', 'future', 'private' ];

	/**
	 * Returns whether the guest can read the object.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public function guest_can_read(): bool {
		return false;
	}

	/**
	 * Returns whether the user can read the object.
	 *
	 * @since TBD
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return bool
	 */
	public function can_read( WP_REST_Request $request ): bool {
		$id = $request['id'] ?? null;
		if ( $id ) {
			return current_user_can( $this->get_post_type_object()->cap->read_post, $id );
		}

		return $this->guest_can_read() || current_user_can( $this->get_post_type_object()->cap->read );
	}

	/**
	 * Returns whether the user can create the object.
	 *
	 * @since TBD
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return bool
	 */
	public function can_create( WP_REST_Request $request ): bool {
		return current_user_can( $this->get_post_type_object()->cap->create_posts );
	}

	/**
	 * Returns whether the user can update the object.
	 *
	 * @since TBD
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return bool
	 */
	public function can_update( WP_REST_Request $request ): bool {
		$id = $request['id'] ?? null;
		if ( ! $id ) {
			return current_user_can( $this->get_post_type_object()->cap->edit_posts );
		}

		return current_user_can( $this->get_post_type_object()->cap->edit_post, $id );
	}

	/**
	 * Returns whether the user can delete the object.
	 *
	 * @since TBD
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return bool
	 */
	public function can_delete( WP_REST_Request $request ): bool {
		$id = $request['id'] ?? null;
		if ( ! $id ) {
			return current_user_can( $this->get_post_type_object()->cap->delete_posts );
		}

		return current_user_can( $this->get_post_type_object()->cap->delete_post, $id );
	}

	/**
	 * Returns the post type object.
	 *
	 * @since TBD
	 *
	 * @return WP_Post_Type
	 */
	public function get_post_type_object(): WP_Post_Type {
		return get_post_type_object( $this->get_post_type() );
	}

	/**
	 * Formats a collection of posts into a collection of post entities.
	 *
	 * @since TBD
	 *
	 * @param array $posts The posts to format.
	 *
	 * @return array
	 */
	protected function format_post_entity_collection( array $posts ): array {
		$rest_controller = new WP_REST_Posts_Controller( $this->get_post_type() );
		$formatted_posts = [];
		foreach ( $posts as $post ) {
			if ( ! $rest_controller->check_read_permission( $post ) ) {
				continue;
			}

			$data              = $rest_controller->prepare_item_for_response( $post, new WP_REST_Request() );
			$formatted_posts[] = $rest_controller->prepare_response_for_collection( $data );
		}
		return $formatted_posts;
	}
}
