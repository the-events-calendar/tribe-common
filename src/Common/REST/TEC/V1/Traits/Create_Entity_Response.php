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
	 * @param array $params The sanitized parameters to use for the request.
	 *
	 * @return WP_REST_Response The response object.
	 */
	public function create( array $params = [] ): WP_REST_Response {
		$params = $this->filter_create_params( $params );
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

	/**
	 * Filters the create parameters.
	 *
	 * @since TBD
	 *
	 * @param array $params The parameters to filter.
	 *
	 * @return array The filtered parameters.
	 */
	protected function filter_create_params( array $params ): array {
		return $params;
	}
}
