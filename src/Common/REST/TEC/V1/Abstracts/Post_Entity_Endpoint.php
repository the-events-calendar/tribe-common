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
use TEC\Common\REST\TEC\V1\Collections\QueryArgumentCollection;
use TEC\Common\REST\TEC\V1\Parameter_Types\Boolean;
use WP_REST_Request;
use WP_REST_Posts_Controller;
use WP_Post_Type;
use WP_Post;

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

		// If requesting a specific post, validate status/visibility/password access.
		if ( $id ) {
			$post = get_post( (int) $id );
			if ( ! $post || $post->post_type !== $this->get_post_type() ) {
				return false;
			}

			$endpoint_allowed = $this->guest_can_read() || current_user_can( $this->get_post_type_object()->cap->read_post, (int) $id );
			if ( ! $endpoint_allowed ) {
				return false;
			}

			return $this->is_post_readable_by_request( $post, $request );
		}

		// Collection/list requests: allow if endpoint is publicly readable or user has capability.
		return $this->guest_can_read() || current_user_can( $this->get_post_type_object()->cap->read );
	}

	/**
	 * Checks if a post is readable for the given request, taking into account status and password.
	 *
	 * @since TBD
	 *
	 * @param WP_Post         $post    The post to check.
	 * @param WP_REST_Request $request The current request.
	 *
	 * @return bool Whether the post is readable.
	 */
	protected function is_post_readable_by_request( WP_Post $post, WP_REST_Request $request ): bool {
		$rest_controller = new WP_REST_Posts_Controller( $this->get_post_type() );

		// Status/visibility (publish, private, inherit, etc.).
		if ( ! $rest_controller->check_read_permission( $post ) ) {
			return false;
		}

		// Password-protected posts require valid password (or edit permission in edit context).
		if ( ! empty( $post->post_password ) && ! $rest_controller->can_access_password_content( $post, $request ) ) {
			return false;
		}

		return true;
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
	 * Returns the arguments for the delete request.
	 *
	 * @since TBD
	 *
	 * @return QueryArgumentCollection
	 */
	public function delete_args(): QueryArgumentCollection {
		$collection = new QueryArgumentCollection();

		$collection[] = new Boolean(
			'force',
			fn() => __( 'Whether to bypass Trash and force deletion.', 'tribe-common' ),
			false,
			false
		);

		return $collection;
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
		$request         = $this->get_request();
		$data            = $rest_controller->prepare_item_for_response( $post, $request );

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

		$data['link'] = $data['permalink'] ?? $data['link'];
		unset(
			$data['permalink'],
			$data['meta'],
			$data['_links']
		);

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
		$post_type = $this->get_post_type();

		/**
		 * Filter to allow modification of the entity data globally for all post types.
		 *
		 * @since TBD
		 *
		 * @param array  $entity    The entity data.
		 * @param string $post_type The post type being transformed.
		 * @param Post_Entity_Endpoint $this The endpoint instance.
		 */
		$entity = apply_filters( 'tec_rest_v1_post_entity_transform', $entity, $post_type, $this );

		/**
		 * Filter to allow modification of the entity data for a specific post type.
		 * The dynamic portion of the hook name, `$post_type`, refers to the post type slug.
		 *
		 * @since TBD
		 *
		 * @param array $entity The entity data.
		 * @param Post_Entity_Endpoint $this The endpoint instance.
		 */
		$entity = apply_filters( "tec_rest_v1_{$post_type}_transform_entity", $entity, $this );

		return $entity;
	}
}
