<?php
/**
 * The base, abstract, class modeling a settings page and settings.
 *
 * @since   4.9.18
 *
 * @package TEC\Common\Menu
 */


namespace TEC\Common\Menu;

/**
 * Class Menu
 *
 * @since TBD
 *
 * @package TEC\Common\Menu
 */
class Settings_Page {

	public $page;

	public $option_group;

	public $settings_file;

	public $parent_slug;
	public $page_title;
	public $menu_title;
	public $capability;

	public function __construct( array $data ) {
		// Include WordPressSettingsFramework
		require_once \Tribe__Main::instance()->plugin_path . '/vendor/iconicwp/wordpress-settings-framework/wp-settings-framework.php';


		$keys = array_keys( get_object_vars( $this ) );

		foreach( $keys as $key ) {
			if ( isset( $data[$key] ) ) {
				$this->$key = $data[$key];
				$bar = '';
			}
		}

		$foo = '';

		$this->hooks();

		$this->page = new \WordPressSettingsFramework( $this->settings_file, $this->option_group );
	}

	public function hooks() {
		add_action( 'admin_menu', array( $this, 'add_settings_page' ), 25 );

		add_filter(
			'wpsf_register_settings_' . $this->option_group,
			[
				$this,
				'filter_wpsf_register_settings'
			],
			10
		);
	}

	public function filter_wpsf_register_settings( array $args ) {
		if ( ! empty( $this->sections ) ) {
			$args['sections'] = $this->sections;
		}

		if ( ! empty( $this->tabs ) ) {
			$args['tabs'] = $this->tabs;
		}

		return $args;

	}

	public function add_settings_page() {
		$args = [
			'parent_slug' => $this->parent_slug,
			'page_title'  => $this->page_title,
			'menu_title'  => $this->menu_title,
			'capability'  => $this->capability,
		];

		$this->page->add_settings_page( $args );
	}
}
