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

use TEC\Common\Contracts\Repository_Interface;
use TEC\Common\StellarWP\SchemaModels\Contracts\SchemaModel as Model;
use WP_Post;
use WP_REST_Response;

/**
 * Trait to handle the response for create entity requests.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Traits
 *
 * @method array get_formatted_entity( Model|WP_Post $model ): array
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

		$orm = $this->get_orm();

		return new WP_REST_Response(
			$this->get_formatted_entity(
				$entity instanceof Model ? $orm->by_primary_key( $entity->getPrimaryValue() ) : $orm->by_args(
					[
						'id'     => $entity->ID,
						'status' => 'any',
					]
				)->first()
			),
			201
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
}
