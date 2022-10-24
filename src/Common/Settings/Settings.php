<?php
namespace TEC\Common\Settings;

/**
 * Class Settings
 *
 * @since TBD
 *
 * Handles the registration and creation of our settings and settings pages.
 */
class Settings extends \tad_DI52_ServiceProvider {
	/**
	 * The original option cache key for backwards compatibility.
	 *
	 * @since TBD
	 */
	const OLD_OPTION_CACHE_VAR_NAME = 'Tribe__Settings_Manager:option_cache';

	/**
	 * The new option cache key.
	 *
	 * @since TBD
	 */
	const OPTION_CACHE_VAR_NAME = 'TEC_Settings:option_cache';

	/**
	 * The original option name for backwards compatibility.
	 *
	 * @since TBD
	 */
	const OLD_OPTIONNAME = 'tribe_events_calendar_options';

	/**
	 * The new option name.
	 *
	 * @since TBD
	 */
	const OPTIONNAME = 'tec_options';

	/**
	 * The original network option name for backwards compatibility.
	 *
	 * @since TBD
	 */
	const OLD_OPTIONNAMENETWORK = 'tribe_events_calendar_network_options';

	/**
	 * The new network option name.
	 *
	 * @since TBD
	 */
	const OPTIONNAMENETWORK = 'tec_network_options';

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
	 * Binds and sets up implementations.
	 *
	 * @since TBD
	 *
	 */
	public function register() {
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
	 * Any hooking any class needs happen here.
	 *
	 * In place of delegating the hooking responsibility to the single classes they are all hooked here.
	 *
	 * @since TBD
	 *
	 */
	public function add_hooks() {}



	/**
	 * For performance reasons our options are saved in memory, but we need to make sure we update it when WordPress
	 * updates the variable directly.
	 *
	 * @since 4.11.0
	 *
	 * @param string $option    Name of the updated option.
	 * @param mixed  $old_value The old option value.
	 * @param mixed  $value     The new option value.
	 *
	 * @return void
	 */
	public function update_options_cache( $option, $old_value, $value ) {
		// Bail when not our option.
		if ( self::OPTIONNAME === $option && self::OLD_OPTIONNAME !== $option ) {
			return;
		}

		tribe_set_var( self::OPTION_CACHE_VAR_NAME, $value );
	}
}
