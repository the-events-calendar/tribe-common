<?php
/**
 * Creatable endpoint interface.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Contracts;

use WP_REST_Request;
use WP_REST_Response;
use TEC\Common\REST\TEC\V1\Collections\RequestBodyCollection;

/**
 * Creatable endpoint interface.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */
interface Creatable_Endpoint {
	/**
	 * Creates the object.
	 *
	 * @since 6.9.0
	 *
	 * @param array $params The parameters to use for the request.
	 *
	 * @return WP_REST_Response
	 */
	public function create( array $params = [] ): WP_REST_Response;

	/**
	 * Returns whether the user can create the object.
	 *
	 * @since 6.9.0
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return bool
	 */
	public function can_create( WP_REST_Request $request ): bool;

	/**
	 * Returns the arguments for the create method.
	 *
	 * @since 6.9.0
	 * @since 6.10.0 Returning a RequestBodyCollection instead of a QueryArgumentCollection
	 *
	 * @return RequestBodyCollection
	 */
	public function create_params(): RequestBodyCollection;

	/**
	 * Returns the schema for the create method.
	 *
	 * @since 6.9.0
	 *
	 * @return OpenAPI_Schema
	 */
	public function create_schema(): OpenAPI_Schema;

	/**
	 * Returns the attributes for the create method.
	 *
	 * @since 6.9.0
	 *
	 * @return array
	 */
	public function get_create_attributes(): array;
}
