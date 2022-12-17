<?php
/**
 * Functions, or polyfills, related to Transient data manipulation.
 *
 * @since 5.0.6
 */

if ( ! function_exists( 'tec_timed_option' ) ) {
	/**
	 * Checks if a file is from one of the specified plugins.
	 *
	 * @since 5.0.6
	 *
	 * @return \TEC\Common\Storage\Timed_Option
	 */
	function tec_timed_option(): \TEC\Common\Storage\Timed_Option {
		static $timed_option;

		if ( ! isset( $timed_option ) ) {
			$timed_option = tribe( \TEC\Common\Storage\Timed_Option::class );
		}

		return $timed_option;
	}
}