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

			add_action( 'admin_print_styles-' . $this->admin_page, array( $this, 'enqueue' ) );
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
		 * Enqueue the styles and script
		 */
		public function enqueue() {
			wp_enqueue_style( 'app-shop', tribe_resource_url( 'app-shop.css', false, null, Tribe__Main::instance() ), array(), apply_filters( 'tribe_events_css_version', Tribe__Main::VERSION ) );
			wp_enqueue_script( 'app-shop', tribe_resource_url( 'app-shop.js', false, null, Tribe__Main::instance() ), array(), apply_filters( 'tribe_events_js_version', Tribe__Main::VERSION ) );
		}

		/**
		 * Renders the Shop App page
		 */
		public function do_menu_page() {
			$main = Tribe__Main::instance();
			$products = $this->get_all_products();
			include_once Tribe__Main::instance()->plugin_path . 'src/admin-views/app-shop.php';
		}

		/**
		 * Get's all products from the API
		 *
		 * @return array|WP_Error
		 */
		private function get_all_products() {
			$products = array(
				(object) array(
					'title' => __( 'Event Aggregator', 'tribe-common' ),
					'link' => 'https://theeventscalendar.com/product/event-aggregator/?utm_campaign=in-app&utm_source=addonspage&utm_medium=event-aggregator&utm_content=appstoreembedded-1',
					'description' => __( 'Importing events from multiple sources has never been easier! Event Aggregator helps you curate and manage event import feeds from Facebook, Meetup, Google Calendar, iCalendar, CSV, and ICS. Schedule automatic imports or manually import events when you’re ready. Event Aggregator provides a convenient dashboard to manage bulk imports, filters, one-way sync, import history, and more.', 'tribe-common' ),
					'image' => 'images/app-shop-ical.jpg',
				),
				(object) array(
					'title' => __( 'Events Calendar PRO', 'tribe-common' ),
					'link' => 'https://theeventscalendar.com/product/wordpress-events-calendar-pro/?utm_campaign=in-app&utm_source=addonspage&utm_medium=wordpress-events-calendar-pro&utm_content=appstoreembedded-1',
					'description' => sprintf(
						__( 'The Events Calendar PRO is a paid Add-On to our open source WordPress plugin %1$sThe Events Calendar%2$s. PRO offers a whole host of calendar features including recurring events, custom event attributes, saved venues and organizers, venue pages, advanced event admin and lots more.', 'tribe-common' ),
						'<a href="http://m.tri.be/18vc">',
						'</a>'
					),
					'image' => 'images/app-shop-pro.jpg',
				),
				(object) array(
					'title' => __( 'Event Tickets Plus', 'tribe-common' ),
					'link' => 'https://theeventscalendar.com/product/wordpress-event-tickets-plus/?utm_campaign=in-app&utm_source=addonspage&utm_medium=wordpress-event-tickets-plus&utm_content=appstoreembedded-1',
					'description' => sprintf(
						__( 'Event Tickets Plus allows you to sell tickets to your events using WooCommerce, Shopp, WP eCommerce, or Easy Digital Downloads. Use it on your posts and pages, or add %1$sThe Events Calendar%2$s and sell tickets from your events listings.', 'tribe-common' ),
						'<a href="http://m.tri.be/18vc">',
						'</a>'
					),
					'image' => 'images/app-shop-tickets-plus.jpg',
				),
				(object) array(
					'title' => __( 'Filter Bar', 'tribe-common' ),
					'link' => 'https://theeventscalendar.com/product/wordpress-events-filterbar/?utm_campaign=in-app&utm_source=addonspage&utm_medium=wordpress-events-filterbar&utm_content=appstoreembedded-1',
					'description' => __( 'It is awesome that your calendar is <em>THE PLACE</em> to get hooked up with prime choice ways to spend time. You have more events than Jabba the Hutt has rolls. Too bad visitors are hiring a personal assistant to go through all the choices. Ever wish you could just filter the calendar to only show events in walking distance, on a weekend, that are free? BOOM. Now you can. Introducing… the Filter Bar.', 'tribe-common' ),
					'image' => 'images/app-shop-filter-bar.jpg',
				),
				(object) array(
					'title' => __( 'Community Events', 'tribe-common' ),
					'link' => 'https://theeventscalendar.com/product/wordpress-community-events/?utm_campaign=in-app&utm_source=addonspage&utm_medium=wordpress-community-events&utm_content=appstoreembedded-1',
					'description' => __( 'Enable users to submit events to your calendar with Community Events. You can require user accounts or allow visitors to submit without an account. Want to make sure that nothing fishy is going on? Just turn on moderation. Decide if users can edit and manage their own events, or simply submit. Plus, no scary form setup! Just activate, configure the options & off you go.', 'tribe-common' ),
					'image' => 'images/app-shop-community.jpg',
				),
				(object) array(
					'title' => __( 'Community Tickets', 'tribe-common' ),
					'link' => 'https://theeventscalendar.com/product/community-tickets/?utm_campaign=in-app&utm_source=addonspage&utm_medium=community-tickets&utm_content=appstoreembedded-1',
					'description' => __( 'Enable Community Events organizers to offer tickets to their events. You can set flexible payment and fee options. They can even check-in attendees to their events! All of this managed from the front-end of your site without ever needing to grant access to your admin', 'tribe-common' ),
						'requires' => _x( 'Event Tickets Plus and Community Events', 'Names of required plugins for Community Tickets', 'tribe-common' ),
					'image' => 'images/app-shop-community-tickets.jpg',
				),
				(object) array(
					'title' => __( 'Eventbrite Tickets', 'tribe-common' ),
					'link' => 'https://theeventscalendar.com/product/wordpress-eventbrite-tickets/?utm_campaign=in-app&utm_source=addonspage&utm_medium=wordpress-eventbrite-tickets&utm_content=appstoreembedded-1',
					'description' => sprintf(
						__( 'The Eventbrite Tickets add-on allows you to create & sell tickets through The Events Calendar using the power of %1$sEventbrite%2$s. Whether you’re creating your ticket on the WordPress dashboard or importing the details of an already-existing event from %1$sEventbrite.com%2$s, this add-on brings the power of the Eventbrite API to your calendar.', 'tribe-common' ),
						'<a href="http://www.eventbrite.com/r/etp">',
						'</a>'
					),
					'image' => 'images/app-shop-eventbrite.jpg',
				),
			);

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
