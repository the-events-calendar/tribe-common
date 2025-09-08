<?php
/**
 * Endpoint class.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Abstracts
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Abstracts;

use TEC\Common\REST\TEC\V1\Contracts\Custom_Entity_Endpoint_Interface;
use TEC\Common\REST\TEC\V1\Collections\QueryArgumentCollection;
use WP_REST_Request;
use TEC\Common\Contracts\Model;

/**
 * Custom Entity Endpoint class.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Abstracts
 */
abstract class Custom_Entity_Endpoint extends Endpoint implements Custom_Entity_Endpoint_Interface {
	/**
	 * Returns whether the guest can read the object.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public function guest_can_read(): bool {
		return false;
	}

	/**
	 * Returns the minimum capability required to access the endpoint.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_minimum_capability(): string {
		return 'manage_options';
	}

	/**
	 * Returns whether the user can read the object.
	 *
	 * @since TBD
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return bool
	 */
	public function can_read( WP_REST_Request $request ): bool {
		return $this->guest_can_read() || current_user_can( $this->get_minimum_capability() );
	}

	/**
	 * Returns whether the user can create the object.
	 *
	 * @since TBD
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return bool
	 */
	public function can_create( WP_REST_Request $request ): bool {
		return current_user_can( $this->get_minimum_capability() );
	}

	/**
	 * Returns whether the user can update the object.
	 *
	 * @since TBD
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return bool
	 */
	public function can_update( WP_REST_Request $request ): bool {
		return current_user_can( $this->get_minimum_capability() );
	}

	/**
	 * Returns whether the user can delete the object.
	 *
	 * @since TBD
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return bool
	 */
	public function can_delete( WP_REST_Request $request ): bool {
		return current_user_can( $this->get_minimum_capability() );
	}

	/**
	 * Returns the arguments for the delete request.
	 *
	 * @since TBD
	 *
	 * @return QueryArgumentCollection
	 */
	public function delete_params(): QueryArgumentCollection {
		return new QueryArgumentCollection();
	}

	/**
	 * Formats a collection of posts into a collection of post entities.
	 *
	 * @since TBD
	 *
	 * @param array $models The models to format.
	 *
	 * @return array
	 */
	protected function format_entity_collection( array $models ): array {
		$formatted_models = [];

		foreach ( $models as $model ) {
			$formatted_models[] = $this->get_formatted_entity( $model );
		}

		return $formatted_models;
	}

	/**
	 * Formats a post into a post entity.
	 *
	 * @since TBD
	 *
	 * @param Model $model The model to format.
	 *
	 * @return array
	 */
	public function get_formatted_entity( Model $model ): array {
		return $model->to_array();
	}
}
