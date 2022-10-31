<?php
namespace TEC\Common\Settings;

/**
 * Settings_Page
 *
 * Manages the pages for all plugin settings.
 *
 * @since TBD
 */
class Settings_Page {
	/**
	 * constructor
	 */
	public function __construct() {
		$this->add_hooks();
	}

	/**
	 * Undocumented function
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function add_hooks() {
		// option pages
		add_action( '_network_admin_menu', [ $this, 'init_settings' ] );
		add_action( '_admin_menu', [ $this, 'init_settings' ] );
	}

	/**
	 * Init the settings API and add a hook to add your own setting tabs
	 *
	 * @return void
	 */
	public function init_options() {

	}

}
