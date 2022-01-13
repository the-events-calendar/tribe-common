<?php

namespace Tribe\Editor\Compatibility;
/**
 * Editor Compatibility with Divi theme's builder option.
 *
 * @since TBD
 */
class Divi {
	public static $classic_key = 'et_enable_classic_editor';

	public static $classic_on = 'on';

	public static $classic_off = 'off';

	/**
	 * Registers the hooks and filters required based on if the Classic Editor plugin is active.
	 *
	 * @since TBD
	 */
	public function init() {
		if ( static::is_divi_active() ) {
			$this->hooks();
		}
	}

	/**
	 * Hooks for loading logic outside this class.
	 *
	 * @since TBD
	 */
	public function hooks() {
		global $current_screen;
		$screen = empty( $current_screen ) ? $current_screen : get_current_screen();

		$good_screens = [
			'post-new.php',
			'post.php',
		];

		//if ( in_array( $current_screen, $good_screens ) ) {
			add_filter( 'tribe_editor_should_load_blocks', [ $this, 'filter_tribe_editor_should_load_blocks' ], 20 );
		//}
	}

	public static function is_divi_active() {
		$theme = wp_get_theme(); // gets the current theme
		return 'Divi' == $theme->name || 'Divi' == $theme->template || 'Divi' == $theme->parent_theme;
	}

	/**
	 * Filters tribe_editor_should_load_blocks based on internal logic.
	 *
	 * @since TBD
	 *
	 * @param boolean $should_load_blocks Whether we should force blocks over classic.
	 *
	 * @return boolean Whether we should force blocks or classic.
	 */
	public function filter_tribe_editor_should_load_blocks( $should_load_blocks ) {
		$boo = apply_filters( 'et_builder_enable_classic_editor', $should_load_blocks );

		if ( function_exists( 'et_builder_enable_classic_editor' ) ) {
			$baz = (
				function_exists('et_get_option' )
				&& static::$classic_on === et_get_option( static::$classic_key, static::$classic_off )
			);

			return ! $baz;
		}

		return $should_load_blocks;
	}
}
