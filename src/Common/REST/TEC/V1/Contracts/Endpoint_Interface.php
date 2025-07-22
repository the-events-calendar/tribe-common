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
	 * @link https://github.com/OAI/OpenAPI-Specification/blob/main/versions/3.0.4.md
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

	/**
	 * Returns the base path of the endpoint.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_base_path(): string;

	/**
	 * Returns the URL of the endpoint.
	 *
	 * @since TBD
	 *
	 * @param mixed ...$args The arguments to pass to the URL.
	 *
	 * @return string
	 */
	public function get_url( ...$args ): string;

	/**
	 * Returns the path parameters of the endpoint.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function get_path_parameters(): array;

	/**
	 * Returns the OpenAPI path of the endpoint.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_open_api_path(): string;
}
