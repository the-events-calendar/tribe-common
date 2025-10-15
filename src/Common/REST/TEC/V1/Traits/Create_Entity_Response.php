<?php
/**
 * Trait to handle the response for create entity requests.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Traits
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Traits;

use WP_REST_Response;
use TEC\Common\StellarWP\SchemaModels\Contracts\SchemaModel as Model;
/**
 * Trait to handle the response for create entity requests.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Traits
 */
trait Create_Entity_Response {
	/**
	 * Creates a new entity.
	 *
	 * @since 6.9.0
	 *
	 * @param array $params The sanitized parameters to use for the request.
	 *
	 * @return WP_REST_Response The response object.
	 */
	public function create( array $params = [] ): WP_REST_Response {
		$entity = $this->get_orm()->set_args( $params )->create();

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
				$this->get_orm()->by_primary_key( $entity instanceof Model ? $entity->getPrimaryValue() : $entity->ID )
			),
			201
		);
	}
}
