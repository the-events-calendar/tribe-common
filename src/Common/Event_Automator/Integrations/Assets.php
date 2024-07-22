<?php
/**
 * Class to manage integrations assets.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Integrations
 */

namespace TEC\Event_Automator\Integrations;

/**
 * Class Settings
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Integrations
 */
class Assets {

	/**
	 * Registers and Enqueues the admin assets.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 */
	public function register_admin_assets() {
		tribe_asset(
			\Tribe__Main::instance(),
			'tec-event-automator-js',
			'tec-event-automator.js',
			[ 'jquery', 'tribe-dropdowns', 'tribe-clipboard' ],
			'admin_enqueue_scripts',
			[
				'conditionals' => [
					[ \Tribe__Admin__Helpers::instance(), 'is_screen' ],
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
