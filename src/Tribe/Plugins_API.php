<?php

class Tribe__Plugins_API {
	/**
	 * Static Singleton Factory Method
	 *
	 * @since 4.5.3
	 *
	 * @return Tribe__Plugins_API
	 */
	public static function instance() {
		return tribe( 'plugins.api' );
	}

	/**
	 * Get product info
	 *
	 * @since 4.5.3
	 *
	 * @return array
	 */
	public function get_products() {
		$products = [
			'the-events-calendar'      => [
				'title'           => __( 'The Events Calendar', 'tribe-common' ),
				'slug'            => 'the-events-calendar',
				'link'            => 'https://evnt.is/1ai-',
				'plugin-dir'	  => 'the-events-calendar',
				'main-file'       => 'the-events-calendar.php',
				'description'     => __( 'Our flagship free calendar', 'tribe-common' ),
				'description-help' => __( 'The #1 calendar for WordPress', 'tribe-common' ),
				'features'        => [
					__( 'Customizable', 'tribe-common' ),
					__( 'Import & export events', 'tribe-common' ),
					__( 'Timezone support', 'tribe-common' ),
					__( 'Multiple views', 'tribe-common' ),
				],
				'image'           => 'images/shop/calendar.jpg',
				'logo'            => 'images/logo/the-events-calendar.svg',
				'is_installed'    => class_exists( 'Tribe__Events__Main' ),
				'free'            => true,
				'active_installs' => 800000,
			],
			'event-aggregator'         => [
				'title'           => __( 'Event Aggregator', 'tribe-common' ),
				'slug'            => 'event-aggregator',
				'link'            => 'https://evnt.is/1aj0',
				'plugin-dir'	  => '',
				'main-file'       => '',
				'description'     => __( 'Automated imports for your calendar', 'tribe-common' ),
				'description-help' => __( 'Import events from Meetup, Eventbrite, iCal, Google Calendar, and more.', 'tribe-common' ),
				'features'        => [
					__( 'Schedule automated imports', 'tribe-common' ),
					__( 'Customizable', 'tribe-common' ),
					__( 'Works with Google Calendar, Meetup, and more', 'tribe-common' ),
					__( 'Refine by date, location, or keyword', 'tribe-common' ),
				],
				'image'           => 'images/shop/aggregator.jpg',
				'logo'            => 'images/logo/event-aggregator.svg',
				'is_installed'    => class_exists( 'Tribe__Events__Aggregator' ) && Tribe__Events__Aggregator::is_service_active(),
				'free'            => false,
				'active_installs' => 20000,
			],
			'events-calendar-pro'      => [
				'title'           => __( 'Events Calendar Pro', 'tribe-common' ),
				'slug'            => 'events-calendar-pro',
				'link'            => 'https://evnt.is/1ai-',
				'plugin-dir'	  => 'events-calendar-pro',
				'main-file'       => 'events-calendar-pro.php',
				'description'     => __( 'Power up your calendar with Pro', 'tribe-common' ),
				'description-help' => __( 'The #1 calendar for WordPress', 'tribe-common' ),
				'features'        => [
					__( 'Premium support', 'tribe-common' ),
					__( 'Recurring events & series', 'tribe-common' ),
					__( 'Additional views', 'tribe-common' ),
					__( 'Shortcodes', 'tribe-common' ),
					__( 'Duplicate events', 'tribe-common' ),
				],
				'image'           => 'images/shop/pro.jpg',
				'logo'            => 'images/logo/events-calendar-pro.svg',
				'is_installed'    => class_exists( 'Tribe__Events__Pro__Main' ),
				'free'            => false,
				'active_installs' => 100000,
			],
			'event-tickets'            => [
				'title'           => __( 'Event Tickets', 'tribe-common' ),
				'slug'            => 'event-tickets',
				'link'            => 'https://evnt.is/1aj1',
				'plugin-dir'	  => 'event-tickets',
				'main-file'       => 'event-tickets.php',
				'description'     => __( 'Manage ticketing and RSVPs', 'tribe-common' ),
				'description-help' => __( 'Collect RSVPs and sell tickets', 'tribe-common' ),
				'features'        => [
					__( 'Add tickets and RSVP to any post', 'tribe-common' ),
					__( 'Paypal integration', 'tribe-common' ),
					__( 'Attendee reports', 'tribe-common' ),
					__( 'Customizable ticket template', 'tribe-common' ),
				],
				'image'           => 'images/shop/tickets.jpg',
				'logo'            => 'images/logo/event-tickets.svg',
				'is_installed'    => class_exists( 'Tribe__Tickets__Main' ),
				'free'            => true,
				'active_installs' => 20000,
			],
			'event-tickets-plus'       => [
				'title'           => __( 'Event Tickets Plus', 'tribe-common' ),
				'slug'            => 'event-tickets-plus',
				'link'            => 'http://evnt.is/1aj1',
				'plugin-dir'	  => 'event-tickets-plus',
				'main-file'       => 'event-tickets-plus.php',
				'description'     => __( 'Monetize your events', 'tribe-common' ),
				'description-help' => __( 'Collect RSVPs and sell tickets', 'tribe-common' ),
				'features'        => [
					__( 'Custom registration fields', 'tribe-common' ),
					__( 'WooCommerce compatibility', 'tribe-common' ),
					__( 'Ticket scanning with mobile app', 'tribe-common' ),
					__( 'Custom attendee registration fields', 'tribe-common' ),
				],
				'image'           => 'images/shop/tickets-plus.jpg',
				'logo'            => 'images/logo/event-tickets-plus.svg',
				'is_installed'    => class_exists( 'Tribe__Tickets_Plus__Main' ),
				'free'            => false,
				'active_installs' => 10000,
			],
			'promoter'                 => [
				'title'           => __( 'Promoter', 'tribe-common' ),
				'slug'            => 'promoter',
				'link'            => 'https://evnt.is/1acy',
				'plugin-dir'	  => '',
				'main-file'       => '',
				'description'     => __( 'An email marketing solution for events and the people running them', 'tribe-common' ),
				'description-help' => __( 'Email marketing to promote your events', 'tribe-common' ),
				'features'        => [
					__( 'Automate email touchpoints', 'tribe-common' ),
					__( 'Customize email templates', 'tribe-common' ),
					__( 'Streamline your email process', 'tribe-common' ),
					__( 'Segment your attendee lists', 'tribe-common' ),
				],
				'image'           => 'images/shop/promoter.jpg',
				'logo'            => 'images/logo/promoter.svg',
				'is_installed'    => tribe( 'promoter.pue' )->has_license_key(),
				'free'            => false,
				'active_installs' => 1000,
			],
			'tribe-filterbar'          => [
				'title'           => __( 'Filter Bar', 'tribe-common' ),
				'slug'            => 'tribe-filterbar',
				'link'            => 'https://evnt.is/19o6',
				'plugin-dir'	  => 'the-events-calendar-filterbar',
				'main-file'       => 'the-events-calendar-filter-view.php',
				'description'     => __( 'Help users find exactly the right event', 'tribe-common' ),
				'description-help' => __( 'Allow users to search for events by category, tag, venue, organizer, day of the week, time of day, and price.', 'tribe-common' ),
				'features'        => [
					__( 'Configurable set of filters', 'tribe-common' ),
					__( 'Horizontal or vertical', 'tribe-common' ),
					__( 'Filter category, price, and more', 'tribe-common' ),
					__( 'Filter distance (for Events Calendar Pro)', 'tribe-common' ),
				],
				'image'           => 'images/shop/filter-bar.jpg',
				'logo'            => 'images/logo/filterbar.svg',
				'is_installed'    => class_exists( 'Tribe__Events__Filterbar__View' ),
				'free'            => false,
				'active_installs' => 20000,
			],
			'events-community'         => [
				'title'            => __( 'Community', 'tribe-common' ),
				'slug'            => 'events-community',
				'link'            => 'https://evnt.is/19o7',
				'plugin-dir'	  => 'the-events-calendar-community-events',
				'main-file'       => 'tribe-community-events.php',
				'description'      => __( 'Users submit events and sell tickets', 'tribe-common' ),
				'description-help' => __( 'Enable 3rd party event submissions.', 'tribe-common' ),
				'features'        => [
					__( 'Publishing Control', 'tribe-common' ),
					__( 'Users Submit Events and Sell Tickets', 'tribe-common' ),
					__( 'Split Commission with Users', 'tribe-common' ),
					__( 'Registered User Settings', 'tribe-common' ),
					__( 'Email notifications', 'tribe-common' ),
				],
				'image'           => 'images/shop/community.jpg',
				'logo'            => 'images/logo/community-events.svg',
				'is_installed'    => class_exists( 'Tribe__Events__Community__Main' ),
				'free'            => false,
				'active_installs' => 20000,
			],
			'tribe-eventbrite'         => [
				'title'           => __( 'Eventbrite Tickets', 'tribe-common' ),
				'slug'            => 'tribe-eventbrite',
				'link'            => 'https://evnt.is/19o9',
				'plugin-dir'	  => 'the-events-calendar-eventbrite-tickets',
				'main-file'       => 'tribe-eventbrite.php',
				'description'     => __( 'Unite the power of TEC with the ticketing of Eventbrite', 'tribe-common' ),
				'description-help' => __( 'Create Eventbrite tickets and events right from your WordPress dashboard.', 'tribe-common' ),
				'features'        => [
					__( 'Manage tickets from WordPress', 'tribe-common' ),
					__( 'Ticket availability automatically updates', 'tribe-common' ),
					__( 'Integrated with your events on Eventbrite', 'tribe-common' ),
					__( 'Automatically import your events', 'tribe-common' ),
				],
				'image'           => 'images/shop/eventbrite.jpg',
				'logo'            => 'images/logo/eventbrite-tickets.svg',
				'is_installed'    => class_exists( 'Tribe__Events__Tickets__Eventbrite__Main' ),
				'free'            => false,
				'active_installs' => 20000,
			],
			'image-widget-plus'        => [
				'title'           => __( 'Image Widget Plus', 'tribe-common' ),
				'slug'            => 'image-widget-plus',
				'link'            => 'https://evnt.is/19nv',
				'plugin-dir'	  => 'image-widget-plus',
				'main-file'       => 'image-widget-plus.php',
				'description'     => __( 'Beautiful display options for your favorite photos.', 'tribe-common' ),
				'description-help' => __( 'Beautiful display options for your favorite photos.', 'tribe-common' ),
				'features'        => [
					__( 'Multi-Image Support', 'tribe-common' ),
					__( 'Lightbox', 'tribe-common' ),
					__( 'Slideshow', 'tribe-common' ),
					__( 'Random Images', 'tribe-common' ),
				],
				'image'           => 'images/shop/image-widget-plus.jpg',
				'logo'            => 'images/logo/image-widget-plus.svg',
				'is_installed'    => class_exists( 'Tribe__Image__Plus__Main' ),
				'free'            => false,
				'active_installs' => 2500,
			],
			'event-schedule-manager'    => [
				'title'            => __( 'Event Schedule Manager', 'tribe-common' ),
				'slug'             => 'event-schedule-manager',
				'link'             => 'https://evnt.is/1bdm',
				'plugin-dir'       => 'event-schedule-manager',
				'main-file'        => 'event-schedule-manager.php',
				'description'      => __( 'Easily create the perfect schedule for your event and display it on any post type.', 'tribe-common' ),
				'description-help' => __( 'Easily create the perfect schedule for your event and display it on any post type.', 'tribe-common' ),
				'features'         => [
					__( 'Multiple tracks support', 'tribe-common' ),
					__( 'Speakers and sponsors', 'tribe-common' ),
					__( 'Works on any post type', 'tribe-common' ),
					__( 'Shortcodes and blocks', 'tribe-common' ),
				],
				'image'            => 'images/shop/event-schedule-manager.png',
				'logo'             => 'images/logo/event-schedule-manager.svg',
				'is_installed'     => defined( 'CONFERENCE_SCHEDULE_PRO_FILE' ),
				'free'             => false,
				'active_installs'  => 0,
			],
		];

		return $products;
	}
}
