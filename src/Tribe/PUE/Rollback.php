<?php
namespace Tribe\PUE;

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

		$dependencies = array_combine( $matches['plugin'], $matches['plugin'] );

		return $dependencies;
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
		$register_path = $source . '/src/Tribe/Plugin_Register.php';

		if ( ! file_exists( $register_path ) ) {
			return $source;
		}

		$register_contents = file_get_contents( $register_path );

		$dependencies = $this->get_dependencies( $register_contents );

		return $source;
	}
}