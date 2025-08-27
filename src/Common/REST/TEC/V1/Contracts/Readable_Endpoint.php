<?php
/**
 * Readable endpoint interface.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Contracts;

use WP_REST_Request;
use WP_REST_Response;
use TEC\Common\REST\TEC\V1\Collections\QueryArgumentCollection;

/**
 * Readable endpoint interface.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */
interface Readable_Endpoint {
	/**
	 * Returns the response for the endpoint.
	 *
	 * @since 6.9.0
	 *
	 * @param array $params The parameters to use for the request.
	 *
	 * @return WP_REST_Response
	 */
	public function read( array $params = [] ): WP_REST_Response;

	/**
	 * Returns whether the endpoint can be read.
	 *
	 * @since 6.9.0
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return bool
	 */
	public function can_read( WP_REST_Request $request ): bool;

	/**
	 * Returns the arguments for the read method.
	 *
	 * @since 6.9.0
	 *
	 * @return QueryArgumentCollection
	 */
	public function read_params(): QueryArgumentCollection;

	/**
	 * Returns the schema for the endpoint.
	 *
	 * @since 6.9.0
	 *
	 * @return OpenAPI_Schema
	 */
	public function read_schema(): OpenAPI_Schema;

	/**
	 * Returns the attributes for the read method.
	 *
	 * @since 6.9.0
	 *
	 * @return array
	 */
	public function get_read_attributes(): array;
}
