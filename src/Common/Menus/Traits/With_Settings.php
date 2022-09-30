<?php
/**
 * Provides methods and properties for settings.
 *
 * @since TBD
 *
 * @package TEC\Common\Menus
 */

namespace TEC\Common\Menus\Traits;

trait With_Settings {
	/**
	 * Slug of the parent menu this goes under.
	 * Required.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected $parent_slug = '';

	/**
	 * Path to the data file for the settings.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected $data_file;

	/**
	 * Hooks specific to settings pages.
	 *
	 * @since TBD
	 */
	public function settings_hooks() : void {
		$this->callback = null; // overridden by WordPressSettingsFramework

		// Include and create a new WordPressSettingsFramework
		require_once( \Tribe__Main::instance()->plugin_path . '/vendor/iconicwp/wordpress-settings-framework/wp-settings-framework.php' );

		$this->settings_page = new \WordPressSettingsFramework(
			null,
			$this->get_settings_slug()
		);

		// Add a settings validation filter
		add_filter( $this->settings_page->get_option_group() . '_settings_validate', [ $this, 'validate_settings' ] );
	}

	/**
	 * WordPressSettingsFramework adds '-settings' to the passed option group (slug)
	 * so here we check if settings is part of the slug and trim it off.
	 *
	 * @since TBD
	 */
	public function get_settings_slug() : string {
		$slug = preg_replace( '/settings/', '', $this->get_slug() );
		$slug = trim( $slug, "_- \n\r\t\v\x00");

		return $slug;
	}

	/**
	 * {@inheritDoc}
	 */
	public function is_submenu() : bool {
		$is_submenu = apply_filters( 'tec_menus_is_submenu', true, $this );

		return (bool) apply_filters( "tec_menus_{$this->get_slug()}_is_submenu", $is_submenu, $this );
	}

	/**
	 * Add settings page.
	 */
	function register_in_wp() : string {
		if ( ! empty( $this->hook_suffix ) ) {
			return $this->hook_suffix;
		}

		$this->settings_page->add_settings_page( [
			'parent_slug' => $this->get_parent_slug(),
			'page_title'  => $this->get_page_title(),
			'page_slug'   => $this->get_slug(),
			'menu_title'  => $this->get_menu_title(),
			'capability'  => $this->get_capability(),
		] );

		// WordPressSettingsFramework does not return the hook suffix, so we fake it.
		$this->hook_suffix  = $this->get_slug();

		return $this->get_hook_suffix();
	}

	/**
	 * Validate settings.
	 * This will just return the values, you'll need to overwrite this to properly validate & sanitize user inputs!
	 *
	 * @param array<string,mixed> $input
	 *
	 * @return array<string,mixed>
	 */
	function validate_settings( $input ) : array {
		// Do your settings validation here
		// Same as $sanitize_callback from http://codex.wordpress.org/Function_Reference/register_setting
		return $input;
	}

	/**
	 * Handled by WordPressSettingsFramework.
	 *
	 * @since TBD
	 */
	public function render() : void {
		return;
	}
}
