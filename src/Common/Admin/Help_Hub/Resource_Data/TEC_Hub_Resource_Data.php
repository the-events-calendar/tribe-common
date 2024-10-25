<?php
/**
 * TEC Hub Resource Data Class
 *
 * This file defines the TEC_Hub_Resource_Data class, which implements
 * the Help_Hub_Data_Interface and provides The Events Calendar-specific
 * resources, FAQs, and settings for the Help Hub functionality.
 *
 * @since   TBD
 * @package TEC\Common\Admin\Help_Hub\Resource_Data
 */

namespace TEC\Common\Admin\Help_Hub\Resource_Data;

use TEC\Common\Telemetry\Telemetry;
use Tribe__Main;
use Tribe__PUE__Checker;

/**
 * Class TEC_Hub_Resource_Data
 *
 * Implements the Help_Hub_Data_Interface, offering resources specific
 * to The Events Calendar, including FAQs, common issues, and customization guides.
 *
 * @since   TBD
 * @package TEC\Common\Admin\Help_Hub\Resource_Data
 */
class TEC_Hub_Resource_Data implements Help_Hub_Data_Interface {
	/**
	 * Creates an array of resource sections with relevant content for each section.
	 *
	 * Each section can be filtered independently or as a complete set.
	 *
	 * @return array The filtered resource sections array.
	 */
	public function create_resource_sections(): array {
		$main  = Tribe__Main::instance();
		$icons = $this->get_icon_urls( $main );

		// Initial data structure for resource sections.
		return [
			'getting_started' => [
				[
					'icon'  => $icons['tec_icon_url'],
					'title' => _x( 'The Events Calendar', 'The Events Calendar title', 'tribe-common' ),
					'link'  => 'https://evnt.is/1ap9',
				],
				[
					'icon'  => $icons['ea_icon_url'],
					'title' => _x( 'Event Aggregator', 'Event Aggregator title', 'tribe-common' ),
					'link'  => 'https://evnt.is/1apc',
				],
				[
					'icon'  => $icons['fbar_icon_url'],
					'title' => _x( 'Filter Bar', 'Filter Bar title', 'tribe-common' ),
					'link'  => 'https://evnt.is/1apd',
				],
			],
			'customizations'  => [
				[
					'title' => _x( 'Getting started with customization', 'Customization article', 'tribe-common' ),
					'link'  => 'https://evnt.is/1apf',
					'icon'  => $icons['article_icon_url'],
				],
				[
					'title' => _x( 'Highlighting events', 'Highlighting events article', 'tribe-common' ),
					'link'  => 'https://evnt.is/1apg',
					'icon'  => $icons['article_icon_url'],
				],
				[
					'title' => _x( 'Customizing template files', 'Customizing templates article', 'tribe-common' ),
					'link'  => 'https://evnt.is/1aph',
					'icon'  => $icons['article_icon_url'],
				],
				[
					'title' => _x( 'Customizing CSS', 'Customizing CSS article', 'tribe-common' ),
					'link'  => 'https://evnt.is/1api',
					'icon'  => $icons['article_icon_url'],
				],
			],
			'common_issues'   => [
				[
					'title' => _x( 'Known issues', 'Known issues article', 'tribe-common' ),
					'link'  => 'https://evnt.is/1apj',
					'icon'  => $icons['article_icon_url'],
				],
				[
					'title' => _x( 'Release notes', 'Release notes article', 'tribe-common' ),
					'link'  => 'https://evnt.is/1apk',
					'icon'  => $icons['article_icon_url'],
				],
				[
					'title' => _x( 'Integrations', 'Integrations article', 'tribe-common' ),
					'link'  => 'https://evnt.is/1apl',
					'icon'  => $icons['article_icon_url'],
				],
				[
					'title' => _x( 'Shortcodes', 'Shortcodes article', 'tribe-common' ),
					'link'  => 'https://evnt.is/1apm',
					'icon'  => $icons['article_icon_url'],
				],
			],
			'faqs'            => [
				[
					'question'  => _x( 'Can I have more than one calendar?', 'FAQ more than one calendar question', 'tribe-common' ),
					'answer'    => _x( 'No, but you can use event categories or tags to display certain events.', 'FAQ more than one calendar answer', 'tribe-common' ),
					'link_text' => _x( 'Learn More', 'Link to more than one calendar article', 'tribe-common' ),
					'link_url'  => 'https://evnt.is/1arh',
				],
				[
					'question'  => _x( 'What do I get with Events Calendar Pro?', 'FAQ what is in Calendar Pro question', 'tribe-common' ),
					'answer'    => _x( 'Events Calendar Pro enhances The Events Calendar with additional views, powerful shortcodes, and a host of premium features.', 'FAQ what is in Calendar Pro answer', 'tribe-common' ),
					'link_text' => _x( 'Learn More', 'Link to what is in Calendar Pro article', 'tribe-common' ),
					'link_url'  => 'https://evnt.is/1arj',
				],
				[
					'question'  => _x( 'How do I sell event tickets?', 'FAQ how to sell event tickets question', 'tribe-common' ),
					'answer'    => _x( 'Get started with tickets and RSVPs using our free Event Tickets plugin.', 'FAQ how to sell event tickets answer', 'tribe-common' ),
					'link_text' => _x( 'Learn More', 'Link to what is in Event Tickets article', 'tribe-common' ),
					'link_url'  => 'https://evnt.is/1ark',
				],
				[
					'question'  => _x( 'Where can I find a list of available shortcodes?', 'FAQ where are the shortcodes question', 'tribe-common' ),
					'answer'    => _x( 'Our plugins offer a variety of shortcodes, allowing you to easily embed the calendar, display an event countdown clock, show attendee details, and much more.', 'FAQ where are the shortcodes answer', 'tribe-common' ),
					'link_text' => _x( 'Learn More', 'Link to the shortcodes article', 'tribe-common' ),
					'link_url'  => 'https://evnt.is/1arl',
				],
			],
		];
	}

	/**
	 * Retrieves the URLs for the necessary icons.
	 *
	 * @since TBD
	 *
	 * @param Tribe__Main $main The main object instance to pass for generating resource URLs.
	 *
	 * @return array An associative array containing the URLs for various icons.
	 */
	public function get_icon_urls( Tribe__Main $main ): array {
		return [
			'tec_icon_url'     => tribe_resource_url( 'images/logo/the-events-calendar.svg', false, null, $main ),
			'ea_icon_url'      => tribe_resource_url( 'images/logo/event-aggregator.svg', false, null, $main ),
			'fbar_icon_url'    => tribe_resource_url( 'images/logo/filterbar.svg', false, null, $main ),
			'article_icon_url' => tribe_resource_url( 'images/icons/file-text1.svg', false, null, $main ),
			'stars_icon_url'   => tribe_resource_url( 'images/icons/stars.svg', false, null, $main ),
		];
	}

	/**
	 * Get the license validity and telemetry opt-in status.
	 *
	 * @return array Contains 'is_license_valid' and 'is_opted_in' status.
	 */
	public function get_license_and_opt_in_status(): array {
		$is_license_valid = Tribe__PUE__Checker::is_any_license_valid();
		$common_telemetry = tribe( Telemetry::class );
		$is_opted_in      = $common_telemetry->calculate_optin_status();

		return [
			'is_license_valid' => $is_license_valid,
			'is_opted_in'      => $is_opted_in,
		];
	}
}
