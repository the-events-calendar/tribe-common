<?php
/**
 * Endpoint interface.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Contracts;

/**
 * Endpoint interface.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */
interface Endpoint_Interface {
	/**
	 * Registers the endpoint.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function register_routes(): void;

	/**
	 * Returns the OpenAPI documentation for the endpoint.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function get_documentation(): array;

	/**
	 * Returns the schema for the endpoint.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function get_schema(): array;

	/**
	 * Returns the path of the endpoint.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_path(): string;
}
