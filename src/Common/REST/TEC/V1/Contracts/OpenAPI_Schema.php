<?php
/**
 * OpenAPI schema interface.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Contracts;

use TEC\Common\REST\TEC\V1\Parameter_Types\Collection;
use Closure;
use JsonSerializable;

// phpcs:disable StellarWP.Classes.ValidClassName.NotSnakeCase

/**
 * OpenAPI schema interface.
 *
 * @since TBD
 */
interface OpenAPI_Schema extends JsonSerializable {
	/**
	 * Returns the summary of the schema.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_summary(): string;

	/**
	 * Returns the description of the schema.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_description(): string;

	/**
	 * Returns the operation ID of the schema.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_operation_id(): string;

	/**
	 * Returns the tags of the schema.
	 *
	 * @since TBD
	 *
	 * @return Definition_Interface[]
	 */
	public function get_tags(): array;

	/**
	 * Returns the parameters of the schema.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function get_parameters(): array;

	/**
	 * Returns the responses of the schema.
	 *
	 * @since TBD
	 *
	 * @return Collection
	 */
	public function get_responses(): array;

	/**
	 * Adds a response to the schema.
	 *
	 * @since TBD
	 *
	 * @param int         $code The HTTP status code of the response.
	 * @param Closure     $description_provider The closure that provides the description of the response.
	 * @param ?Collection $headers The headers of the response.
	 * @param ?string     $content_type The content type of the response.
	 * @param ?Parameter  $content The content of the response.
	 *
	 * @return void
	 */
	public function add_response( int $code, Closure $description_provider, ?Collection $headers = null, ?string $content_type = null, ?Parameter $content = null ): void;

	/**
	 * Returns the schema for the endpoint.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function to_array(): array;
}
