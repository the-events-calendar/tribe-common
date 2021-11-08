<?php

namespace Tribe\Editor\Compatibility;

use \Classic_Editor as Plugin_Editor;
/**
 * Editor Compatibility with classic editor plugins.
 *
 * https://dev.tec/wp-admin/post.php?post=796&action=edit&classic-editor
 * https://dev.tec/wp-admin/post.php?post=796&action=edit&classic-editor__forget
 * https://dev.tec/wp-admin/post.php?post=796&action=edit&classic-editor&classic-editor__forget
 *
 * @since TBD
 */
class Classic_Editor {
	/**
	 * "Classic Editor" flag for blocks/classic
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static $classic_option_key = 'classic-editor-replace';

	/**
	 * "Classic Editor" original param for blocks->classic.
	 * Can be overridden by user choice.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static $classic_param = 'classic-editor';

	/**
	 * "Classic Editor" term used for comparisons.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static $classic_term = 'classic';

	/**
	 * "Blocks Editor" term used for comparisons.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static $block_term = 'block';

	/**
	 * "Classic Editor" param for user override
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static $classic_override = 'classic-editor__forget';

	/**
	 * "User Choice" key for user override
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static $user_choice_key = 'classic-editor-allow-users';

	/**
	 * User meta "User Choice" key for user override
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static $user_meta_choice_key = 'wp_classic-editor-settings';

	/**
	 * Stores the values used by the Classic Editor plugin to indicate we're using the classic editor.
	 *
	 * @since TBD
	 *
	 * @var array<string>
	 */
	public static $classic_values  = [
		'replace',
		'classic',
	];

	/**
	 * Registers the hooks and filters required based on if the Classic Editor plugin is active.
	 *
	 * @since TBD
	 */
	public function register() {
		if ( static::is_classic_plugin_active() ) {
			$this->hooks();
		}
	}

	/**
	 * Hooks for loading logic outside this class.
	 *
	 * @since TBD
	 */
	public function hooks() {
		add_filter( 'tribe_editor_should_load_blocks', [ $this, 'filter_tribe_editor_should_load_blocks' ] );
	}

	/**
	 * Filters tribe_editor_should_load_blocks based on internal logic.
	 *
	 * @since TBD
	 *
	 * @param boolean $should_load_blocks Whether we should force blocks over classic.
	 *
	 * @return boolean Whether we should force blocks over classic.
	 */
	public function filter_tribe_editor_should_load_blocks( $should_load_blocks ) {
		global $post;

		if ( ! static::is_classic_plugin_active() ) {
			return $should_load_blocks;
		}

		$blocks = Plugin_Editor::choose_editor( $should_load_blocks, $post );

		if ( $blocks ) {
			return true;
		}

		return $should_load_blocks;
	}

	/**
	 * classic_editor_replace is function that is created by the plugin:
	 * used in ECP recurrence and TEC Meta
	 *
	 * @see https://wordpress.org/plugins/classic-editor/
	 *
	 * prior 1.3 version the Classic Editor plugin was bundle inside of a unique function:
	 * `classic_editor_replace` now all is bundled inside of a class `Classic_Editor`
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public static function is_classic_plugin_active() {
		$is_plugin_active = function_exists( 'classic_editor_replace' ) || class_exists( 'Classic_Editor', false );
		/**
		 * Filter to change the output of calling: `is_classic_plugin_active`
		 *
		 * @since 4.9.12
		 * @since TBD moved to separate class.
		 *
		 * @param $is_plugin_active bool Value that indicates if the plugin is active or not.
		 */
		return apply_filters( 'tribe_is_classic_editor_plugin_active', $is_plugin_active );
	}

	/**
	 * Check if the setting `classic-editor-replace` is set to `replace` that option means to
	 * replace the gutenberg editor with the Classic Editor.
	 *
	 * Prior to 1.3 on Classic Editor plugin the value to identify if is on classic the value
	 * was `replace`, now the value is `classic`
	 *
	 * @since 4.8
	 * @since TBD moved to separate class.
	 *
	 * @return bool
	 */
	public static function is_classic_option_active() {
		$valid_values  = [ 'replace', 'classic' ];
		$replace       = in_array( (string) get_option( static::$classic_option_key ), $valid_values, true );

		return $replace;
	}
}
