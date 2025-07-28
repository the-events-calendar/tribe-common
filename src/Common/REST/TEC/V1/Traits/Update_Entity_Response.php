<?php
/**
 * Trait to handle the response for update entity requests.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Traits
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Traits;

use WP_REST_Request;
use WP_REST_Response;

/**
 * Trait to handle the response for update entity requests.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Traits
 */
trait Update_Entity_Response {
	/**
	 * Updates an existing entity.
	 *
	 * @since TBD
	 *
	 * @param WP_REST_Request $request The request object.
	 * @param array           $params  The sanitized parameters to use for the request.
	 *
	 * @return WP_REST_Response The response object.
	 */
	public function update( WP_REST_Request $request, array $params = [] ): WP_REST_Response {
		$id = $params['id'] ?? null;

		unset( $params['id'] );

		if ( ! $id ) {
			return new WP_REST_Response(
				[
					'error' => __( 'The entity could not be updated.', 'tribe-common' ),
				],
				404
			);
		}

		if ( get_post_type( $id ) !== $this->get_post_type() ) {
			return new WP_REST_Response(
				[
					'error' => __( 'The entity could not be updated.', 'tribe-common' ),
				],
				404
			);
		}

		$entity = $this->get_orm()->by_args(
			[
				'id'     => $id,
				'status' => 'any',
			]
		)->set_args( $params )->save();

		if ( empty( $entity ) ) {
			return new WP_REST_Response(
				[
					'error' => __( 'Failed to update entity.', 'tribe-common' ),
				],
				500
			);
		}

		return new WP_REST_Response(
			$this->get_formatted_entity(
				$this->get_orm()->by_args(
					[
						'id'     => array_keys( $entity )[0],
						'status' => 'any',
					]
				)->first()
			),
			200
		);
	}
}
