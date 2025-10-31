<?php
/**
 * Tag interface for the TEC REST API.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Contracts;

use JsonSerializable;

/**
 * Tag interface for the TEC REST API.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */
interface Tag_Interface extends JsonSerializable {
	/**
	 * Returns the name of the tag.
	 *
	 * @since 6.9.0
	 *
	 * @return string
	 */
	public function get_name(): string;

	/**
	 * Returns the tag.
	 *
	 * @since 6.9.0
	 *
	 * @link https://github.com/OAI/OpenAPI-Specification/blob/main/versions/3.0.4.md#tag-object
	 *
	 * @return array
	 */
	public function get(): array;

	/**
	 * Returns the priority of the tag.
	 *
	 * @since 6.9.0
	 *
	 * @return int
	 */
	public function get_priority(): int;
}
