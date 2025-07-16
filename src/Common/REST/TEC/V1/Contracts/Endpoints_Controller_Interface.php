<?php
/**
 * Endpoints Controller interface.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Contracts;

/**
 * Endpoints Controller interface.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */
interface Endpoints_Controller_Interface {
	/**
	 * Returns the endpoints to register.
	 *
	 * @since TBD
	 *
	 * @return Endpoint_Interface[]
	 */
	public function get_endpoints(): array;

	/**
	 * Returns the definitions to register.
	 *
	 * @since TBD
	 *
	 * @return Definition_Interface[]
	 */
	public function get_definitions(): array;

	/**
	 * Registers the endpoints.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function register_endpoints(): void;
}
