<?php
/**
 * Trait to handle the response for update entity requests.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Traits
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Traits;

use WP_REST_Response;
use WP_REST_Posts_Controller;

/**
 * Trait to handle the response for read entity requests.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Traits
 */
trait Read_Entity_Response {
	/**
	 * Reads an existing entity.
	 *
	 * @since 6.9.0
	 *
	 * @param array $params The sanitized parameters to use for the request.
	 *
	 * @return WP_REST_Response The response object.
	 */
	public function read( array $params = [] ): WP_REST_Response {
		$id = $params['id'] ?? null;

		if ( ! $id ) {
			return new WP_REST_Response(
				[
					'error' => __( 'The entity could not be read.', 'tribe-common' ),
				],
				404
			);
		}

		if ( get_post_type( $id ) !== $this->get_post_type() ) {
			return new WP_REST_Response(
				[
					'error' => __( 'The entity could not be read.', 'tribe-common' ),
				],
				404
			);
		}

		$rest_controller = new WP_REST_Posts_Controller( $this->get_post_type() );

		$filter_added = false;

		if ( post_password_required( $id ) && $rest_controller->can_access_password_content( get_post( $id ), $this->get_request() ) ) {
			// If user can access password protected content, we remove any integration that might be obstructing the content.
			add_filter( 'post_password_required', '__return_false' );
			$filter_added = true;
		}

		$entity = $this->get_orm()->by_args(
			[
				'id'     => $id,
				'status' => 'any',
			]
		)->first();

		if ( ! $entity ) {
			return new WP_REST_Response(
				[
					'error' => __( 'The entity could not be read.', 'tribe-common' ),
				],
				404
			);
		}

		$response = new WP_REST_Response( $this->get_formatted_entity( $entity ), 200 );

		if ( $filter_added ) {
			remove_filter( 'post_password_required', '__return_false' );
		}

		return $response;
	}
}
