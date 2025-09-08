<?php
/**
 * Trait to handle the response for delete custom entity requests.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Traits
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Traits;

use WP_REST_Response;

/**
 * Trait to handle the response for delete custom entity requests.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Traits
 */
trait Delete_Custom_Entity_Response {
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
		$id = $params['id'] ?? null;

		if ( ! $id ) {
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
				500
			);
		}

		return new WP_REST_Response( [], 200 );
	}
}
