<?php

namespace TEC\Common\Settings;

/**
 * Class Manager
 *
 * Handles the structure and organization of settings.
 * This SHOULD NOT handle additional admin pages.
 * This SHOULD NOT handle saving "hidden tabs".
 * This SHOULD NOT handle instantiating tabs.
 *
 * @since TBD
 */
class Manager extends \tad_DI52_ServiceProvider {
	/**
	 * constructor
	 */
	public function register() {
		$this->add_hooks();

		tribe_singleton( 'tec.settings', Settings::class );
		tribe_singleton( 'tec.settings.tabs', Tabs::class );
		tribe_singleton( 'tec.settings.form', Admin_Form::class );
	}

	public function add_hooks() {
		// option pages
		add_action( '_network_admin_menu', [ $this, 'init_settings' ] );
		add_action( '_admin_menu', [ $this, 'init_settings' ] );

		add_action( 'tec_do_tabs', [ $this, 'do_setting_tabs' ] );
		add_action( 'updated_option', [ $this, 'update_options_cache' ], 10, 3 );
	}

	/**
	 * Init the settings API and add a hook to add your own setting tabs
	 *
	 * @return void
	 */
	public function init_settings() {
		Settings::instance();
	}


	/**
	 * Registers the license key management tab in the Events > Settings screen,
	 * only if premium addons are detected.
	 */
	protected function do_licenses_tab() {
		$show_tab = ( current_user_can( 'activate_plugins' ) && $this->have_addons() );

		/**
		 * Provides an opportunity to override the decision to show or hide the licenses tab
		 *
		 * Normally it will only show if the current user has the "activate_plugins" capability
		 * and there are some currently-activated premium plugins.
		 *
		 * @var bool
		 */
		if ( ! apply_filters( 'tec_show_licenses_tab', $show_tab ) ) {
			return;
		}

		/**
		 * @var $licenses_tab
		 */
		include \Tribe__Main::instance()->plugin_path . 'src/admin-views/tribe-options-licenses.php';

		/**
		 * Allows the fields displayed in the licenses tab to be modified.
		 *
		 * @var array
		 */
		$license_fields = apply_filters( 'tribe_license_fields', $licenses_tab );

		new \Tribe__Settings_Tab( 'licenses', esc_html__( 'Licenses', 'tribe-common' ), [
			'priority'      => '40',
			'fields'        => $license_fields,
			'network_admin' => is_network_admin() ? true : false,
		] );
	}

	/**
	 * Create the help tab
	 */
	public function do_help_tab() {
		/**
		 * Include Help tab Assets here
		 */

		include_once \Tribe__Main::instance()->plugin_path . 'src/admin-views/help.php';
	}

	/**
	 * Add help menu item to the admin (unless blocked via network admin settings).
	 *
	 * @todo move to an admin class
	 */
	public function add_help_admin_menu_item() {
		$hidden_settings_tabs = self::get_network_option( 'hideSettingsTabs', [] );
		if ( in_array( 'help', $hidden_settings_tabs ) ) {
			return;
		}

		$parent = class_exists( 'Tribe__Events__Main' ) ? \Tribe__Settings::$parent_page : \Tribe__Settings::$parent_slug;
		$title  = esc_html__( 'Help', 'tribe-common' );
		$slug   = 'tribe-help';

		add_submenu_page( $parent, $title, $title, 'manage_options', $slug, [ $this, 'do_help_tab' ] );
	}

	/**
	 * Tries to discover if licensable addons are activated on the same site.
	 *
	 * @return bool
	 */
	protected function have_addons() {
		$addons = apply_filters( 'tribe_licensable_addons', [] );

		return ! empty( $addons );
	}

	/**
	 * Static Singleton Factory Method
	 *
	 * @return Manager
	 */
	public static function instance() {
		return tribe( 'settings.manager' );
	}
}
