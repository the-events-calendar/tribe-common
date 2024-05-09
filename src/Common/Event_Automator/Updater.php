<?php
/**
 * Handles Plugin Updates
 *
 * @since TBD Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator
 */

namespace TEC\Event_Automator;

use Tribe__Events__Updater;

/**
 * Class Updater.
 *
 * @since TBD Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator
 */
class Updater extends Tribe__Events__Updater {
	/**
	 * Event Automator reset version.
	 *
	 * @since TBD Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	protected $reset_version = '1.0';

	/**
	 * Event Automator Schema Key.
	 *
	 * @since TBD Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	protected $version_option = 'event-automator-schema-version';

	/**
	 * Force upgrade script to run even without an existing version number
	 * The version was not previously stored for Event Automator.
	 *
	 * @since TBD Migrated to Common from Event Automator
	 *
	 * @return bool
	 */
	public function is_new_install() {
		return false;
	}

	/**
	 * Run Updates on Plugin Upgrades.
	 *
	 * @since TBD Migrated to Common from Event Automator
	 */
	public function run_updates() {
		if ( ! $this->update_required() ) {
			return;
		}

		$this->do_updates();
	}
}
