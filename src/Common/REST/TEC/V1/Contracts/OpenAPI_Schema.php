<?php
/**
 * OpenAPI schema interface.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Contracts;

use TEC\Common\REST\TEC\V1\Collections\HeadersCollection;
use TEC\Common\REST\TEC\V1\Collections\RequestBodyCollection;
use Closure;
use JsonSerializable;

// phpcs:disable StellarWP.Classes.ValidClassName.NotSnakeCase

/**
 * OpenAPI schema interface.
 *
 * @since 6.9.0
 */
interface OpenAPI_Schema extends JsonSerializable {
	/**
	 * Returns the summary of the schema.
	 *
	 * @since 6.9.0
	 *
	 * @return string
	 */
	public function get_summary(): string;

	/**
	 * Returns the description of the schema.
	 *
	 * @since 6.9.0
	 *
	 * @return string
	 */
	public function get_description(): string;

	/**
	 * Returns the operation ID of the schema.
	 *
	 * @since 6.9.0
	 *
	 * @return string
	 */
	public function get_operation_id(): string;

	/**
	 * Returns the tags of the schema.
	 *
	 * @since 6.9.0
	 *
	 * @return Definition_Interface[]
	 */
	public function get_tags(): array;

	/**
	 * Returns the parameters of the schema.
	 *
	 * @since 6.9.0
	 *
	 * @return array
	 */
	public function get_parameters(): array;

	/**
	 * Returns the request body of the schema.
	 *
	 * @since 6.9.0
	 *
	 * @return ?RequestBodyCollection
	 */
	public function get_request_body(): ?RequestBodyCollection;

	/**
	 * Returns the responses of the schema.
	 *
	 * @since 6.9.0
	 *
	 * @return array
	 */
	public function get_responses(): array;

	/**
	 * Adds a response to the schema.
	 *
	 * @since 6.9.0
	 *
	 * @param int                $code The HTTP status code of the response.
	 * @param Closure            $description_provider The closure that provides the description of the response.
	 * @param ?HeadersCollection $headers The headers of the response.
	 * @param ?string            $content_type The content type of the response.
	 * @param ?Parameter         $content The content of the response.
	 *
	 * @return void
	 */
	public function add_response( int $code, Closure $description_provider, ?HeadersCollection $headers = null, ?string $content_type = null, ?Parameter $content = null ): void;

	/**
	 * Returns the schema for the endpoint.
	 *
	 * @since 6.9.0
	 *
	 * @return array
	 */
	public function to_array(): array;

	/**
	 * Filters the schema.
	 *
	 * @since 6.10.0
	 *
	 * @param array $data The data to filter.
	 *
	 * @return array The filtered schema.
	 */
	public function filter_before_request( array $data = [] ): array;
}
