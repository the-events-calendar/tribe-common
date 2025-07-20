<?php
/**
 * Tag interface for the TEC REST API.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Contracts;

use JsonSerializable;

/**
 * Tag interface for the TEC REST API.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */
interface Tag_Interface extends JsonSerializable {
	/**
	 * Returns the name of the tag.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_name(): string;

	/**
	 * Returns the tag.
	 *
	 * @since TBD
	 *
	 * @link https://github.com/OAI/OpenAPI-Specification/blob/main/versions/3.0.4.md#tag-object
	 *
	 * @return array
	 */
	public function get(): array;
}
