<?php
/**
 * Check if the current theme is a block theme.
 *
 * @since TBD
 *
 * @return bool Whether the current theme is a block theme supporting full-site editing.
 */
if ( ! function_exists( 'tec_is_full_site_editor' ) ) {
	function tec_is_full_site_editor() {
		if ( function_exists( 'wp_is_block_theme' ) ) {
			return (bool) wp_is_block_theme();
		} else if ( function_exists( 'gutenberg_is_fse_theme' ) ) {
			// This function has returned wp_is_block_theme since 2021/12 so this is just in case someone hasn't updated.
			return (bool) gutenberg_is_fse_theme();
		}

		return false;
	}
}
