<?php
/**
 * Provides methods and properties for submenus.
 *
 * @since   TBD
 *
 * @package TEC\Common\Menus
 */

namespace TEC\Common\Menus\Traits;

trait With_Settings {

	/**
	 * path to the settings file.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public $settings_file = '';

	/**
	 * Constructor
	 *
	 * @since TBD
	 *
	 * @param array<string,mixed> $data The data for creating the settings page.
	 */
	public function init( array $data ) {
		// Include WordPressSettingsFramework
		require_once \Tribe__Main::instance()->plugin_path . 'vendor/iconicwp/wordpress-settings-framework/wp-settings-framework.php';

		// Dynamically set all the params from data.
		$keys = array_keys( get_object_vars( $this ) );

		foreach( $keys as $key ) {
			if ( isset( $data[$key] ) ) {
				$this->$key = $data[$key];
			}
		}

		// Key change.
		if ( isset( $keys[ 'hook_suffix' ] ) ) {
			$this->option_group = $keys[ 'hook_suffix' ];
		}

	}

	/**
	 * Actually build the page.
	 *
	 * @since TBD
	 */
	public function add_settings_page() {
		$this->page = new \WordPressSettingsFramework( $this->settings_file, $this->parent_slug );

		$args = [
			'parent_slug' => $this->get_parent_slug(),
			'page_title'  => $this->get_page_title(),
			'menu_title'  => $this->get_title(),
			'capability'  => $this->get_capability(),
		];

		$this->page->add_settings_page( $args );
	}
}
