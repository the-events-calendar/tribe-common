<?php
/**
 * The base, abstract, class modeling a menu.
 *
 * This class does nothing by itself - it is meant to be extended for specific menus,
 * changing the properties as appropriate.
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
	public static $menu_slug = '';

	/**
	 * Page content callback.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public $callback = '';

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
	 * Whether this is a submenu or not.
	 *
	 * @since TBD
	 *
	 * @var boolean
	 */
	public $submenu = false;

	/**
	 * Placeholder for the settings page, if we have one.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	public $settings_page_data = [];

	/**
	 * Constructor.
	 *
	 * @since TBD
	 */
	public function __construct() {
		$this->callback = [ $this, 'render' ];
	}

	/**
	 * Handle internal hooking.
	 *
	 * @since TBD
	 */
	public function hooks() {
		add_action( 'admin_menu', array( $this, 'add_menu' ), 20 );
		add_action( 'tec_menu_setup', [ $this, 'create_settings_page' ] );
	}

	/**
	 * Sugar function for add_menu_page that allows us to utilize some of the parameters elsewhere.
	 * Triggers setup of connected submenus and settings pages as well.
	 *
	 * @since TBD
	 *
	 * @param array<string,mixed> $args An array of arguments, can contain the following:
	 *
	 * Internal:name
	 * @param bool      $submenu    Is this a submenu.
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
	 *
	 * @return string|false         The resulting page's hook_suffix. False on a failure.
	 */
	public function add_menu( $args ): ?string {
		if ( empty( $args[ 'submenu' ] ) ) {
			$this->option_group = add_menu_page(
				$this->page_title,
				$this->menu_title,
				$this->capability,
				static::$menu_slug,
				$this->callback,
				$this->icon_url,
				$this->position,
			);
		} else {
			unset( $args[ 'submenu' ] );
			$this->option_group = $this->add_submenu( $this, $args );
		}

		// Something went wrong, let folks know upstream.
		if ( ! $this->option_group ) {
			return false;
		}

		/**
		 * Allows triggering actions once the menu page is set up.
		 *
		 * @param TEC\Common\Menus\Menu $menu The current menu object.
		 */
		do_action( 'tec_menu_setup', $this );

		/**
		 * Allows triggering actions once the menu page is set up.
		 *
		 * @param TEC\Common\Menus\Menu $menu The current menu object.
		 */
		do_action( 'tec_menu_setup_' . static::$menu_slug, $this );

		// Follow the lead of
		return $this->option_group;
	}

	/**
	 * Builds a settings page if the data is provided for one.
	 *
	 * @since TBD
	 *
	 * @param TEC\Common\Menus\Menu $menu The current (main) menu object.
	 * Typically, `$menu` is `$this` but sing it as a param allows for calling from outside the main menu object.
	 */
	public function create_settings_page( $menu ) {
		if ( ! $menu->settings ) {
			return;
		}

		$defaults = [
			'parent_slug'   => $menu::$menu_slug,
			'page_slug'     => 'settings',
			'page_title'    => 'Settings - ' . $menu->menu_title,
			'menu_title'    => 'Settings',
			'capability'    => $menu->capability,
			'option_group'  => $menu->option_group,
			'settings_file' => $menu->settings_file,
		];

		$menu->settings_page_data = wp_parse_args( $menu->settings_page_data, $defaults );

		$menu->settings_page = new Settings_Page( $menu->settings_page_data );
	}

	/**
	 * Undocumented function
	 *
	 * @since TBD
	 *
	 * @param TEC\Common\Menus\Menu $menu The current (main) menu object.
	 * @param array<string,mixed> $args An array of arguments, can contain the following:
	 *
	 *  From add_submenu_page():
	 *
	 * @param string    $parent_slug The slug name for the parent menu (or the file name of a standard WordPress admin page).
	 * @param string    $page_title  The text to be displayed in the title tags of the page when the menu is selected.
	 * @param string    $menu_title  The text to be used for the menu.
	 * @param string    $capability  The capability required for this menu to be displayed to the user.
	 * @param string    $menu_slug   The slug name to refer to this menu by. Should be unique for this menu and only
	 *                               include lowercase alphanumeric, dashes, and underscores characters to be compatible
	 *                               with sanitize_key().
	 * @param callable  $callback    Optional. The function to be called to output the content for this page.
	 * @param int|float $position    Optional. The position in the menu order this item should appear.
	 *
	 * @return string|false         The resulting page's hook_suffix. False on a failure.
	 */
	public function add_submenu( $menu, $args ): string|false {
		$defaults = [
			'parent_slug' => $menu::$menu_slug,
			'page_title' => 'Submenu - ' . $menu->menu_title,
			'menu_title' => 'Submenu',
			'capability'  => $menu->capability,
			'menu_slug' => '',
			'callback' => '',
			'position' => null,
		];

		$args = wp_parse_args( $args, $defaults );

		$hook_suffix = add_submenu_page(
			$args[ 'parent_slug' ],
			$args[ 'page_title' ],
			$args[ 'menu_title' ],
			$args[ 'capability' ],
			$args[ 'menu_slug' ],
			$args[ 'callback' ],
			$args[ 'position' ]
		);

		return $hook_suffix;
	}

}
