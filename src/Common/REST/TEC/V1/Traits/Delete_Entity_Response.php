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

use WP_REST_Request;
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
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_REST_Response The response object.
	 */
	public function delete( WP_REST_Request $request ): WP_REST_Response {
		$id = $request['id'] ?? null;

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

		$result = $this->get_orm()->where( 'id', $id )->delete();

		if ( ! $result ) {
			return new WP_REST_Response(
				[
					'error' => __( 'The entity could not be deleted.', 'tribe-common' ),
				],
				404
			);
		}

		return new WP_REST_Response( [], 200 );
	}
}
