<?php
/**
 * Handles IAN Client notifications display logic.
 *
 * @since   TBD
 *
 * @package TEC\Common\Ian
 */

namespace TEC\Common\Ian;

use TEC\Common\StellarWP\Telemetry\Config;
use TEC\Common\StellarWP\Telemetry\Opt_In\Status;
use TEC\Common\Telemetry\Telemetry as Common_Telemetry;

/**
 * Class Conditionals
 *
 * @since   TBD

 * @package TEC\Common\Ian
 */
class Conditionals {
	/**
	 * Get the current user status based on Telemetry and IAN opt-in.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public static function get_ian_opt_in(): bool {
		// Trigger this before we try use the Telemetry value.
		tribe( Common_Telemetry::class )->normalize_optin_status();

		// We don't care what the value stored in tribe_options is - give us Telemetry's Opt_In\Status value.
		$status = Config::get_container()->get( Status::class );
		$value  = $status->get() === $status::STATUS_ACTIVE;

		// First check if the user has opted in to telemetry.
		if ( tribe_is_truthy( $value ) ) {
			return true;
		}

		// If Telemetry is off, return the IAN opt-in value.
		return tribe_is_truthy( tribe_get_option( 'ian-client-opt-in', false ) );
	}
}
