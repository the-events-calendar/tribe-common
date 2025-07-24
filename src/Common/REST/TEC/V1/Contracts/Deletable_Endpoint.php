<?php
/**
 * Deletable endpoint interface.
 *
 * @since TBD
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
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */
interface Deletable_Endpoint {
	/**
	 * Deletes the object.
	 *
	 * @since TBD
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_REST_Response
	 */
	public function delete( WP_REST_Request $request ): WP_REST_Response;

	/**
	 * Returns whether the user can delete the object.
	 *
	 * @since TBD
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return bool
	 */
	public function can_delete( WP_REST_Request $request ): bool;

	/**
	 * Returns the arguments for the delete method.
	 *
	 * @since TBD
	 *
	 * @return QueryArgumentCollection
	 */
	public function delete_args(): QueryArgumentCollection;

	/**
	 * Returns the schema for the delete method.
	 *
	 * @since TBD
	 *
	 * @return OpenAPI_Schema
	 */
	public function delete_schema(): OpenAPI_Schema;

	/**
	 * Returns the attributes for the delete method.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function get_delete_attributes(): array;
}
