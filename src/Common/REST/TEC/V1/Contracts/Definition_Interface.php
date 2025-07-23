<?php
/**
 * Definition interface.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Contracts;

/**
 * Definition interface.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */
interface Definition_Interface {
	/**
	 * Returns the OpenAPI documentation for the definition.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function get_documentation(): array;

	/**
	 * Returns the type of the definition.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_type(): string;

	/**
	 * Returns the priority of the definition.
	 *
	 * @since TBD
	 *
	 * @return int
	 */
	public function get_priority(): int;

	/**
	 * Returns the example of the definition.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function get_example(): array;
}
