<?php

/**
 * Class used to hook into the assets being loaded
 *
 * @since 4.7.7
 */

class Tribe__Assets_Pipeline {

	/**
	 * Filter to listen when a tag is attached to the HTML.
	 *
	 * @since 4.7.7
	 */
	public function hook() {
		add_filter( 'script_loader_tag', [ $this, 'prevent_underscore_conflict' ], 10, 2 );
		add_filter( 'script_loader_tag', [ $this, 'prevent_select2_conflict' ], 10, 2 );
	}

	/**
	 * Before underscore is loaded to the FE we add two scripts on before and one after to prevent underscore from
	 * taking place on the global namespace if lodash is present.
	 *
	 * @since 4.7.7
	 *
	 * @param string $tag    The <script> tag for the enqueued script.
	 * @param string $handle The script's registered handle.
	 *
	 * @return string The <script> tag.
	 */
	public function prevent_underscore_conflict( $tag, $handle ) {
		if ( is_admin() ) {
			return $tag;
		}

		if ( 'underscore' === $handle ) {
			$path = Tribe__Main::instance()->plugin_url;
			$tag  = "<script src='{$path}build/js/underscore-before.js'></script>\n"
				. $tag
				. "<script src='{$path}build/js/underscore-after.js'></script>\n";
		}

		return $tag;
	}

	/**
	 * After select2 is loaded to the FE we add one scripts after to prevent select2 from breaking.
	 *
	 * @since 4.13.2
	 * @since 4.14.18 Ensure we don't run this in the admin.
	 *
	 * @param string $tag    The <script> tag for the enqueued script.
	 * @param string $handle The script's registered handle.
	 *
	 * @return string The <script> tag.
	 */
	public function prevent_select2_conflict( $tag, $handle ) {
		if ( is_admin() ) {
			return $tag;
		}

		if ( 'tribe-select2' !== $handle ) {
			return $tag;
		}

		$path = Tribe__Main::instance()->plugin_url;
		$tag .= "<script src='{$path}build/js/select2-after.js'></script>\n";

		return $tag;
	}
}
