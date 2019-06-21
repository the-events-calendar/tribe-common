<?php
namespace Tribe\PUE;

use WP_Error;

/**
 * Class Rollback engine for a plugin with invalid/empty keys with
 * unmet dependencies on Core or Event Tickets.
 *
 * @package Tribe\PUE;
 */
class Rollback {

	/**
	 * Fetches the dependencies based on a regular expression search of the Plugin_Register.php
	 * file that we use to prevent problems with mismatched version on our plugins.
	 *
	 * @since  TBD
	 *
	 * @param  string $content Contents of the file in question.
	 *
	 * @return array  Named array with [ class_name => version ] or empty if it didnt find it.
	 */
	public function get_dependencies( $content ) {
		$regex = "/'(?<plugin>[^']*)'(?:[^']*)'(?<version>[^']*)',/";

		if ( ! preg_match_all( $regex, $content, $matches ) ) {
			return [];
		}

		$dependencies = array_combine( $matches['plugin'], $matches['version'] );

		return $dependencies;
	}

	/**
	 * Checks for the list of constants associate with plugin to make sure we are dealing
	 * with a plugin owned by Modern Tribe.
	 *
	 * @since  TBD
	 *
	 * @param  string $plugin Plugin file partial path, folder and main php file.
	 *
	 * @return bool
	 */
	public function is_tribe_plugin( $plugin ) {
		$path_constants_list = [
			// The Events Calendar
			'TRIBE_EVENTS_FILE',

			// Events Pro
			'EVENTS_CALENDAR_PRO_FILE',

			// Filter bar
			'TRIBE_EVENTS_FILTERBAR_FILE',

			// Eventbrite Tickets
			'EVENTBRITE_PLUGIN_FILE',
		];

		foreach ( $path_constants_list as $constant_name ) {
			if ( ! defined( $constant_name ) ) {
				continue;
			}

			if ( false === strpos( constant( $constant_name ), $plugin ) ) {
				continue;
			}

			return true;
		}

		return false;
	}

	/**
	 * Filters the source file location for the upgrade package for the PUE Rollback engine.
	 *
	 * @since  TBD
	 *
	 * @param string      $source        File source location.
	 * @param string      $remote_source Remote file source location.
	 * @param WP_Upgrader $upgrader      WP_Upgrader instance.
	 * @param array       $extra         Extra arguments passed to hooked filters.
	 */
	public function filter_upgrader_source_selection( $source, $remote_source, $upgrader, $extras ) {
		$plugin = $extras['plugin'];

		// Bail if we are not dealing with a plugin we own
		if ( ! $this->is_tribe_plugin( $plugin ) ) {
			return $source;
		}

		$register_path = $source . '/src/Tribe/Plugin_Register.php';

		if ( ! file_exists( $register_path ) ) {
			return $source;
		}

		$register_contents = file_get_contents( $register_path );

		$dependencies = $this->get_dependencies( $register_contents );
		$incompatible_plugins = [];

		foreach ( $dependencies as $class_name => $required_version ) {
			// Skip inactive plugin checks
			if ( ! class_exists( $class_name ) ) {
				continue;
			}

			$constant_name = $class_name . '::VERSION';

			// Skip if we cant find the version constant
			if ( ! defined( $constant_name ) ) {
				continue;
			}

			$current_version = constant( $constant_name );

			// Skip when the version is equal or higher than the required
			if ( version_compare( $current_version, $required_version, '>=' ) ) {
				continue;
			}

			// Flag that we should prevent the Update
			$incompatible_plugins[ $class_name ] = $required_version;
		}

		if ( empty( $incompatible_plugins ) ) {
			return $source;
		}

		$error = new WP_Error(
			'tribe-prevent-failed',
			'tribe-prevent-failed',
			[]
		);

		return $error;
	}
}