<?php
/**
 * Updatable endpoint interface.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Contracts;

use WP_REST_Request;
use WP_REST_Response;
use TEC\Common\REST\TEC\V1\Collections\Collection;

/**
 * Updatable endpoint interface.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */
interface Updatable_Endpoint {
	/**
	 * Updates the object.
	 *
	 * @since 6.9.0
	 *
	 * @param array $params The parameters to use for the request.
	 *
	 * @return WP_REST_Response
	 */
	public function update( array $params = [] ): WP_REST_Response;

	/**
	 * Returns whether the user can update the object.
	 *
	 * @since 6.9.0
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return bool
	 */
	public function can_update( WP_REST_Request $request ): bool;

	/**
	 * Returns the arguments for the update method.
	 *
	 * @since 6.9.0
	 *
	 * @return array
	 */
	public function update_args(): Collection;

	/**
	 * Returns the schema for the update method.
	 *
	 * @since 6.9.0
	 *
	 * @return OpenAPI_Schema
	 */
	public function update_schema(): OpenAPI_Schema;

	/**
	 * Returns the attributes for the update method.
	 *
	 * @since 6.9.0
	 *
	 * @return array
	 */
	public function get_update_attributes(): array;
}
