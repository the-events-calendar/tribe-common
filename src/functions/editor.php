<?php
if ( ! function_exists( 'tec_is_full_site_editor' ) ) {
	/**
	 * Check if the current theme is a block theme.
	 *
	 * @since 4.14.18
	 *
	 * @return bool Whether the current theme is a block theme supporting full-site editing.
	 */
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

if ( ! function_exists( 'tec_using_new_editor' ) ) {
	function tec_using_new_editor(): bool {
		/**
		 * Filters whether the new editor experience is being used or not.
		 *
		 * @since TBD
		 *
		 * @param bool $using_new_editor Whether the new editor experience is being used or not.
		 */
		$using_new_editor = apply_filters( 'tec_using_new_editor', false );

		return (bool) $using_new_editor;
	}
}
