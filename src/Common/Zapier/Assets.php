<?php
/**
 * Class to manage Zapier assets.
 *
 * @since   TBD
 *
 * @package TEC\Common\Zapier
 */

namespace TEC\Common\Zapier;

use Tribe__Main as Common;

/**
 * Class Settings
 *
 * @since   TBD
 *
 * @package TEC\Common\Zapier
 */
class Assets {

	/**
	 * Registers and Enqueues the admin assets.
	 *
	 * @since TBD
	 */
	public function register_admin_assets() {
		// Integration Settings Script.
		tribe_asset(
			Common::instance(),
			'tec-settings-integrations',
			'tec-settings-integrations.js',
			[ 'jquery', 'tribe-dropdowns' ],
			'admin_enqueue_scripts',
			[]
		);
	}
}
