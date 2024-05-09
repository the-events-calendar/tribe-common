<?php
/**
 * Handles Plugin Updates
 *
 * @since   1.2.0
 *
 * @package TEC\Common\Event_Automator
 */

namespace TEC\Common\Event_Automator;

use TEC\Common\Event_Automator\PUE\Helper;
use Tribe__Events__Updater;

/**
 * Class Updater.
 *
 * @since 1.5.0
 *
 * @package TEC\Common\Event_Automator
 */
class Updater extends Tribe__Events__Updater {
	/**
	 * Event Automator reset version.
	 *
	 * @since 1.2.0
	 *
	 * @var string
	 */
	protected $reset_version = '1.0';

	/**
	 * Event Automator Schema Key.
	 *
	 * @since 1.2.0
	 *
	 * @var string
	 */
	protected $version_option = 'event-automator-schema-version';

	/**
	 * Returns an array of callbacks with version strings as keys.
	 * Any key higher than the version recorded in the DB
	 * and lower than $this->current_version will have its
	 * callback called on admin_init after a plugin update.
	 *
	 * @since 1.2.0
	 *
	 * @return array<string|callback> The version number and callback to use.
	 */
	public function get_update_callbacks() {
		return [
			'1.2' => [ $this, 'update_pue_license_key' ],
		];
	}

	/**
	 * Update the PUE license field.
	 *
	 * @since 1.2.0
	 *
	 * @return boolean whether the update to pue license field is complete.
	 */
	public function update_pue_license_key() {
		$pue_license_key = get_option( 'pue_install_key_event_automator' );

		// If any value, then do nothing as the user might have saved it or the default password was added.
		if ( ! empty( $pue_license_key ) ) {
			return false;
		}

		// If empty, update the option with the customer's key.
		return update_option( 'pue_install_key_event_automator', Helper::DATA );
	}

	/**
	 * Force upgrade script to run even without an existing version number
	 * The version was not previously stored for Event Automator.
	 *
	 * @since 1.2.0
	 *
	 * @return bool
	 */
	public function is_new_install() {
		return false;
	}

	/**
	 * Run Updates on Plugin Upgrades.
	 *
	 * @since 1.2.0
	 */
	public function run_updates() {
		if ( ! $this->update_required() ) {
			return;
		}

		$this->do_updates();
	}
}
