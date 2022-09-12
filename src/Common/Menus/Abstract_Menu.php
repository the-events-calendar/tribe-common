<?php
/**
 * The base, abstract, class modeling a menu.
 *
 * This class does nothing by itself - it is meant to be extended for specific menus,
 * changing the properties as appropriate.
 *
 * If you want to create a submenu - use the Submenu Trait as well.
 * If you want to create a settings page - use the With_Settings Trait as well (probably alongside the Submenu Trait)
 *
 * @since TBD
 *
 * @package TEC\Common\Menus
 */

namespace TEC\Common\Menus;

use \TEC\Common\Menus\Factory;

/**
 * Class Menu
 *
 * @since TBD
 *
 * @package TEC\Common\Menus
 */
abstract class Abstract_Menu implements Menu_Contract {
	/**
	 * Title for the menu page.
	 * Required.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected $page_title = '';

	/**
	 * Title for the Menu item in the admin menu.
	 * Required.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected $menu_title = '';

	/**
	 * Required capability for accessing the menu.
	 * Required.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected $capability = 'manage_options';

	/**
	 * URL slug for the menu.
	 * Required.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected static $menu_slug = '';

	/**
	 * Page content callback.
	 * Without this the menu will display a blank page at best.
	 * Unless it's a post-type menu item - those get a callback method automatically.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected $callback = 'render';

	/**
	 * URL (or dashicon string) for the menu icon.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected $icon_url = 'dashicons-menu-alt';

	/**
	 * WP Admin Menu position for the new menu.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected $position = '';

	/**
	 * Parent menu reference.
	 *
	 * @since TBD
	 *
	 * @var ?string|obj
	 */
	protected $parent_menu = null;

	/**
	 * Path to the file for the settings data.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected $settings_file = '';

	/**
	 * Holds the settings page object.
	 *
	 * @since TBD
	 *
	 * @var ?obj
	 */
	protected $settings_page = null;

	/**
	 * Whether this is a submenu or not.
	 *
	 * @since TBD
	 *
	 * @var boolean
	 */
	protected $is_submenu = false;

	/**
	 * Placeholder for the settings page, if we have one.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	protected $settings_page_data = [];

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->build();

	}

	public function build() {
		tribe( Factory::class )->add_menu( $this );
	}

	public function render() {
		echo "render";
	}

	public function is_submenu() {
		// Note - DO NOT autoload here!
		$traits = (array) class_uses( $this, false );
		return $this->is_submenu && isset( $traits['Submenu'] );
	}

	public function get_slug() {
		return self::$menu_slug;
	}

	public function get_parent() {
		return $this->parent_menu;
	}

	public function get_parent_slug() {
		return $this->get_parent()->get_slug();
	}

	public function get_callback() {
		return $this->callback;
	}
}
