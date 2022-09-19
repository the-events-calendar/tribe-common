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

use \TEC\Common\Menus\Menus;

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
	 * URL slug for the menu. Must be unique.
	 * Used internally as an ID. Required.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static $menu_slug = '';

	/**
	 * Page content callback.
	 * Without this the menu will display a blank page at best.
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
	 * @var int
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
	 * {@inheritDoc}
	 */
	public function __construct() {
		$this->init();
		$this->hooks();
		$this->build();
	}

	/**
	 * {@inheritDoc}
	 */
	public function init() {
		$this->callback = [ $this, 'render' ];
	}

	/**
	 * Adds any required programmatic action/filter hooks for the menu.
	 * This is for internal use only - please add your own hooks via a Service Provider.
	 *
	 * @since TBD
	 */
	protected function hooks() {}

	/**
	 * {@inheritDoc}
	 */
	public function build() {
		tribe( Menus::class )->add_menu( $this );
	}

	/**
	 * Renders the admin page content for the menu item.
	 *
	 * @since TBD
	 */
	public function render() {
		echo "Your {$this->get_menu_title()} menu works! Now override this function to render your admin page.";
	}

	/**
	 * {@inheritDoc}
	 */
	public function register_menu() {
		/**
		 * Allows triggering actions before the menu page is registered with WP.
		 *
		 * @param TEC\Common\Menus\Menu $menu The current menu object.
		 */
		do_action( 'tec_menu_setup_' . $this->get_slug(), $this );

		$this->register_in_wp();

		do_action( 'tec_menu_registered', $this );

		do_action( 'tec_menu_' . $this->get_slug() . '_registered', $this );
	}

	/**
	 * Actually handles registering the menu with WordPress.
	 *
	 * @since TBD
	 */
	protected function register_in_wp() {
		$this->hook_suffix = add_menu_page(
			$this->get_page_title(),
			$this->get_menu_title(),
			$this->get_capability(),
			$this->get_slug(),
			$this->get_callback(),
			$this->get_icon_url(),
			$this->get_position()
		);

		return $this->get_hook_suffix();
	}

	/**
	 * {@inheritDoc}
	 */
	public function is_registered() : bool {
		return (bool) $this->hook_suffix;
	}

	/**
	 * {@inheritDoc}
	 */
	public function is_submenu() : bool {
		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_slug() : string {
		return static::$menu_slug;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_parent_slug() : ?string {
		return ! empty( $this->parent_slug ) ? $this->parent_slug : null;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_parent() : ?Menu_Contract {
		if ( empty( $this->parent_slug ) ) {
			return null;
		}

		return tribe( Menus::class )->get_menu( $this->parent_slug );
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_callback() : string|callable {
		return $this->callback;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_capability() : string {
		return $this->capability;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_position() : int {
		return $this->position;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_icon_url() : string {
		return $this->icon_url;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_page_title() : string {
		return $this->page_title;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_menu_title() : string {
		return $this->menu_title;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_hook_suffix() : ?string {
		if ( empty( $this->hook_suffix ) ) {
			return null;
		}

		return $this->hook_suffix;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_url() : string {
		return menu_page_url( static::$menu_slug, false );
	}
}
