<?php
/**
 * Template Factory
 *
 * The parent class for managing the view methods in core and addons
 *
 */

if ( class_exists( 'Tribe__Template_Factory' ) ) {
	return;
}

class Tribe__Template_Factory {
	/**
	 * @deprecated 6.0.0
	 * @return string[] An array of registered vendor script handles.
	 */
	public static function get_vendor_scripts() {
		_deprecated_function( __METHOD__, '6.0.0' );
		return [];
	}
}