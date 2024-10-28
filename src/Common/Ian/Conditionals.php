<?php
/**
 * Handles IAN Client notifications display logic.
 *
 * @since   TBD
 *
 * @package TEC\Common\Ian
 */
namespace TEC\Common\Ian;

use TEC\Common\Telemetry\Opt_In;

/**
 * Class Conditionals
 *
 * @since   TBD

 * @package TEC\Common\Ian
 */
class Conditionals {
	/**
	 * Get the current user Telemetry opt-in data.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public static function get_user_opt_in(): bool {
		$opt_in = new Opt_In();
		$user   = $opt_in->build_opt_in_user();

		if ( tribe_is_truthy( tribe_get_option( 'opt-in-status', null ) ) ) {
			return false;
		}
		return true;
	}
}
