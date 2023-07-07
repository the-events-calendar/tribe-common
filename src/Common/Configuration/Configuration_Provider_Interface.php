<?php
/**
 * Interface used to provider access to a particular configuration for the Configuration_Loader.
 *
 * @since 5.1.3
 *
 * @package TEC\Common\Configuration;
 */

namespace TEC\Common\Configuration;

/**
 * Interface Configuration_Provider_Interface.
 *
 * @since 5.1.3
 *
 * @package TEC\Common\Configuration;
 */
interface Configuration_Provider_Interface {
	/**
	 * Whether a particular variable is defined or not.
	 *
	 * @since 5.1.3
	 *
	 * @param $key string Variable name.
	 *
	 * @return bool Whether the variable is defined or not.
	 */
	public function has( string $key ): bool;

	/**
	 * Retrieves the value for the given variable.
	 *
	 * @since 5.1.3
	 *
	 * @param $key string Variable name.
	 *
	 * @return null|mixed
	 */
	public function get( string $key );

	/**
	 * Retrieve all variables defined in an associative array.
	 *
	 * @since 5.1.3
	 *
	 * @return array All vars.
	 */
	public function all(): array;
}
