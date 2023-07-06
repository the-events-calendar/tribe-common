<?php
/**
 * Interface used to provider access to a particular configuration for the Configuration_Loader.
 *
 * @since   TBD
 *
 * @package TEC\Common\Configuration;
 */

namespace TEC\Common\Configuration;

/**
 * Interface Configuration_Provider_Interface.
 *
 * @since   TBD
 *
 * @package TEC\Common\Configuration;
 */
interface Configuration_Provider_Interface {
	/**
	 * Whether a particular variable is defined or not.
	 *
	 * @since TBD
	 *
	 * @param $key string Variable name.
	 *
	 * @return bool Whether the variable is defined or not.
	 */
	public function has( string $key ): bool;

	/**
	 * Retrieves the value for the given variable.
	 *
	 * @since TBD
	 *
	 * @param $key string Variable name.
	 *
	 * @return null|mixed
	 */
	public function get( string $key );

	/**
	 * Retrieve all variables defined in an associative array.
	 *
	 * @since TBD
	 *
	 * @return array All vars.
	 */
	public function all(): array;
}