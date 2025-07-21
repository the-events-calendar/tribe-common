<?php
/**
 * Readable endpoint interface.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Contracts;

use WP_REST_Request;
use WP_REST_Response;
use TEC\Common\REST\TEC\V1\Parameter_Types\Collection;

/**
 * Readable endpoint interface.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */
interface Readable_Endpoint {
	/**
	 * Returns the response for the endpoint.
	 *
	 * @since TBD
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_REST_Response
	 */
	public function read( WP_REST_Request $request ): WP_REST_Response;

	/**
	 * Returns whether the endpoint can be read.
	 *
	 * @since TBD
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return bool
	 */
	public function can_read( WP_REST_Request $request ): bool;

	/**
	 * Returns the arguments for the read method.
	 *
	 * @since TBD
	 *
	 * @return Collection
	 */
	public function read_args(): Collection;

	/**
	 * Returns the schema for the endpoint.
	 *
	 * @since TBD
	 *
	 * @return OpenAPI_Schema
	 */
	public function read_schema(): OpenAPI_Schema;
}
