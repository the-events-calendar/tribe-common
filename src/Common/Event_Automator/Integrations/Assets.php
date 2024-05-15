<?php
/**
 * Class to manage integrations assets.
 *
 * @since TBD Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Integrations
 */

namespace TEC\Event_Automator\Integrations;

use TEC\Event_Automator\Plugin;

/**
 * Class Settings
 *
 * @since TBD Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Integrations
 */
class Assets {

	/**
	 * Registers and Enqueues the admin assets.
	 *
	 * @since TBD Migrated to Common from Event Automator
	 */
	public function register_admin_assets() {
		$admin_helpers = \Tribe__Admin__Helpers::instance();
		$plugin = tribe( Plugin::class );

		tribe_asset(
			$plugin,
			'tec-event-automator-css',
			'tec-event-automator.css',
			[ 'tribe-tooltip' ],
			'admin_enqueue_scripts',
			[
				'conditionals' => [
					[ $admin_helpers, 'is_screen' ],
				],
			]
		);

		tribe_asset(
			$plugin,
			'tec-event-automator-js',
			'tec-event-automator.js',
			[ 'jquery', 'tribe-dropdowns', 'tribe-clipboard', 'tribe-tooltip-js' ],
			'admin_enqueue_scripts',
			[
				'conditionals' => [
					[ $admin_helpers, 'is_screen' ],
				],
				'localize' => [
					'name' => 'tec_automator',
					'data' => [
						'clipboard_btn_text'         => _x( 'Copy', 'Copy to api key clipboard button text.', 'tribe-common' ),
						'clipboard_copied_text'      => _x( 'Copied', 'Copy api key to clipboard success message', 'tribe-common' ),
						'clipboard_fail_text'        => _x( 'Press "Cmd + C" to copy', 'Copy api key to clipboard instructions', 'tribe-common' ),
					],
				],
			]
		);
	}
}
