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
		$products = array(
			'the-events-calendar' => array(
				'title' => __( 'The Events Calendar', 'tribe-common' ),
				'slug' => 'the-events-calendar',
				'link' => 'https://theeventscalendar.com/product/wordpress-events-calendar-pro/?utm_campaign=in-app&utm_source=addonspage&utm_medium=wordpress-events-calendar-pro&utm_content=appstoreembedded-1',
				'description' => __( 'Our flagship free calendar', 'tribe-common' ),
				'features' => [
					__( 'Customizable', 'tribe-common' ),
					__( 'Import & export events', 'tribe-common' ),
					__( 'Timezone support', 'tribe-common' ),
					__( 'Multiple views', 'tribe-common' ),
				],
				'image' => 'images/shop/calendar.jpg',
				'logo' => 'images/logo/the-events-calendar.svg',
				'is_installed' => class_exists( 'Tribe__Events__Main' ),
				'free' => true,
				'active_installs' => 800000,
			),
			'event-aggregator' => array(
				'title' => __( 'Event Aggregator', 'tribe-common' ),
				'slug' => 'event-aggregator',
				'link' => 'https://theeventscalendar.com/product/event-aggregator/?utm_campaign=in-app&utm_source=addonspage&utm_medium=event-aggregator&utm_content=appstoreembedded-1',
				'description' => __( 'Massive import functionality for your calendar', 'tribe-common' ),
				'features' => [
					__( 'Feature', 'tribe-common' ),
					__( 'Feature', 'tribe-common' ),
					__( 'Feature', 'tribe-common' ),
					__( 'Feature', 'tribe-common' ),
				],
				'image' => 'images/shop/aggregator.jpg',
				'logo' => 'images/logo/event-aggregator.svg',
				'is_installed' => class_exists( 'Tribe__Events__Aggregator' ) && Tribe__Events__Aggregator::is_service_active(),
				'free' => false,
				'active_installs' => 20000,
			),
			'events-calendar-pro' => array(
				'title' => __( 'Events Calendar PRO', 'tribe-common' ),
				'slug' => 'events-calendar-pro',
				'link' => 'https://theeventscalendar.com/product/wordpress-events-calendar-pro/?utm_campaign=in-app&utm_source=addonspage&utm_medium=wordpress-events-calendar-pro&utm_content=appstoreembedded-1',
				'buy-now' => 'http://m.tri.be/19o4',
				'description' => __( 'Power up your calendar with Pro', 'tribe-common' ),
				'features' => [
					__( 'Premium support', 'tribe-common' ),
					__( 'Recurring events', 'tribe-common' ),
					__( 'Additional views', 'tribe-common' ),
					__( 'Shortcodes', 'tribe-common' ),
				],
				'image' => 'images/shop/pro.jpg',
				'logo' => 'images/logo/events-calendar-pro.svg',
				'is_installed' => class_exists( 'Tribe__Events__Pro__Main' ),
				'free' => false,
				'active_installs' => 100000,
			),
			'event-tickets' => array(
				'title' => __( 'Event Tickets', 'tribe-common' ),
				'slug' => 'event-tickets',
				'link' => null,
				'description' => __( 'Manage ticketing and RSVPs', 'tribe-common' ),
				'features' => [
					__( 'Add tickets and RSVP to any post', 'tribe-common' ),
					__( 'Paypal integration', 'tribe-common' ),
					__( 'Attendee reports', 'tribe-common' ),
					__( 'Customizable ticket template', 'tribe-common' ),
				],
				'image' => 'images/shop/tickets.jpg',
				'logo' => 'images/logo/event-tickets.svg',
				'is_installed' => class_exists( 'Tribe__Tickets__Main' ),
				'free' => true,
				'active_installs' => 20000,
			),
			'event-tickets-plus' => array(
				'title' => __( 'Event Tickets Plus', 'tribe-common' ),
				'slug' => 'event-tickets-plus',
				'link' => 'https://theeventscalendar.com/product/wordpress-event-tickets-plus/?utm_campaign=in-app&utm_source=addonspage&utm_medium=wordpress-event-tickets-plus&utm_content=appstoreembedded-1',
				'buy-now' => 'http://m.tri.be/19o5',
				'description' => __( 'Monetize your events', 'tribe-common' ),
				'features' => [
					__( 'Custom registration fields', 'tribe-common' ),
					__( 'WooCommerce compatibility', 'tribe-common' ),
					__( 'Ticket scanning with mobile app', 'tribe-common' ),
					__( 'Custom attendee registration fields', 'tribe-common' ),
				],
				'image' => 'images/shop/tickets-plus.jpg',
				'logo' => 'images/logo/event-tickets-plus.svg',
				'is_installed' => class_exists( 'Tribe__Tickets_Plus__Main' ),
				'free' => false,
				'active_installs' => 10000,
			),
			'promoter' => array(
				'title' => __( 'Promoter', 'tribe-common' ),
				'slug' => 'promoter',
				'link' => 'https://theeventscalendar.com/product/promoter/?utm_campaign=in-app&utm_source=addonspage&utm_medium=wordpress-events-promoter&utm_content=appstoreembedded-1',
				'buy-now' => 'http://m.tri.be/1acy',
				'description' => __( 'Connect with your community', 'tribe-common' ),
				'features' => [
					__( 'Feature', 'tribe-common' ),
					__( 'Feature', 'tribe-common' ),
					__( 'Feature', 'tribe-common' ),
					__( 'Feature', 'tribe-common' ),
				],
				'image' => 'images/shop/promoter.jpg',
				'logo' => 'images/logo/promoter.svg',
				'is_installed' => tribe( 'promoter.pue' )->has_license_key(),
				'free' => false,
				'active_installs' => 1000,
			),
			'tribe-filterbar' => array(
				'title' => __( 'Filter Bar', 'tribe-common' ),
				'slug' => 'tribe-filterbar',
				'link' => 'https://theeventscalendar.com/product/wordpress-events-filterbar/?utm_campaign=in-app&utm_source=addonspage&utm_medium=wordpress-events-filterbar&utm_content=appstoreembedded-1',
				'buy-now' => 'http://m.tri.be/19o6',
				'description' => __( '[Powerful filtering]', 'tribe-common' ),
				'features' => [
					__( 'Feature', 'tribe-common' ),
					__( 'Feature', 'tribe-common' ),
					__( 'Feature', 'tribe-common' ),
					__( 'Feature', 'tribe-common' ),
				],
				'image' => 'images/shop/filter-bar.jpg',
				'logo' => 'images/logo/filterbar.svg',
				'is_installed' => class_exists( 'Tribe__Events__Filterbar__View' ),
				'free' => false,
				'active_installs' => 20000,
			),
			'events-community' => array(
				'title' => __( 'Community Events', 'tribe-common' ),
				'slug' => 'events-community',
				'link' => 'https://theeventscalendar.com/product/wordpress-community-events/?utm_campaign=in-app&utm_source=addonspage&utm_medium=wordpress-community-events&utm_content=appstoreembedded-1',
				'buy-now' => 'http://m.tri.be/19o7',
				'description' => __( 'Users submit events to your calendar', 'tribe-common' ),
				'features' => [
					__( 'Publishing Control', 'tribe-common' ),
					__( 'Event Submission Form', 'tribe-common' ),
					__( 'Registered User Settings', 'tribe-common' ),
					__( 'Email notifications', 'tribe-common' ),
				],
				'image' => 'images/shop/community.jpg',
				'logo' => 'images/logo/community-events.svg',
				'is_installed' => class_exists( 'Tribe__Events__Community__Main' ),
				'free' => false,
				'active_installs' => 20000,
			),
			'events-community-tickets' => array(
				'title' => __( 'Community Tickets', 'tribe-common' ),
				'slug' => 'events-community-tickets',
				'link' => 'https://theeventscalendar.com/product/community-tickets/?utm_campaign=in-app&utm_source=addonspage&utm_medium=community-tickets&utm_content=appstoreembedded-1',
				'buy-now' => 'http://m.tri.be/19o8',
				'description' => __( 'Run your own events marketplace', 'tribe-common' ),
				'features' => [
					__( 'Users submit events and sell tickets', 'tribe-common' ),
					__( 'Split commission with users', 'tribe-common' ),
					__( '[Feature]', 'tribe-common' ), /* code review: fail this */
					__( '[Feature]', 'tribe-common' ),
				],
				'requires' => _x( 'Event Tickets Plus and Community Events', 'Names of required plugins for Community Tickets', 'tribe-common' ),
				'image' => 'images/shop/community-tickets.jpg',
				'logo' => 'images/logo/community-tickets.svg',
				'is_installed' => class_exists( 'Tribe__Events__Community__Tickets__Main' ),
				'free' => false,
				'active_installs' => 10000,
			),
			'tribe-eventbrite' => array(
				'title' => __( 'Eventbrite Tickets', 'tribe-common' ),
				'slug' => 'tribe-eventbrite',
				'link' => 'https://theeventscalendar.com/product/wordpress-eventbrite-tickets/?utm_campaign=in-app&utm_source=addonspage&utm_medium=wordpress-eventbrite-tickets&utm_content=appstoreembedded-1',
				'buy-now' => 'http://m.tri.be/19o9',
				'description' => sprintf(
					__( 'Create & sell tickets using the power of Eventbrite.', 'tribe-common' ),
					'<a href="http://www.eventbrite.com/r/etp">',
					'</a>'
				),
				'features' => [
					__( 'Feature', 'tribe-common' ),
					__( 'Feature', 'tribe-common' ),
					__( 'Feature', 'tribe-common' ),
					__( 'Feature', 'tribe-common' ),
				],
				'image' => 'images/shop/eventbrite.jpg',
				'logo' => 'images/logo/eventbrite-tickets.svg',
				'is_installed' => class_exists( 'Tribe__Events__Tickets__Eventbrite__Main' ),
				'free' => false,
				'active_installs' => 20000,
			),
			'image-widget-plus' => array(
				'title' => __( 'Image Widget Plus', 'tribe-common' ),
				'slug' => 'image-widget-plus',
				'link' => 'http://m.tri.be/19nv',
				'buy-now' => 'http://m.tri.be/19oa',
				'description' => __( 'Amp up your images.', 'tribe-common' ),
				'features' => [
					__( 'Feature', 'tribe-common' ),
					__( 'Feature', 'tribe-common' ),
					__( 'Feature', 'tribe-common' ),
					__( 'Feature', 'tribe-common' ),
				],
				'image' => 'images/shop/image-widget-plus.jpg',
				'logo' => 'images/logo/image-widget-plus.svg',
				'is_installed' => class_exists( 'Tribe__Image__Plus__Main' ),
				'free' => false,
				'active_installs' => 2500,
			),
			'virtual-events' => array(
				'title' => __( 'Virtual Events', 'tribe-common' ),
				'slug' => 'events-virtual',
				'link' => 'http://m.tri.be/19nv', /* CODE REVIEW: this link needs updating */
				'buy-now' => 'http://m.tri.be/19oa', /* CODE REVIEW: this link needs updating */
				'description' => __( '[ADD DESCRIPTION]', 'tribe-common' ), /* CODE REVIEW: this desciption needs updating */
				'features' => [
					__( 'Feature', 'tribe-common' ),
					__( 'Feature', 'tribe-common' ),
					__( 'Feature', 'tribe-common' ),
					__( 'Feature', 'tribe-common' ),
				],
				'image' => 'images/shop/virtual-events.jpg',
				'logo' => 'images/logo/virtual-events.svg',
				'is_installed' => defined( 'EVENTS_VIRTUAL_FILE' ),
				'free' => false,
				'active_installs' => 2500,
			),
		);

		return $products;
	}
}
