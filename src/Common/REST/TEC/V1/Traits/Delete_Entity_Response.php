<?php
/**
 * Trait to handle the response for delete entity requests.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Traits
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Traits;

use WP_REST_Response;

/**
 * Trait to handle the response for delete entity requests.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Traits
 */
trait Delete_Entity_Response {
	/**
	 * Deletes an existing entity.
	 *
	 * @since TBD
	 *
	 * @param array $params The sanitized parameters to use for the request.
	 *
	 * @return WP_REST_Response The response object.
	 */
	public function delete( array $params = [] ): WP_REST_Response {
		$params = $this->filter_delete_params( $params );
		$id     = $params['id'] ?? null;
		$force  = (bool) ( $params['force'] ?? false );

		if ( ! $id ) {
			return new WP_REST_Response(
				[
					'error' => __( 'The entity could not be found.', 'tribe-common' ),
				],
				404
			);
		}

		$post = get_post( $id );
		if ( ! $post ) {
			return new WP_REST_Response(
				[
					'error' => __( 'The entity could not be found.', 'tribe-common' ),
				],
				404
			);
		}

		if ( $post->post_type !== $this->get_post_type() ) {
			return new WP_REST_Response(
				[
					'error' => __( 'The entity type can not be deleted by this endpoint.', 'tribe-common' ),
				],
				400
			);
		}

		// Check if trashing is supported when not forcing.
		if ( ! $force && EMPTY_TRASH_DAYS <= 0 ) {
			return new WP_REST_Response(
				[
					'error' => __( "The entity does not support trashing. Set 'force=true' to delete.", 'tribe-common' ),
				],
				501
			);
		}

		// Handle special case: already trashed posts when not forcing.
		if ( ! $force && 'trash' === $post->post_status ) {
			/**
			 * Filters whether to convert a soft delete to a permanent delete when the post is already trashed.
			 * Note: wp_delete_post() only auto-converts trash to delete for "post" and "page" post types.
			 *
			 * @since TBD
			 *
			 * @param bool     $convert_to_permanent Whether to convert to permanent delete. Default false.
			 * @param int      $id                  The post ID being deleted.
			 * @param \WP_Post $post                The post object.
			 */
			$convert_to_permanent = apply_filters( 'tec_rest_delete_convert_trashed_to_permanent', false, $id, $post );

			if ( ! $convert_to_permanent ) {
				return new WP_REST_Response(
					[
						'error' => __( 'The entity has already been trashed.', 'tribe-common' ),
					],
					410
				);
			}

			$force = true;
		}

		// Use wp_delete_post which handles both trashing and permanent deletion.
		$result = wp_delete_post( $id, $force );

		if ( ! $result ) {
			$error_message = $force
				? __( 'The entity could not be permanently deleted.', 'tribe-common' )
				: __( 'The entity could not be trashed.', 'tribe-common' );

			return new WP_REST_Response(
				[
					'error' => $error_message,
				],
				500
			);
		}

		return new WP_REST_Response( [], 200 );
	}

	/**
	 * Filters the delete parameters.
	 *
	 * This is meant to be overridden by the endpoint to add any additional filtering.
	 *
	 * @since TBD
	 *
	 * @param array $params The parameters to filter.
	 *
	 * @return array The filtered parameters.
	 */
	protected function filter_delete_params( array $params ): array {
		return $params;
	}
}
