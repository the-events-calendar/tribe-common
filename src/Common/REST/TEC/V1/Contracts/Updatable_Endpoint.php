<?php
/**
 * Updatable endpoint interface.
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
 * Updatable endpoint interface.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */
interface Updatable_Endpoint {
	/**
	 * Updates the object.
	 *
	 * @since TBD
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_REST_Response
	 */
	public function update( WP_REST_Request $request ): WP_REST_Response;

	/**
	 * Returns whether the user can update the object.
	 *
	 * @since TBD
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return bool
	 */
	public function can_update( WP_REST_Request $request ): bool;

	/**
	 * Returns the arguments for the update method.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function update_args(): array;
}
