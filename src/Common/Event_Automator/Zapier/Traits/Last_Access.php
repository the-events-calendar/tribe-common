<?php
/**
 * Provides methods to set last access on Zapier endpoint.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Traits;
 */

namespace TEC\Event_Automator\Zapier\Traits;

use Tribe\Utils\Date_I18n;
use Tribe__Timezones as Timezones;

/**
 * Trait Last_Access
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Zapier\Traits;
 */
trait Last_Access {

	/**
	 * Get the last access with provided app name.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string $app_name The optional app name used with this API key pair.
	 */
	public function get_last_access( $app_name ) {
		$pretty_app_name = '';
		if ( $app_name === 'zapier-event-tickets' ) {
			$pretty_app_name = _x( 'Event Tickets App', 'Name of the Event Tickets Zapier app.', 'tribe-common' );
		} elseif ( $app_name === 'zapier-the-events-calendar' ) {
			$pretty_app_name = _x( 'The Events Calendar App', 'Name of the Events Calendar Zapier app.', 'tribe-common' );
		}

		/**
		 * Filters the Zapier app name.
		 *
		 * @since 6.0.0 Migrated to Common from Event Automator
		 *
		 * @param string $pretty_app_name The Zapier app name.
		 * @param string $app_name The Zapier app name id as sent from Zapier.
		 */
		$pretty_app_name = apply_filters( 'tec_event_automator_zapier_app_name', $pretty_app_name, $app_name );

		$timezone_object              = Timezones::build_timezone_object();
		$date                         = new Date_I18n( 'now', $timezone_object );

		return $pretty_app_name . '|' . $date->format( 'Y-m-d H:i:s' );
	}
}
