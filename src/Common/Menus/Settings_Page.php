<?php
/**
 * The base, abstract, class modeling a settings page and settings.
 *
 * @since TBD
 *
 * @package TEC\Common\Menus
 */


namespace TEC\Common\Menus;

/**
 * Class Menu
 *
 * @since TBD
 *
 * @package TEC\Common\Menus
 */
class Settings_Page {
	public $capability;
	public $menu_title;
	public $option_group;
	public $page_title;
	public $page;
	public $parent_slug;
	public $settings_file;
	public $page_slug;

	/**
	 * Constructor
	 *
	 * @since TBD
	 *
	 * @param array<string,mixed> $data The data for creating the settings page.
	 */
	public function __construct( array $data ) {
		// Include WordPressSettingsFramework
		require_once \Tribe__Main::instance()->plugin_path . 'vendor/iconicwp/wordpress-settings-framework/wp-settings-framework.php';

		// Dynamically set all the params from data.
		$keys = array_keys( get_object_vars( $this ) );

		foreach( $keys as $key ) {
			if ( isset( $data[$key] ) ) {
				$this->$key = $data[$key];
			}
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
			'parent_slug' => $this->parent_slug,
			'page_title'  => $this->page_title,
			'menu_title'  => $this->menu_title,
			'capability'  => $this->capability,
			'page_slug'   => $this->page_slug,
		];

		$this->page->add_settings_page( $args );
	}
}
