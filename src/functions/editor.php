<?php
/**
 * Check if the current theme is a block theme.
 *
 * @since TBD
 *
 * @return bool
 */
if ( ! function_exists( 'tec_is_full_site_editor' ) ) {
	function tec_is_full_site_editor(): bool {
		if ( function_exists( 'wp_is_block_theme' ) ) {
			return (bool) wp_is_block_theme();
		}

		if ( function_exists( 'gutenberg_is_fse_theme' ) ) {
			return (bool) gutenberg_is_fse_theme();
		}

		return false;
	}
}
