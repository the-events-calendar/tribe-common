<?php
/**
 * Trait to handle the response for delete entity requests.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Traits
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Traits;

use WP_REST_Response;

/**
 * Trait to handle the response for delete entity requests.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Traits
 */
trait Delete_Entity_Response {
	/**
	 * Deletes an existing entity.
	 *
	 * @since 6.9.0
	 *
	 * @param array $params The sanitized parameters to use for the request.
	 *
	 * @return WP_REST_Response The response object.
	 */
	public function delete( array $params = [] ): WP_REST_Response {
		$id    = $params['id'] ?? null;
		$force = (bool) ( $params['force'] ?? false );

		if ( ! $id ) {
			return new WP_REST_Response(
				[
					'error' => __( 'The entity could not be deleted.', 'tribe-common' ),
				],
				404
			);
		}

		if ( get_post_type( $id ) !== $this->get_post_type() ) {
			return new WP_REST_Response(
				[
					'error' => __( 'The entity could not be deleted.', 'tribe-common' ),
				],
				404
			);
		}

		// Get the post to check its current status.
		$post = get_post( $id );
		if ( ! $post ) {
			return new WP_REST_Response(
				[
					'error' => __( 'The entity could not be found.', 'tribe-common' ),
				],
				404
			);
		}

		// If we're forcing, then delete permanently using WordPress function.
		if ( $force ) {
			// Force delete bypasses all status checks.
			$result = wp_delete_post( $id, true );

			if ( ! $result ) {
				return new WP_REST_Response(
					[
						'error' => __( 'Force deletion failed - the entity could not be permanently deleted.', 'tribe-common' ),
					],
					500
				);
			}

			return new WP_REST_Response( [], 200 );
		}

		// Check if the post supports trashing (when not forcing).
		$supports_trash = ( EMPTY_TRASH_DAYS > 0 );

		// If we don't support trashing, error out (like WordPress core does).
		if ( ! $supports_trash ) {
			return new WP_REST_Response(
				[
					'error' => __( "The entity does not support trashing. Set 'force=true' to delete.", 'tribe-common' ),
				],
				501
			);
		}

		// Check if already trashed.
		if ( 'trash' === $post->post_status ) {
			/**
			 * Filters whether to convert a soft delete to a permanent delete when the post is already trashed.
			 *
			 * @since 6.9.0
			 *
			 * @param bool     $convert_to_permanent Whether to convert to permanent delete. Default false.
			 * @param int      $id                  The post ID being deleted.
			 * @param \WP_Post $post                The post object.
			 */
			$convert_to_permanent = apply_filters( 'tec_rest_delete_convert_trashed_to_permanent', false, $id, $post );

			if ( $convert_to_permanent ) {
				// Convert soft delete to permanent delete for already trashed posts.
				$result = wp_delete_post( $id, true );

				if ( ! $result ) {
					return new WP_REST_Response(
						[
							'error' => __( 'The entity could not be permanently deleted.', 'tribe-common' ),
						],
						500
					);
				}

				return new WP_REST_Response( [], 200 );
			}

			return new WP_REST_Response(
				[
					'error' => __( 'The entity has already been trashed.', 'tribe-common' ),
				],
				410
			);
		}

		// Use WordPress trash function for soft delete.
		$result = wp_trash_post( $id );

		if ( ! $result ) {
			return new WP_REST_Response(
				[
					'error' => __( 'The entity could not be trashed.', 'tribe-common' ),
				],
				500
			);
		}

		return new WP_REST_Response( [], 200 );
	}

	/**
	 * Returns the post type for the endpoint.
	 *
	 * @since 6.10.0
	 *
	 * @return string
	 */
	abstract public function get_post_type(): string;
}
