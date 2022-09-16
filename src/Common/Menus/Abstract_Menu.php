<?php
/**
 * The base, abstract class modeling a menu.
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
	public static $menu_slug = '';

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
	 * Placeholder for the hook suffix we get from registering with WP.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	public $hook_suffix = '';

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->callback = [ $this, 'render' ];
		$this->build();
		$this->hooks();
	}

	public function hooks() {}

	public function build() {
		tribe( Factory::class )->add_menu( $this );
	}

	public function render() {
		echo "It works! Now override this to render your admin page.";
	}

	public function is_submenu() {
		return false;
	}

	public function get_slug() {
		return static::$menu_slug;
	}

	public function get_parent_slug() {
		return ! empty( $this->parent_slug ) ? $this->parent_slug : false;
	}

	public function get_parent() {
		if ( empty( empty( $this->parent_slug ) ) ) {
			return false;
		}

		return tribe( Factory::class )->get_menu( $this->parent_slug );
	}

	public function get_callback() {
		return $this->callback;
	}

	public function get_capability() {
		return $this->capability;
	}

	public function get_position() {
		return $this->position;
	}

	public function get_icon_url() {
		return $this->icon_url;
	}

	public function get_page_title() {
		return $this->page_title;
	}

	public function get_menu_title() {
		return $this->menu_title;
	}

	/**
	 * Wrapper function for register_in_wp() that contains hooks
	 *
	 * @since TBD
	 */
	public function register_menu() {
		/**
		 * Allows triggering actions before the menu page is registered with WP.
		 *
		 * @param TEC\Common\Menus\Menu $menu The current menu object.
		 */
		do_action( 'tec_menu_setup_' . $this->get_slug(), $this );

		$this->register_in_wp();
	}


	/**
	* @global array $menu
	* @global array $admin_page_hooks
	* @global array $_registered_pages
	* @global array $_parent_pages
	*
	* @param string    $page_title The text to be displayed in the title tags of the page when the menu is selected.
	* @param string    $menu_title The text to be used for the menu.
	* @param string    $capability The capability required for this menu to be displayed to the user.
	* @param string    $menu_slug  The slug name to refer to this menu by. Should be unique for this menu page and only
	*                              include lowercase alphanumeric, dashes, and underscores characters to be compatible
	*                              with sanitize_key().
	* @param callable  $callback   Optional. The function to be called to output the content for this page.
	* @param string    $icon_url   Optional. The URL to the icon to be used for this menu.
	*                              * Pass a base64-encoded SVG using a data URI, which will be colored to match
	*                                the color scheme. This should begin with 'data:image/svg+xml;base64,'.
	*                              * Pass the name of a Dashicons helper class to use a font icon,
	*                                e.g. 'dashicons-chart-pie'.
	*                              * Pass 'none' to leave div.wp-menu-image empty so an icon can be added via CSS.
	* @param int|float $position   Optional. The position in the menu order this item should appear.
	* @return string The resulting page's hook_suffix.
	*/
	public function register_in_wp() {
		$this->hook_suffix = add_menu_page(
			$this->get_page_title(),
			$this->get_menu_title(),
			$this->get_capability(),
			$this->get_slug(),
			$this->get_callback(),
			$this->get_icon_url(),
			$this->get_position(),
		);

		return $this->hook_suffix;
	}

	public function is_registered() {
		return (bool) $this->hook_suffix;
	}

	public function get_hook_suffix() {
		if ( empty( $this->hook_suffix ) ) {
			return false;
		}

		return $this->hook_suffix;
	}

	public function get_url() {
		return menu_page_url( static::$menu_slug, false );
	}
}
