<?php
/**
 * Common Updater.
 *
 * @since 5.6.1.1
 *
 * @package TEC\Common;
 */

namespace TEC\Common;

use Tribe__Updater;

/**
 * Class Tribe__Tickets_Plus__Updater
 *
 * @since 5.6.1.1
 *
 */
class Updater extends Tribe__Updater {

	protected $version_option = 'event-tickets-plus-schema-version';


	/**
	 * Returns an array of callbacks with version strings as keys.
	 * Any key higher than the version recorded in the DB
	 * and lower than $this->current_version will have its
	 * callback called.
	 *
	 * @since 5.6.1.1
	 *
	 * @return array
	 */
	public function get_update_callbacks() {
		return [
			'6.5.1.1' => [ $this, 'reset_pue_notices' ],
		];
	}

	/**
	 * Resets the `tribe_pue_key_notices` option.
	 * 
	 * @since 6.5.1.1
	 */
	public function reset_pue_notices(): void {
        error_log('reset_pue_notices');
		delete_option( 'tribe_pue_key_notices' );
	}
}