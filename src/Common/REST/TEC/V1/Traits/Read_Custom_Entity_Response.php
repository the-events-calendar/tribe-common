<?php
/**
 * Trait to handle the response for read custom entity requests.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Traits
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Traits;

use WP_REST_Response;

/**
 * Trait to handle the response for read custom entity requests.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Traits
 */
trait Read_Custom_Entity_Response {
	/**
	 * Reads an existing entity.
	 *
	 * @since TBD
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

		$entity = $this->get_orm()->by_primary_key( $id );

		if ( ! $entity ) {
			return new WP_REST_Response(
				[
					'error' => __( 'The entity could not be read.', 'tribe-common' ),
				],
				404
			);
		}

		return new WP_REST_Response( $this->get_formatted_entity( $entity ), 200 );
	}
}
