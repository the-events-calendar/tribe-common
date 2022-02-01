<?php

namespace Tribe\Editor\Compatibility;

use WP_Post;

/**
 * Editor Compatibility with Divi theme's builder option.
 *
 * @since TBD
 */
class Divi {
	/**
	 * The key for the Divi classic editor.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static $classic_key = 'et_enable_classic_editor';

	/**
	 * The value for enabling the Divi classic editor.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static $classic_on = 'on';

	/**
	 * The value for disabling the Divi classic editor.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static $classic_off = 'off';

	/**
	 * Registers the hooks and filters required based on if the Classic Editor plugin is active.
	 *
	 * @since TBD
	 */
	public function init() {
		if ( ! static::is_divi_active() ) {
			return;
		}

		$this->hooks();
	}

	/**
	 * Hooks for loading logic outside this class.
	 *
	 * @since TBD
	 */
	public function hooks() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX  ) {
			return;
		}

		if ( defined( 'DOING_CRON' ) && DOING_CRON ) {
			return;
		}

		// This happens too early for most plugin/theme tests. Let's try and bail when we're not needed.
		$path = basename( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) );
		$new = false !== stripos( $path, 'post-new' );

		if ( $new ) {
			// Are we creating a new event?
			$post_type = filter_input( INPUT_GET, 'post_type', FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE );
			if ( \Tribe__Events__Main::POSTTYPE !== $post_type ) {
				return;
			}
		} else {
			// Are we editing an event?
			if ( false === stripos( $path, 'post' ) ) {
				return;
			}

			$action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE );
			if ( 'edit' !== $action ) {
				return;
			}

			$post_id = filter_input( INPUT_GET, 'post', FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE );
			$event = get_post( $post_id );

			if (! $event instanceof WP_Post || \Tribe__Events__Main::POSTTYPE !== $event->post_type ) {
				return;
			}
		}

		add_filter( 'tribe_editor_should_load_blocks', [ $this, 'filter_tribe_editor_should_load_blocks' ], 20 );
	}

	public static function is_divi_active() {
		/** @var Tribe__Cache $cache */
		$cache = tribe( 'cache' );

		$divi = $cache->get( 'is_divi' );

		if ( false !== $divi ) {
			// Stored as an int - convert to a boolean.
			return tribe_is_truthy( $divi );
		}

		// OK, do it the hard way.
		$theme = wp_get_theme();
		// Handle theme children and variations.
		$divi = 'Divi' == $theme->name || 'Divi' == $theme->template || 'Divi' == $theme->parent_theme;

		// Cache to save us this work next time.
		$cache->set( 'is_divi', (int) $divi );

		// Stored as an int - convert to a boolean.
		return tribe_is_truthy( $divi );
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
		// et_enable_classic_editor
		$divi_option = get_option( 'et_divi', [] );

		if ( empty( $divi_option[ static::$classic_key ] ) ) {
			return $should_load_blocks;
		} else if ( static::$classic_on === $divi_option[ static::$classic_key ] ) {
			return false;
		}

		return $should_load_blocks;
	}
}
