<?php
/**
 * SVG template tags.
 *
 * @since 6.10.0
 *
 * @package TEC\Common\SVG
 */

use TEC\Common\SVG\SVG;

if ( ! function_exists( 'tec_svg' ) ) {
	/**
	 * Get the SVG code for a given icon.
	 *
	 * @since 6.10.0
	 *
	 * @param string $namespaced_path The namespaced path to the SVG file.
	 *
	 * @return string The SVG code for the given icon.
	 */
	function tec_svg( string $namespaced_path ): string {
		return tribe( SVG::class )->get_svg( $namespaced_path );
	}
}

if ( ! function_exists( 'tec_svg_register_namespace' ) ) {
	/**
	 * Register a namespace for a given path.
	 *
	 * @since 6.10.0
	 *
	 * @param string         $name_space The namespace to register.
	 * @param string|Closure $path       The path to the SVG files.
	 *
	 * @return void
	 */
	function tec_svg_register_namespace( string $name_space, $path ): void {
		tribe( SVG::class )->register_namespace( $name_space, $path );
	}
}
