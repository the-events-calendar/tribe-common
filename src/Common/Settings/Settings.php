<?php

namespace TEC\Common\Settings;

/**
 * Class Settings
 *
 * Handles all the getting and setting of plugin settings/options.
 *
 * Note "setting(s)" and "option(s)" are synonymous and used interchangeably throughout.
 *
 * @since TBD
 */
class Settings {
	const OPTION_CACHE_VAR_NAME = 'TEC\Common\Settings\Manager:option_cache';

	/**
	 * Holds the multisite defaults.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	public static $tec_mu_defaults = [];

	/**
	 * Holds any network options.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	protected static $network_options = [];

	public static function instance() {}

	public function init() {}

	/**
	 * For performance reasons our options are saved in memory, but we need to make sure we update it when WordPress
	 * updates the variable directly.
	 *
	 * @since TBD
	 *
	 * @param string $option    Name of the updated option.
	 * @param mixed  $old_value The old option value.
	 * @param mixed  $value     The new option value.
	 *
	 * @return void
	 */
	public function update_options_cache( $option, $old_value, $value ) {
		// Bail when not our option.
		if ( \Tribe__Main::OPTIONNAME !== $option ) {
			return;
		}

		tribe_set_var( self::OPTION_CACHE_VAR_NAME, $value );
	}

	/**
	 * Get all options for the Events Calendar
	 *
	 * @return array of options
	 */
	public static function get_options() {
		$options = tribe_get_var( self::OPTION_CACHE_VAR_NAME, [] );

		if ( empty( $options ) ) {
			$options = (array) get_option( \Tribe__Main::OPTIONNAME, [] );

			tribe_set_var( self::OPTION_CACHE_VAR_NAME, $options );
 		}

		return $options;
	}

	/**
	 * Get value for a specific option
	 *
	 * @param string $option_name name of option
	 * @param string $default     default value
	 *
	 * @return mixed results of option query
	 */
	public static function get_option( $option_name, $default = '' ) {
		if ( ! $option_name ) {
			return null;
		}
		$options = static::get_options();

		$option = $default;
		if ( array_key_exists( $option_name, $options ) ) {
			$option = $options[ $option_name ];
		} elseif ( is_multisite() && isset( self::$tec_mu_defaults ) && is_array( self::$tec_mu_defaults ) && in_array( $option_name, array_keys( self::$tec_mu_defaults ) ) ) {
			$option = self::$tec_mu_defaults[ $option_name ];
		}

		// FOr backwards compatibility.
		$option = apply_filters( 'tribe_get_single_option', $option, $default, $option_name );

		return apply_filters( 'tec_get_single_option', $option, $default, $option_name );
	}

	/**
	 * Saves the options for the plugin
	 *
	 * @param array $options formatted the same as from get_options()
	 * @param bool  $apply_filters
	 *
	 * @return bool
	 */
	public static function set_options( $options, $apply_filters = true ) {
		if ( ! is_array( $options ) ) {
			return false;
		}
		if ( true === $apply_filters ) {
			$options = apply_filters( 'tec-save-options', $options );
		}
		$updated = update_option( \Tribe__Main::OPTIONNAME, $options );

		if ( $updated ) {
			tribe_set_var( self::OPTION_CACHE_VAR_NAME, $options );
		}

		return $updated;
	}

	/**
	 * Set an option
	 *
	 * @param string $name The option key or 'name'.
	 * @param mixed  $value The value we want to set.
	 *
	 * @return bool
	 */
	public static function set_option( $name, $value ) {
		$options          = self::get_options();
		$options[ $name ] = $value;

		return static::set_options( $options );
	}

	/**
	 * Remove an option. Actually remove (unset), as opposed to setting to null/empty string/etc.
	 *
	 * @since TBD
	 *
	 * @param string $name The option key or 'name'.
	 *
	 * @return bool
	 */
	public static function remove_option( $name ) {
		$options          = self::get_options();
		unset( $options[ $name ] );

		return static::set_options( $options );
	}

	/**
	 * Get all network options for the Events Calendar
	 *
	 * @return array of options
	 * @TODO add force option, implement in setNetworkOptions
	 */
	public static function get_network_options() {
		if ( ! isset( self::$network_options ) ) {
			$options               = get_site_option( \Tribe__Main::OPTIONNAMENETWORK, [] );
			self::$network_options = apply_filters( 'tribe_get_network_options', $options );
		}

		return self::$network_options;
	}

	/**
	 * Get value for a specific network option
	 *
	 * @param string $option_name name of option
	 * @param string $default    default value
	 *
	 * @return mixed results of option query
	 */
	public static function get_network_option( $option_name, $default = '' ) {
		if ( ! $option_name ) {
			return null;
		}

		if ( ! isset( self::$network_options ) ) {
			self::get_network_options();
		}

		if ( isset( self::$network_options[ $option_name ] ) ) {
			$option = self::$network_options[ $option_name ];
		} else {
			$option = $default;
		}

		return apply_filters( 'tribe_get_single_network_option', $option, $default );
	}

	/**
	 * Saves the network options for the plugin
	 *
	 * @param array $options formatted the same as from get_options()
	 * @param bool  $apply_filters
	 *
	 * @return void
	 */
	public static function set_network_options( $options, $apply_filters = true ) {
		if ( ! is_array( $options ) ) {
			return;
		}

		if (
			isset( $_POST['tribeSaveSettings'] )
			&& isset( $_POST['current-settings-tab'] )
		) {
			$options['hideSettingsTabs'] = tribe_get_request_var( 'hideSettingsTabs', [] );
		}

		$admin_pages = tribe( 'admin.pages' );
		$admin_page  = $admin_pages->get_current_page();

		if ( true === $apply_filters ) {
			$options = apply_filters( 'tec-save-network-options', $options, $admin_page );
		}

		if ( update_site_option( \Tribe__Main::OPTIONNAMENETWORK, $options ) ) {
			self::$network_options = apply_filters( 'tribe_get_network_options', $options );
		} else {
			self::$network_options = self::get_network_options();
		}
	}
}
