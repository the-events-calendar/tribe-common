<?php
/**
 * Trait to handle the response for update custom entity requests.
 *
 * @since 6.10.0
 *
 * @package TEC\Common\REST\TEC\V1\Traits
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Traits;

use TEC\Common\Contracts\Repository_Interface;
use TEC\Common\StellarWP\SchemaModels\Contracts\SchemaModel as Model;
use WP_REST_Response;

/**
 * Trait to handle the response for update custom entity requests.
 *
 * @since 6.10.0
 *
 * @package TEC\Common\REST\TEC\V1\Traits
 */
trait Update_Custom_Entity_Response {
	/**
	 * Updates an existing entity.
	 *
	 * @since 6.10.0
	 *
	 * @param array $params The sanitized parameters to use for the request.
	 *
	 * @return WP_REST_Response The response object.
	 */
	public function update( array $params = [] ): WP_REST_Response {
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

		$save_result = $this->get_orm()->by_args( [ 'id' => $id ] )->set_args( $params )->save();

		// Check if the save operation succeeded by verifying the result.
		if ( ! $save_result ) {
			return new WP_REST_Response(
				[
					'error' => __( 'Failed to update entity.', 'tribe-common' ),
				],
				500
			);
		}

		$updated_entity = $this->get_orm()->by_primary_key( $id );

		// Verify the entity exists after update.
		if ( ! $updated_entity ) {
			return new WP_REST_Response(
				[
					'error' => __( 'Entity not found after update.', 'tribe-common' ),
				],
				500
			);
		}

		return new WP_REST_Response(
			$this->get_formatted_entity( $updated_entity ),
			200
		);
	}

	/**
	 * Returns the ORM for the endpoint.
	 *
	 * @since 6.10.0
	 *
	 * @return Repository_Interface
	 */
	abstract public function get_orm(): Repository_Interface;

	/**
	 * Formats a model into a model entity.
	 *
	 * @since 6.10.0
	 *
	 * @param Model $model The model to format.
	 *
	 * @return array
	 */
	abstract public function get_formatted_entity( Model $model ): array;
}
