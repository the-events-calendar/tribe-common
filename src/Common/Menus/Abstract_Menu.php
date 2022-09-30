<?php
/**
 * The base, abstract class modeling a menu.
 *
 * This class does nothing by itself - it is meant to be extended for specific menus,
 * changing the properties as appropriate.
 *
 * If you want to create a submenu - use the Submenu Trait as well.
 * If you want to create a menu for a Custom Post type (CPT) use the CPT trait.
 * Traits can be combined. So "use Submenu, CPT, With_Admin_Bar;" is perfectly valid (and used in TEC).
 *
 * @since TBD
 *
 * @package TEC\Common\Menus
 */

namespace TEC\Common\Menus;

use \TEC\Common\Menus\Menus;
use \WP_Screen;

/**
 * Class Menu
 *
 * @since TBD
 *
 * @package TEC\Common\Menus
 */
abstract class Abstract_Menu {
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
	 * Required capability for including the page in the admin menu.
	 * The render function will also have to check the capability before rendering.
	 * @see render() below.
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
	public $menu_slug = '';

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
	protected $position;

	/**
	 * Placeholder for the hook suffix we get from registering with WP.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	public $hook_suffix = '';


	protected $de_duplicate = false;

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
	public function init() : void {
		$this->callback = [ $this, 'render' ];
	}

	/**
	 * Adds any required programmatic action/filter hooks for the menu.
	 * This is for internal use only - please add your own hooks via a Service Provider.
	 *
	 * @since TBD
	 */
	protected function hooks() : void {
		add_action( 'admin_menu', [ $this, 'de_duplicate' ], 100);

		add_action( 'admin_enqueue_scripts', [ $this, 'register_assets' ] );

		// For the CPT trait hooks.
		if ( method_exists( $this, 'cpt_hooks' ) ) {
			$this->cpt_hooks();
		}

		// For the With_Admin_Bar trait hooks.
		if ( method_exists( $this, 'settings_hooks' ) ) {
			$this->settings_hooks();
		}

		// For the Taxonomy trait hooks.
		if ( method_exists( $this, 'tax_hooks' ) ) {
			$this->tax_hooks();
		}

		// For the With_Admin_Bar trait hooks.
		if ( method_exists( $this, 'adminbar_hooks' ) ) {
			$this->adminbar_hooks();
		}

		// For the Tabbed trait hooks.
		if ( method_exists( $this, 'tabbed_hooks' ) ) {
			$this->tabbed_hooks();
		}

		// For adding menu-specific hooks.
		if ( method_exists( $this, 'custom_hooks' ) ) {
			$this->custom_hooks();
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function build() : void {
		tribe( Menus::class )->add_menu( $this );
	}

	/**
	 * Renders the admin page content for the menu item.
	 * No return - just directly output HTML or echo an HTML string.
	 * @see https://developer.wordpress.org/reference/functions/add_menu_page/#parameters
	 *
	 * Also - WordPress only checks user cap to display the menu item.
	 * If you want to keep folks from accessing via direct link, you need to check the capacity before rendering.
	 *
	 * @since TBD
	 */
	public function render() : void {
		if ( ! current_user_can( $this->get_capability() ) ) {
			return;
		}

		echo "Your {$this->get_menu_title()} menu works! Now override this function to render your admin page.";
		echo "\n Don't forget to check the user capability before outputting the page!";
	}

	/**
	 * {@inheritDoc}
	 */
	public function register_menu() : void {
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
	protected function register_in_wp() : string {
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
	 * Removes the duplicated submenu item.
	 *
	 * @since TBD
	 */
	public function de_duplicate() : void {
		if ( ! $this->de_duplicate ) {
			return;
		}

		remove_submenu_page( $this->get_slug(), $this->get_slug() );
	}

	/**
	 * {@inheritDoc}
	 */
	public function is_registered() : bool {
		return (bool) $this->hook_suffix;
	}

	/**
	 * {@inheritDoc}
	 *
	 * Note the version here is not filterable, but it is in the Traits\Submenu override.
	 */
	public function is_submenu() : bool {
		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_slug() : string {
		return $this->menu_slug;
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
	public function get_parent() : ?Abstract_Menu {
		if ( empty( $this->parent_slug ) ) {
			return null;
		}

		return Menus::get_menu( $this->parent_slug );
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_callback() : string|callable|null {
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
		return menu_page_url( $this->get_slug(), false );
	}

	/**
	 * Checks if this admin page is the current page.
	 *
	 * @since TBD
	 */
	public function is_current_page() : bool {
		global $current_screen;

		if ( is_null( $this->get_hook_suffix() ) ) {
			_doing_it_wrong(
				__FUNCTION__,
				'Function was called before it is possible to accurately determine what the current page is.',
				'TBD'
			);

			return false;
		}

		// Not in the admin so we just don't care.
		if ( ! is_admin() ) {
			return false;
		}

		// Doing AJAX? bail.
		if ( tribe( 'context' )->doing_ajax() ) {
			return false;
		}

		// Avoid Notices by checking the object type of WP_Screen.
		if ( ! $current_screen instanceof WP_Screen ) {
			return false;
		}

		// Match our admin page.
		if ( $current_screen->id === $this->get_hook_suffix() ) {
			return true;
		}

		return false;
	}

	/**
	 * Override this function to register/enqueue assets (CSS and JS, etc)
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function register_assets() {
		if ( ! $this->is_current_page() ) {
			return;
		}

		// register & enqueue your assets.
	}
}
