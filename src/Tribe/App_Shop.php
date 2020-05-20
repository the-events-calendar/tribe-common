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
			add_action( 'admin_menu', array( $this, 'add_menu_page' ), 100 );
			add_action( 'wp_before_admin_bar_render', array( $this, 'add_toolbar_item' ), 20 );

			$this->register_assets();
		}

		/**
		 * Adds the page to the admin menu
		 */
		public function add_menu_page() {
			if ( ! Tribe__Settings::instance()->should_setup_pages() ) {
				return;
			}

			$page_title = esc_html__( 'Event Add-Ons', 'tribe-common' );
			$menu_title = esc_html__( 'Event Add-Ons', 'tribe-common' );
			$capability = apply_filters( 'tribe_events_addon_page_capability', 'install_plugins' );

			$where = Tribe__Settings::instance()->get_parent_slug();

			$this->admin_page = add_submenu_page( $where, $page_title, $menu_title, $capability, self::MENU_SLUG, array( $this, 'do_menu_page' ) );
		}

		/**
		 * Adds a link to the shop app to the WP admin bar
		 */
		public function add_toolbar_item() {

			$capability = apply_filters( 'tribe_events_addon_page_capability', 'install_plugins' );

			// prevent users who cannot install plugins from seeing addons link
			if ( current_user_can( $capability ) ) {
				global $wp_admin_bar;

				$wp_admin_bar->add_menu( array(
					'id'     => 'tribe-events-app-shop',
					'title'  => esc_html__( 'Event Add-Ons', 'tribe-common' ),
					'href'   => Tribe__Settings::instance()->get_url( array( 'page' => self::MENU_SLUG ) ),
					'parent' => 'tribe-events-settings-group',
				) );
			}
		}

		/**
		 * Registers the plugin assets
		 */
		protected function register_assets() {
			tribe_assets(
				Tribe__Main::instance(),
				array(
					array( 'tribe-app-shop-css', 'app-shop.css' ),
					array( 'tribe-app-shop-js', 'app-shop.js', array( 'jquery' ) ),
				),
				'admin_enqueue_scripts',
				array(
					'conditionals' => array( $this, 'is_current_page' ),
				)
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
			if ( ! Tribe__Settings::instance()->should_setup_pages() || ! did_action( 'admin_menu' ) ) {
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
		 */
		public function do_menu_page() {
			$main = Tribe__Main::instance();
			$products = $this->get_all_products();
			$bundles = $this->get_bundles();
			$extensions = $this->get_extensions();
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
				'the-events-calendar' =>      (object) $all_products['the-events-calendar'],
				'events-calendar-pro' =>      (object) $all_products['events-calendar-pro'],
				'virtual-events' =>           (object) $all_products['virtual-events'],
				'event-aggregator' =>         (object) $all_products['event-aggregator'],
				'event-tickets' =>            (object) $all_products['event-tickets'],
				'event-tickets-plus' =>       (object) $all_products['event-tickets-plus'],
				'promoter' =>                 (object) $all_products['promoter'],
				'tribe-filterbar' =>          (object) $all_products['tribe-filterbar'],
				'events-community' =>         (object) $all_products['events-community'],
				'events-community-tickets' => (object) $all_products['events-community-tickets'],
				'tribe-eventbrite' =>         (object) $all_products['tribe-eventbrite'],
				'image-widget-plus' =>        (object) $all_products['image-widget-plus'],
			];

			return $products;
		}

		/**
		 * Gets product bundles
		 *
		 * @return array|WP_Error
		 */
		private function get_bundles() {
			$bundles = [
				(object) [
					'title' => __( 'Events Marketing Bundle', 'tribe-common' ),
					'logo' => 'images/logo/bundle-event-marketing.svg',
					'link' => 'https://m.tri.be/somewhere',
					'discount' => __( 'Save over 40%', 'tribe-common' ),
					'description' => __( 'No third party websites, no extra ticket processing fees… just you, your attendees, and one kick ass event. Take full control of the event ticketing and marketing process and customize it to suit your needs.', 'tribe-common' ),
					'includes' => [
						'events-calendar-pro',
						'event-tickets-plus',
						'promoter',
					],
				],
				(object) [
					'title' => __( 'Event Importer Bundle', 'tribe-common' ),
					'logo' => 'images/logo/bundle-event-importer.svg',
					'link' => 'https://m.tri.be/somewhere',
					'discount' => __( 'Save over 40%', 'tribe-common' ),
					'description' => __( 'Fill your calendar with events from across the web', 'tribe-common' ),
					'includes' => [
						'events-calendar-pro',
						'tribe-filterbar',
						'event-aggregator'
					],
				],
				(object) [
					'title' => __( 'Virtual Event Marketing Bundle', 'tribe-common' ),
					'logo' => 'images/logo/bundle-virtual-events.svg',
					'link' => 'https://m.tri.be/somewhere',
					'discount' => __( 'Save over 40%', 'tribe-common' ),
					'description' => __( '[description]', 'tribe-common' ),
					'includes' => [
						'events-calendar-pro',
						'event-tickets-plus',
						'virtual-events',
						'promoter',
					],
				],
				(object) [
					'title' => __( 'Community Manager Bundle', 'tribe-common' ),
					'logo' => 'images/logo/bundle-community-manager.svg',
					'link' => 'https://m.tri.be/somewhere',
					'discount' => __( 'Save over 40%', 'tribe-common' ),
					'description' => __( 'Essential tools to build a robust community', 'tribe-common' ),
					'includes' => [
						'event-tickets-plus',
						'events-community',
						'events-community-tickets',
						'tribe-filterbar',
					],
				],
				(object) [
					'title' => __( 'Ultimate Bundle', 'tribe-common' ),
					'logo' => 'images/logo/bundle-ultimate.svg',
					'link' => 'https://m.tri.be/somewhere',
					'discount' => __( 'Save over 40%', 'tribe-common' ),
					'description' => __( 'All of our premium plugins in one big convenient package]', 'tribe-common' ),
					'includes' => [
						'events-calendar-pro',
						'event-tickets-plus',
						'virtual-events', /* code review: not true today, confirm this has changed before shipping! */
						'events-community',
						'events-community-tickets',
						'tribe-filterbar',
						'event-aggregator',
						'tribe-eventbrite',
						'promoter', /* code review: not true today, confirm this has changed before shipping! */
					],
				],

			];

			return $bundles;
		}

		/**
		 * Gets product extensions
		 *
		 * @return array|WP_Error
		 */
		private function get_extensions() {
			$extensions = [
				(object) [
					'title' => __( 'Web URL CTA', 'tribe-common' ),
					'link' => 'https://m.tri.be/something',
					'image' => 'images/shop/extension-web-url-cta.jpg',
					'description' => __( 'Use this shortcode to display events that are currently in progress, like webinars and livestreams', 'tribe-common' ),
				],
				(object) [
					'title' => __( 'Link to Webinar', 'tribe-common' ),
					'link' => 'https://m.tri.be/something',
					'image' => 'images/shop/extension-link-to-webinar.jpg',
					'description' => __( 'Use this shortcode to display events that are currently in progress, like webinars and livestreams', 'tribe-common' ),
				],
				(object) [
					'title' => __( 'Events Happening Now', 'tribe-common' ),
					'link' => 'https://m.tri.be/something',
					'image' => 'images/shop/extension-events-happening-now.jpg',
					'description' => __( 'Use this shortcode to display events that are currently in progress, like webinars and livestreams', 'tribe-common' ),
				],
				(object) [
					'title' => __( 'Venue Links', 'tribe-common' ),
					'link' => 'https://m.tri.be/something',
					'image' => 'images/shop/extension-custom-venue-links.jpg',
					'description' => __( 'Use this shortcode to display events that are currently in progress, like webinars and livestreams', 'tribe-common' ),
				],
				(object) [
					'title' => __( 'Change Label', 'tribe-common' ),
					'link' => 'https://m.tri.be/something',
					'image' => 'images/shop/extension-change-label.jpg',
					'description' => __( 'Use this shortcode to display events that are currently in progress, like webinars and livestreams', 'tribe-common' ),
				],
				(object) [
					'title' => __( 'Advanced Options', 'tribe-common' ),
					'link' => 'https://m.tri.be/something',
					'image' => 'images/shop/extension-advanced-options.jpg',
					'description' => __( 'Use this shortcode to display events that are currently in progress, like webinars and livestreams', 'tribe-common' ),
				],
			];

			return $extensions;
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
