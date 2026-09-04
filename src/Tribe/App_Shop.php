<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'Tribe__App_Shop' ) ) {
	/**
	 * Class that handles the integration with our Shop App API
	 */
	class Tribe__App_Shop {

		/**
		 * Slug of the WP admin menu item
		 */
		const MENU_SLUG = 'tribe-app-shop';

		/**
		 * Singleton instance
		 *
		 * @var null or Tribe__App_Shop
		 */
		private static $instance = null;
		/**
		 * The slug for the new admin page
		 *
		 * @var string
		 */
		private $admin_page = null;

		/**
		 * Class constructor
		 */
		public function __construct() {
			add_action( 'admin_menu', [ $this, 'add_menu_page' ], 100 );
			add_action( 'wp_before_admin_bar_render', [ $this, 'add_toolbar_item' ], 20 );

			$this->register_assets();
		}

		/**
		 * Adds the page to the admin menu
		 */
		public function add_menu_page() {
			if ( ! tribe( 'settings' )->should_setup_pages() ) {
				return;
			}

			$page_title = esc_html__( 'Event Add-Ons', 'tribe-common' );
			$menu_title = esc_html__( 'Event Add-Ons', 'tribe-common' );
			$capability = apply_filters( 'tribe_events_addon_page_capability', 'install_plugins' );

			$where = tribe( 'settings' )->get_parent_slug();

			$this->admin_page = add_submenu_page(
				$where,
				$page_title,
				$menu_title,
				$capability,
				self::MENU_SLUG,
				[
					$this,
					'do_menu_page',
				]
			);
		}

		/**
		 * Adds a link to the shop app to the WP admin bar
		 */
		public function add_toolbar_item() {

			$capability = apply_filters( 'tribe_events_addon_page_capability', 'install_plugins' );

			// prevent users who cannot install plugins from seeing addons link
			if ( current_user_can( $capability ) ) {
				global $wp_admin_bar;

				$wp_admin_bar->add_menu( [
					'id'     => 'tribe-events-app-shop',
					'title'  => esc_html__( 'Event Add-Ons', 'tribe-common' ),
					'href'   => tribe( 'settings' )->get_url( [ 'page' => self::MENU_SLUG ] ),
					'parent' => 'tribe-events-settings-group',
				] );
			}
		}

		/**
		 * Registers the plugin assets
		 */
		protected function register_assets() {
			tec_assets(
				Tribe__Main::instance(),
				[
					[ 'tribe-app-shop-css', 'app-shop.css' ],
					[ 'tribe-app-shop-js', 'app-shop.js', [ 'jquery' ] ],
				],
				'admin_enqueue_scripts',
				[
					'conditionals' => [ $this, 'is_current_page' ],
				]
			);
		}

		/**
		 * Checks if the current page is the app shop
		 *
		 * @since 4.5.7
		 *
		 * @return bool
		 */
		public function is_current_page() {
			if ( ! tribe( 'settings' )->should_setup_pages() || ! did_action( 'admin_menu' ) ) {
				return false;
			}

			if ( is_null( $this->admin_page ) ) {
				_doing_it_wrong(
					__FUNCTION__,
					'Function was called before it is possible to accurately determine what the current page is.',
					'4.5.6'
				);
				return false;
			}

			return Tribe__Admin__Helpers::instance()->is_screen( $this->admin_page );
		}

		/**
		 * Renders the Shop App page
		 *
		 * @since TBD Removed the bundles and extensions data passed to the template.
		 *
		 * @return void
		 */
		public function do_menu_page() {
			$main = Tribe__Main::instance();
			$products = $this->get_all_products();
			include_once Tribe__Main::instance()->plugin_path . 'src/admin-views/app-shop.php';
		}

		/**
		 * Gets all products from the API
		 *
		 * @return array|WP_Error
		 */
		private function get_all_products() {
			$all_products = tribe( 'plugins.api' )->get_products();

			$products = [
				'the-events-calendar'      => (object) $all_products['the-events-calendar'],
				'events-calendar-pro'      => (object) $all_products['events-calendar-pro'],
				'event-aggregator'         => (object) $all_products['event-aggregator'],
				'event-tickets'            => (object) $all_products['event-tickets'],
				'event-tickets-plus'       => (object) $all_products['event-tickets-plus'],
				'promoter'                 => (object) $all_products['promoter'],
				'tribe-filterbar'          => (object) $all_products['tribe-filterbar'],
				'events-community'         => (object) $all_products['events-community'],
				'event-schedule-manager'   => (object) $all_products['event-schedule-manager'],
				'tribe-eventbrite'         => (object) $all_products['tribe-eventbrite'],
				'image-widget-plus'        => (object) $all_products['image-widget-plus'],
			];

			return $products;
		}

		/**
		 * Static Singleton Factory Method
		 *
		 * @return Tribe__App_Shop
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) ) {
				$className      = __CLASS__;
				self::$instance = new $className;
			}

			return self::$instance;
		}
	}
}
