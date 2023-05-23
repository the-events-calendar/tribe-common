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
	 * @param $key
	 *
	 * @return bool
	 */
	public function has( $key ):bool;

	public function get( $key );

	public function all():array;
}