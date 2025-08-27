<?php
/**
 * Deletable endpoint interface.
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
 * Deletable endpoint interface.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */
interface Deletable_Endpoint {
	/**
	 * Deletes the object.
	 *
	 * @since 6.9.0
	 *
	 * @param array $params The parameters to use for the request.
	 *
	 * @return WP_REST_Response
	 */
	public function delete( array $params = [] ): WP_REST_Response;

	/**
	 * Returns whether the user can delete the object.
	 *
	 * @since 6.9.0
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return bool
	 */
	public function can_delete( WP_REST_Request $request ): bool;

	/**
	 * Returns the arguments for the delete method.
	 *
	 * @since 6.9.0
	 *
	 * @return QueryArgumentCollection
	 */
	public function delete_params(): QueryArgumentCollection;

	/**
	 * Returns the schema for the delete method.
	 *
	 * @since 6.9.0
	 *
	 * @return OpenAPI_Schema
	 */
	public function delete_schema(): OpenAPI_Schema;

	/**
	 * Returns the attributes for the delete method.
	 *
	 * @since 6.9.0
	 *
	 * @return array
	 */
	public function get_delete_attributes(): array;
}
