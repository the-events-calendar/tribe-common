<?php

namespace Tribe\Editor\Compatibility;
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
	public static $user_meta_choice_key = 'classic-editor-settings';

	/**
	 * Post meta key used for CE "remembering" the last editor used.
	 * The bane of my existence.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static $post_meta_key = 'classic-editor-remember';

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
	 * Placeholders
	 *
	 * @since TBD
	 *
	 * @var [type]
	 */
	public static $user_choice_allowed = null;
	public static $user_profile_choice = null;
	public static $classic_url_param = null;
	public static $classic_url_override = null;

	/**
	 * Registers the hooks and filters required based on if the Classic Editor plugin is active.
	 *
	 * @since TBD
	 */
	public function init() {
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
		add_action( 'tribe_plugins_loaded', [ $this, 'set_classic_url_params' ], 22 );

		add_filter( 'tribe_editor_should_load_blocks', [ $this, 'filter_tribe_editor_should_load_blocks' ], 20 );
	}

	/**
	 * Sets the placeholders for the URL params.
	 *
	 * @since TBD
	 */
	public function set_classic_url_params() {
		static::$classic_url_param    = self::get_classic_param();
		static::$classic_url_override = self::get_classic_override();
	}

	/**
	 * Gets the $classic_url_param placeholder if it's set.
	 * Sets it then returns it if it's not yet set.
	 *
	 * @since TBD
	 *
	 * @return boolean
	 */
	public static function get_classic_param () {
		if ( null !== static::$classic_url_param ) {
			return static::$classic_url_param;
		}

		static::$classic_url_param = isset( $_GET[  static::$classic_param ] ) || isset( $_POST[  static::$classic_param ] );

		return static::$classic_url_param;
	}

	/**
	 * Gets the $classic_url_override placeholder if it's set.
	 * Sets it then returns it if it's not yet set.
	 *
	 * @since TBD
	 *
	 * @return boolean
	 */
	public static function get_classic_override() {
		if ( null !== static::$classic_url_override ) {
			return static::$classic_url_override;
		}

		static::$classic_url_override = isset( $_GET[ static::$classic_override ] ) || isset( $_POST[ static::$classic_override ] );

		return static::$classic_url_override;
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
		if ( ! static::is_classic_plugin_active() ) {
			return (boolean) $should_load_blocks;
		}

		if ( self::is_classic_option_active() ) {
			$should_load_blocks = false;
		}

		if ( ! static::get_user_choice_allowed() ) {
			return $should_load_blocks;
		}

		$remember = self::classic_editor_remembers();

		if ( false !== $remember ) {
			$should_load_blocks = static::$block_term === $remember;
		}

		if ( self::get_classic_override() ) {
			$should_load_blocks = true;
		}

		if ( self::get_classic_param() ) {
			$should_load_blocks = false;
		}

		global $pagenow;

		// The profile setting only applies to new posts/etc so bail out now if we're not in the admin and creating a new event.
		if ( ! is_admin() || ! in_array( $pagenow, array( 'post-new.php' ) ) ) {
			return $should_load_blocks;
		}

		$profile_choice = self::user_profile_choice();

		// Only override via $profile_choice if it is actually set.
		if ( empty( $profile_choice ) ) {
			return $should_load_blocks;
		}

		// Only override via $profile_choice if it contains an expected value.
		if ( static::$block_term === $profile_choice ) {
			$should_load_blocks = true;
		} else if ( static::$classic_term === $profile_choice ) {
			$should_load_blocks = false;
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
		return (boolean) apply_filters( 'tribe_is_classic_editor_plugin_active', $is_plugin_active );
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
		if ( ! static::is_classic_plugin_active() ) {
			return false;
		}

		$valid_values  = [ 'replace', 'classic' ];
		$replace       = in_array( (string) get_option( static::$classic_option_key ), $valid_values, true );

		return (boolean) $replace;
	}


	public static function get_user_choice_allowed() {
		if ( null !== static::$user_choice_allowed ) {
			return static::$user_choice_allowed;
		}

		static::$user_choice_allowed = 'allow' === get_option( static::$user_choice_key, 'disallow' );

		return static::$user_choice_allowed;
	}

	public static function user_profile_choice() {
		if ( null !== static::$user_profile_choice ) {
			return static::$user_profile_choice;
		}

		global $wpdb;

		$user    = get_current_user_id();
		static::$user_profile_choice = get_user_option( $wpdb->prefix . static::$user_meta_choice_key, $user );

		return static::$user_profile_choice;
	}

	public static function classic_editor_remembers() {
		if ( ! is_admin() ) {
			return false;
		}

		$id = isset(  $_GET[ 'post' ] ) ? (int) $_GET[ 'post' ] : null;

		$remember = get_post_meta( $id, static::$post_meta_key, true );

		if ( empty( $remember ) ) {
			return false;
		}

		// Why WP, why did you use a different term here?
		return str_replace( '-editor', '', $remember );
	}
}
