<?php
/**
 * Trait to handle the response for create entity requests.
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
 * Trait to handle the response for create entity requests.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Traits
 */
trait Create_Entity_Response {
	/**
	 * Creates a new entity.
	 *
	 * @since TBD
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_REST_Response The response object.
	 */
	public function create( WP_REST_Request $request ): WP_REST_Response {
		$entity = $this->get_orm()->set_args( $request->get_body_params() )->create();

		if ( ! $entity ) {
			return new WP_REST_Response(
				[
					'error' => __( 'Failed to create entity.', 'tribe-common' ),
				],
				500
			);
		}

		return new WP_REST_Response(
			$this->get_formatted_entity(
				$this->get_orm()->by_args(
					[
						'id'     => $entity->ID,
						'status' => 'any',
					]
				)->first()
			),
			201
		);
	}
}
