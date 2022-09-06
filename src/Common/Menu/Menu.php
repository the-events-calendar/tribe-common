<?php
/**
 * The base, abstract, class modeling a menu.
 *
 * This class does nothing by itself - it is meant to be extended for specific menus,
 * changing the properties as appropriate.
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
abstract class Menu implements Menu_Contract {

	/**
	 * Title for the menu page.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public $page_title = '';

	/**
	 * Title for the Menu item in the admin menu.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public $menu_title = '';

	/**
	 * Required capability for accessing the menu.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public $capability = 'manage_options';

	/**
	 * URL sug for the menu.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public $menu_slug = '';

	/**
	 * Page content callback.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public $callback = 'render';

	/**
	 * URL (or dashicon string) for the menu icon.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public $icon_url = 'dashicons-menu-alt';

	/**
	 * WP Admin Menu position for the new menu.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public $position = '';

	/**
	 * Add a settings page?
	 *
	 * @since TBD
	 *
	 * @var boolean
	 */
	public $settings = false;

	/**
	 * Path to the file for the settings data.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public $settings_file = '';

	/**
	 * Holds the settings page object.
	 *
	 * @since TBD
	 *
	 * @var obj
	 */
	public $settings_page;

	/**
	 * Placeholder for the settings page, if we have one.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	public $settings_page_data = [];

	public function hooks() {
		add_action( 'admin_menu', array( $this, 'add_menu' ), 20 );
	}

	/**
	 * Sugar function for add_menu_page that allows us to utilize some of the parameters elsewhere.
	 *
	 * @since TBD
	 *
	 * @param array<string,mixed> $args An array of arguments, can contain the following:
	 *
	 * Internal:
	 * @param string $name
	 *
	 * From add_menu_page():
	 * @param string    $page_title The text to be displayed in the title tags of the page when the menu is selected.
	 * @param string    $menu_title The text to be used for the menu.
	 * @param string    $capability The capability required for this menu to be displayed to the user.
	 * @param string    $menu_slug  The slug name to refer to this menu by. Should be unique for this menu page and only
	 *                              include lowercase alphanumeric, dashes, and underscores characters to be compatible
	 *                              with sanitize_key().
	 * @param callable  $callback   Optional. The function to be called to output the content for this page.
	 * @param string    $icon_url   Optional. The URL to the icon to be used for this menu.
	 *                               * Pass a base64-encoded SVG using a data URI, which will be colored to match
	 *                                the color scheme. This should begin with 'data:image/svg+xml;base64,'.
	 *                               * Pass the name of a Dashicons helper class to use a font icon,
	 *                                e.g. 'dashicons-chart-pie'.
	 *                               * Pass 'none' to leave div.wp-menu-image empty so an icon can be added via CSS.
	 * @param int|float $position   Optional. The position in the menu order this item should appear.
	 */
	public function add_menu( $args ) {
		$this->option_group = add_menu_page(
			$this->page_title,
			$this->menu_title,
			$this->capability,
			$this->menu_slug,
			$this->callback,
			$this->icon_url,
			$this->position,
		);

		$foo = '';

		// Something went wrong, let folks know upstream.
		if ( ! $this->option_group ) {
			return false;
		}

		if ( $this->settings ) {
			$defaults = [
				'parent_slug'   => $this->menu_slug,
				'page_slug'     => 'settings',
				'page_title'    => 'Settings - ' . $this->menu_title,
				'menu_title'    => 'Settings',
				'capability'    => $this->capability,
				'option_group'  => $this->option_group,
				'settings_file' => $this->settings_file,
				'sections'      => [],
				'tabs'          => [],
			];

			$this->settings_page_data = wp_parse_args( $this->settings_page_data, $defaults );
		}

		// Only auto-create a Settings page if we're set up for it.
		if ( $this->settings ) {
			$this->create_settings_page();
		}

		return $this->option_group;
	}

	public function create_settings_page() {
		$this->settings_page = new Settings_Page( $this->settings_page_data );
	}

}
