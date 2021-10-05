<?php

namespace Tribe\Editor\Compatibility;

/**
 * Editor Compatibility with classic editor plugins.
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
	public static $classic_option = 'classic-editor-replace';

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
	public static $block_param = 'block';

	/**
	 * "Classic Editor" param for user override
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static $classic_override = 'classic-editor__forget';

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

	public function register() {
		if ( self::is_classic_plugin_active() ) {
			$this->hooks();
		}
	}

	public function hooks() {
		add_filter( 'tribe_editor_should_load_blocks', [ $this, 'filter_tribe_editor_should_load_blocks' ] );
		add_filter( 'tribe_editor_classic_is_active', [ $this, 'filter_tribe_editor_classic_is_active'], 11 );
	}

	public function filter_tribe_editor_should_load_blocks( $should_load_blocks ) {
		if ( self::is_classic_plugin_active() ) {
			return false;
		}

		if ( self::is_classic_option_active() ) {
			return false;
		}

		return $should_load_blocks;
	}

	public function filter_tribe_editor_classic_is_active( $is_active ) {
		// Plugin ins't active.
		if ( ! self::is_classic_plugin_active() ) {
			return $is_active;
		}

		// We always obey URL params.
		if ( self::is_classic_editor_request() ) {
			return true;
		}

		$profile = self::is_user_override_active();

		if ( empty( $profile ) ) {
			return self::is_classic_option_active();
		}

		return self::$classic_term === $profile;
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
	 * @since 4.8
	 *
	 * @return bool
	 */
	public static function is_classic_plugin_active() {
		$is_plugin_active = function_exists( 'classic_editor_replace' ) || class_exists( 'Classic_Editor' );
		/**
		 * Filter to change the output of calling: `is_classic_plugin_active`
		 *
		 * @since 4.9.12
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
	 *
	 * @return bool
	 */
	public static function is_classic_option_active() {
		$valid_values  = [ 'replace', 'classic' ];
		$replace       = in_array( (string) get_option( self::$classic_option ), $valid_values, true );

		return $replace;
	}

	/**
	 * Check if users are allowed to override the Classic Editor setting
	 *
	 * @since TBD
	 *
	 * @return boolean
	 */
	public static function is_user_override_allowed() {
		return 'allow' === get_option( 'classic-editor-allow-users', 'disallow' );
	}

	/**
	 * Gets teh meta value for hte usr profile setting.
	 *
	 * @since TBD
	 *
	 * @return mixed The <string> value of `wp_classic-editor-settings`.
	 *               False for an invalid `$user_id` (non-numeric, zero, or negative value).
	 *               An empty string if a valid but non-existing user ID is passed.
	 */
	public static function get_user_profile_override( $user_id = null ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		return get_user_meta( $user_id, 'wp_classic-editor-settings', true );
	}

	/**
	 * Check if user override is active for this editing session.
	 *
	 * @since TBD
	 *
	 * @return boolean|string The value of the user's profile setting, or boolean false if it's empty,
	 *                        or we should ignore it due to things that take precedence.
	 */
	public static function is_user_override_active() {
		// if we don't allow it, let's just get out of here.
		if ( ! self::is_user_override_allowed() ) {
			return false;
		}

		// If the URL param is set (via intentional link click), obey it.
		if ( self::is_classic_editor_request() ) {
			return false;
		}

		$profile  = self::get_user_profile_override();

		// The user hasn't saved a profile choice.
		if ( empty( $profile ) ) {
			return false;
		}


		return $profile;

	}

	public static function is_classic_editor_request() {
		// Need to check for function in cases where this gets called early.
		if ( function_exists( 'tribe_get_request_var' ) ) {
			return tribe_get_request_var( self::$classic_param, null );
		} else {
			return isset( $_GET[ self::$classic_param ] ) ? $_GET[ self::$classic_param ] : false;
		}
	}
}
