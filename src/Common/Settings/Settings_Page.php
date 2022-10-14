<?php
/**
 *
 */

namespace TEC\Common\Settings;

/**
 * Settings_Page
 *
 * Manages the pages for all plugin settings.
 *
 * @since TBD
 */
class Settings_Page {
	const OPTION_CACHE_VAR_NAME = 'TEC_Settings_Manager:option_cache';

	/**
	 * Holds options specific to a network install.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	protected static $network_options;

	/**
	 * Holds defaults for network installs.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	public static $tribe_events_mu_defaults;

	/**
	 * constructor
	 */
	public function __construct() {
		$this->add_hooks();

		// Load multisite defaults.
		if ( is_multisite() ) {
			$tribe_events_mu_defaults = [];
			$file                     = WP_CONTENT_DIR . '/tribe-events-mu-defaults.php';

			if ( file_exists( $file ) ) {
				require_once $file;
			}

			self::$tribe_events_mu_defaults = apply_filters( 'tribe_events_mu_defaults', $tribe_events_mu_defaults );
		}
	}

	/**
	 * Undocumented function
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function add_hooks() {

	}

}
