<?php
/**
 * Definition interface.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Contracts;

/**
 * Definition interface.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */
interface Definition_Interface {
	/**
	 * Returns the OpenAPI documentation for the definition.
	 *
	 * @since 6.9.0
	 *
	 * @return array
	 */
	public function get_documentation(): array;

	/**
	 * Returns the type of the definition.
	 *
	 * @since 6.9.0
	 *
	 * @return string
	 */
	public function get_type(): string;

	/**
	 * Returns the priority of the definition.
	 *
	 * @since 6.9.0
	 *
	 * @return int
	 */
	public function get_priority(): int;

	/**
	 * Returns the example of the definition.
	 *
	 * @since 6.9.0
	 *
	 * @return array
	 */
	public function get_example(): array;

	/**
	 * Returns the instance from a ref.
	 *
	 * @since 6.9.0
	 *
	 * @param string $ref The ref.
	 *
	 * @return ?Definition_Interface
	 */
	public static function get_instance_from_ref( string $ref ): ?Definition_Interface;
}
