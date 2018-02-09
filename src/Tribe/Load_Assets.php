<?php
/**
 * Class used to hook into the assets being loaded
 *
 * @since TBD
 */
class Tribe__Load_Assets {

	/**
	 * Filter to listen when a tag is attached to the HTML.
	 *
	 * @since TBD
	 */
	public function hook() {
		add_filter( 'script_loader_tag', array( $this, 'prevent_underscore_conflict' ), 10, 2 );
	}

	/**
	 * Before underscore is loaded to the FE we add two scripts on before and one after to prevent underscore from
	 * taking place on the global namespace if lodash is present.
	 *
	 * @since TBD
	 *
	 * @param string $tag The <script> tag for the enqueued script.
	 * @param string $handle The script's registered handle.
	 * @return string The <script> tag.
	 */
	public function prevent_underscore_conflict( $tag, $handle ) {
		if ( is_admin() || ! defined( 'TRIBE_COMMON_PARENT_PLUGIN_URL' ) ) {
			return $tag;
		}

		if ( 'underscore' === $handle ) {
			$dir = TRIBE_COMMON_PARENT_PLUGIN_URL . 'src/resources/js';
			$tag = "<script type='text/javascript' src='{$dir}/underscore-before.js'></script>\n"
				. $tag
				. "<script type='text/Javascript' src='{$dir}/underscore-after.js'></script>\n";
		}
		return $tag;
	}
}
