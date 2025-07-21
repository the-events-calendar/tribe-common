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
use WP_Post;
use TEC\Common\REST\TEC\V1\Contracts\Readable_Endpoint;

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
			return $this->guest_can_read() || current_user_can( $this->get_post_type_object()->cap->read_post, $id );
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

			$formatted_posts[] = $this->get_formatted_entity( $post );
		}

		return $formatted_posts;
	}

	/**
	 * Formats a post into a post entity.
	 *
	 * @since TBD
	 *
	 * @param WP_Post $post The post to format.
	 *
	 * @return array
	 */
	public function get_formatted_entity( WP_Post $post ): array {
		$rest_controller = new WP_REST_Posts_Controller( $this->get_post_type() );
		$data            = $rest_controller->prepare_item_for_response( $post, new WP_REST_Request() );

		return $this->transform_entity( $this->add_properties_to_model( $rest_controller->prepare_response_for_collection( $data ), $post ) );
	}

	/**
	 * Adds properties to the model.
	 *
	 * @since TBD
	 *
	 * @param array   $formatted_post The formatted post.
	 * @param WP_Post $original_post  The original post.
	 *
	 * @return array
	 */
	/**
	 * Adds properties to the events.
	 *
	 * @since TBD
	 *
	 * @param array   $formatted_post The formatted post.
	 * @param WP_Post $original_post  The original post.
	 *
	 * @return array The response with the properties added.
	 */
	protected function add_properties_to_model( array $formatted_post, WP_Post $original_post ): array {
		$properties_to_add = $this->get_model_class()::get_properties_to_add();

		$data = array_merge( (array) $formatted_post, array_intersect_key( (array) $original_post, $properties_to_add ) );

		$data['link'] = $data['permalink'];
		unset(
			$data['permalink'],
			$data['meta'],
		);

		// Reorder the links.
		$links = $data['_links'] ?? [];
		if ( ! empty( $links ) ) {
			unset( $data['_links'] );
			$data['_links'] = $links;
		}

		return $data;
	}

	/**
	 * Validates the status parameter.
	 *
	 * @since TBD
	 *
	 * @param mixed $value The value to validate.
	 *
	 * @return bool Whether the value is valid.
	 */
	public function validate_status( $value ): bool {
		$value = is_string( $value ) ? explode( ',', $value ) : $value;

		if ( ! is_array( $value ) ) {
			return false;
		}

		$invalid_statuses = array_diff( $value, self::ALLOWED_STATUS );
		if ( ! empty( $invalid_statuses ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Transforms the entity.
	 *
	 * @since TBD
	 *
	 * @param array $entity The entity to transform.
	 *
	 * @return array
	 */
	protected function transform_entity( array $entity ): array {
		return $entity;
	}
}
