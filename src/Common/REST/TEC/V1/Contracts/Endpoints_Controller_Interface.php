<?php
/**
 * Endpoints Controller interface.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Contracts;

/**
 * Endpoints Controller interface.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */
interface Endpoints_Controller_Interface {
	/**
	 * Returns the endpoints to register.
	 *
	 * @since 6.9.0
	 *
	 * @return Endpoint_Interface[]
	 */
	public function get_endpoints(): array;

	/**
	 * Returns the definitions to register.
	 *
	 * @since 6.9.0
	 *
	 * @return Definition_Interface[]
	 */
	public function get_definitions(): array;

	/**
	 * Returns the tags to register.
	 *
	 * @since 6.9.0
	 *
	 * @return Tag_Interface[]
	 */
	public function get_tags(): array;

	/**
	 * Registers the endpoints.
	 *
	 * @since 6.9.0
	 *
	 * @return void
	 */
	public function register_endpoints(): void;
}
