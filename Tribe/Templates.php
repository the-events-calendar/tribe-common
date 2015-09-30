<?php
/**
 * Templating functionality for common tribe
 */

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( class_exists( 'Tribe__Templates' ) ) {
	return;
}

/**
 * Handle views and template files.
 */
class Tribe__Templates {
	/**
	 * Check to see if this is operating in the main loop
	 *
	 * @param WP_Query $query
	 *
	 * @return bool
	 */
	protected static function is_main_loop( $query ) {
		return $query->is_main_query();
	}

	/**
	 * Look for the stylesheets. Fall back to $fallback path if the stylesheets can't be located or the array is empty.
	 *
	 * @param array|string $stylesheets Path to the stylesheet
	 * @param bool|string  $fallback    Path to fallback stylesheet
	 *
	 * @return bool|string Path to stylesheet
	 */
	public static function locate_stylesheet( $stylesheets, $fallback = false ) {
		if ( ! is_array( $stylesheets ) ) {
			$stylesheets = array( $stylesheets );
		}
		if ( empty( $stylesheets ) ) {
			return $fallback;
		}
		foreach ( $stylesheets as $filename ) {
			if ( file_exists( STYLESHEETPATH . '/' . $filename ) ) {
				$located = trailingslashit( get_stylesheet_directory_uri() ) . $filename;
				break;
			} else {
				if ( file_exists( TEMPLATEPATH . '/' . $filename ) ) {
					$located = trailingslashit( get_template_directory_uri() ) . $filename;
					break;
				}
			}
		}
		if ( empty( $located ) ) {
			return $fallback;
		}

		return $located;
	}

}//end class
