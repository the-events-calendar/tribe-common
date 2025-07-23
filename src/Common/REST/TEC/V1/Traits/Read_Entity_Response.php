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
 * Trait to handle the response for read entity requests.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Traits
 */
trait Read_Entity_Response {
	/**
	 * Reads an existing entity.
	 *
	 * @since TBD
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_REST_Response The response object.
	 */
	public function read( WP_REST_Request $request ): WP_REST_Response {
		$id = $request['id'] ?? null;

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

		$entity = $this->get_orm()->where( 'id', $id )->first();
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
