<?php
/**
 * Creatable endpoint interface.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Contracts;

use WP_REST_Request;
use WP_REST_Response;

/**
 * Creatable endpoint interface.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */
interface Creatable_Endpoint {
	/**
	 * Creates the object.
	 *
	 * @since TBD
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_REST_Response
	 */
	public function create( WP_REST_Request $request ): WP_REST_Response;

	/**
	 * Returns whether the user can create the object.
	 *
	 * @since TBD
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return bool
	 */
	public function can_create( WP_REST_Request $request ): bool;

	/**
	 * Returns the arguments for the create method.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function create_args(): array;
}
