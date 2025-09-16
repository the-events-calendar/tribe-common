<?php
/**
 * Endpoint interface.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Contracts;

use WP_REST_Request;
use TEC\Common\REST\TEC\V1\Contracts\Tag_Interface as Tag;
use TEC\Common\REST\TEC\V1\Collections\PathArgumentCollection;
use RuntimeException;
use InvalidArgumentException;

/**
 * Endpoint interface.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */
interface Endpoint_Interface {
	/**
	 * Registers the endpoint.
	 *
	 * @since 6.9.0
	 *
	 * @return void
	 */
	public function register_routes(): void;

	/**
	 * Returns the OpenAPI documentation for the endpoint.
	 *
	 * @since 6.9.0
	 *
	 * @link https://github.com/OAI/OpenAPI-Specification/blob/main/versions/3.0.4.md
	 *
	 * @return array
	 */
	public function get_documentation(): array;

	/**
	 * Returns the schema for the endpoint.
	 *
	 * @since 6.9.0
	 *
	 * @return array
	 */
	public function get_schema(): array;

	/**
	 * Returns the path of the endpoint.
	 *
	 * @since 6.9.0
	 *
	 * @return string
	 *
	 * @throws RuntimeException If the path parameter is invalid.
	 */
	public function get_path(): string;

	/**
	 * Returns the base path of the endpoint.
	 *
	 * @since 6.9.0
	 *
	 * @return string
	 */
	public function get_base_path(): string;

	/**
	 * Returns the URL of the endpoint.
	 *
	 * @since 6.9.0
	 *
	 * @param mixed ...$args The arguments to pass to the URL.
	 *
	 * @return string
	 */
	public function get_url( ...$args ): string;

	/**
	 * Returns the path parameters of the endpoint.
	 *
	 * @since 6.9.0
	 *
	 * @return PathArgumentCollection
	 */
	public function get_path_parameters(): PathArgumentCollection;

	/**
	 * Returns the OpenAPI path of the endpoint.
	 *
	 * @since 6.9.0
	 *
	 * @return string
	 */
	public function get_open_api_path(): string;

	/**
	 * Returns the request object for the endpoint.
	 *
	 * @since 6.9.0
	 *
	 * @return WP_REST_Request
	 */
	public function get_request(): WP_REST_Request;

	/**
	 * Returns the tags for the endpoint.
	 *
	 * @since 6.9.0
	 *
	 * @return Tag[]
	 */
	public function get_tags(): array;

	/**
	 * Returns the operation ID for the endpoint.
	 *
	 * @since 6.9.0
	 *
	 * @param string $operation The operation to get the operation ID for.
	 *
	 * @return string
	 *
	 * @throws InvalidArgumentException If the operation is invalid.
	 */
	public function get_operation_id( string $operation ): string;

	/**
	 * Returns whether the endpoint is experimental.
	 *
	 * @since 6.9.0
	 *
	 * @return bool
	 */
	public function is_experimental(): bool;
}
