<?php
/**
 * Mock Resource Data Class
 *
 * A lightweight mock implementation of the Help_Hub_Data_Interface,
 * used for testing the Help Hub without relying on external resources or dependencies.
 *
 * @since   TBD
 * @package TEC\Common\Tests\Help_Hub
 */

namespace TEC\Common\Tests\Help_Hub;

use TEC\Common\Admin\Help_Hub\Resource_Data\Help_Hub_Data_Interface;
use TEC\Common\Admin\Help_Hub\Section_Builder\Link_Section_Builder;
use TEC\Common\Admin\Help_Hub\Section_Builder\FAQ_Section_Builder;
use TEC\Common\Telemetry\Telemetry;
use Tribe__PUE__Checker;

/**
 * Class Mock_Resource_Data
 *
 * Provides mock data for testing the Help Hub's functionality.
 *
 * @since   TBD
 * @package TEC\Common\Tests\Help_Hub
 */
class Mock_Resource_Data implements Help_Hub_Data_Interface {

	/**
	 * Mock icons array for testing.
	 *
	 * @since TBD
	 * @var array
	 */
	protected array $icons = [
		'tec_icon'     => '/mock/path/to/tec-icon.svg',
		'ea_icon'      => '/mock/path/to/ea-icon.svg',
		'fbar_icon'    => '/mock/path/to/fbar-icon.svg',
		'article_icon' => '/mock/path/to/article-icon.svg',
		'stars_icon'   => '/mock/path/to/stars-icon.svg',
		'chat_icon'    => '/mock/path/to/chat-icon.svg',
	];

	/**
	 * Body class array for testing the admin page styling.
	 *
	 * @since TBD
	 * @var array
	 */
	protected array $admin_page_body_classes = [ 'mock_tribe_events_page' ];

	/**
	 * Registers hooks for the Help Hub Resource Data class.
	 *
	 * This method registers filters and actions required for the Help Hub,
	 * such as adding custom body classes to the Help Hub page.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function add_hooks(): void {
		add_filter( 'tec_help_hub_body_classes', [ $this, 'add_admin_body_classes' ] );
		add_filter( 'tec_help_hub_resources_description', [ $this, 'add_resources_description' ] );
		add_filter( 'tec_help_hub_support_title', [ $this, 'add_support_description' ] );
	}

	/**
	 * Add resources description
	 *
	 * @since TBD
	 *
	 * @param string $description The default resources description.
	 *
	 * @return string The modified resources description.
	 */
	public function add_resources_description( $description ) {
		return _x( 'Mock help resources for testing the Help Hub functionality.', 'Help Hub resources description', 'event-tickets' );
	}

	/**
	 * Add support description
	 *
	 * @since TBD
	 *
	 * @param string $title The default support title.
	 *
	 * @return string The modified support title.
	 */
	public function add_support_description( $title ) {
		return _x( 'Mock support resources for testing the Help Hub functionality.', 'Help Hub support title', 'event-tickets' );
	}

	/**
	 * Adds custom body classes for the Help Hub page.
	 *
	 * This method allows the addition of `$admin_page_body_classes` to
	 * the list of body classes for the Help Hub page.
	 *
	 * @since TBD
	 *
	 * @param array $classes The current array of body classes.
	 *
	 * @return array Modified array of body classes.
	 */
	public function add_admin_body_classes( array $classes ): array {
		return array_merge( $classes, $this->admin_page_body_classes );
	}

	/**
	 * Creates an array of resource sections with relevant content for each section.
	 *
	 * Each section can be filtered independently or as a complete set.
	 *
	 * @since TBD
	 *
	 * @return array The filtered resource sections array.
	 */
	public function create_resource_sections(): array {
		/** @var Link_Section_Builder $link_builder */
		$link_builder = tribe( Link_Section_Builder::class );

		/** @var FAQ_Section_Builder $faq_builder */
		$faq_builder = tribe( FAQ_Section_Builder::class );

		// Build all sections.
		$this->build_getting_started_section( $link_builder );
		$this->build_customizations_section( $link_builder );
		$this->build_common_issues_section( $link_builder );
		$this->build_faq_section( $faq_builder );

		// Get all built sections.
		return array_merge(
			$link_builder::get_all_sections(),
			$faq_builder::get_all_sections()
		);
	}

	/**
	 * Builds the Getting Started section.
	 *
	 * @since TBD
	 *
	 * @param Link_Section_Builder $builder The section builder instance.
	 *
	 * @return void
	 */
	protected function build_getting_started_section( Link_Section_Builder $builder ): void {
		$builder::make(
			_x( 'Getting Started', 'Section title', 'event-tickets' ),
			'getting_started'
		)
			->set_description( _x( 'Learn the basics of The Events Calendar.', 'Section description', 'event-tickets' ) )
			->add_link(
				_x( 'Mock The Events Calendar', 'The Events Calendar title', 'event-tickets' ),
				'https://example.com/tec',
				$this->get_icon_url( 'tec_icon' )
			)
			->add_link(
				_x( 'Mock Event Aggregator', 'Event Aggregator title', 'event-tickets' ),
				'https://example.com/ea',
				$this->get_icon_url( 'ea_icon' )
			)
			->add_link(
				_x( 'Mock Filter Bar', 'Filter Bar title', 'event-tickets' ),
				'https://example.com/fbar',
				$this->get_icon_url( 'fbar_icon' )
			)
			->build();
	}

	/**
	 * Builds the Customizations section.
	 *
	 * @since TBD
	 *
	 * @param Link_Section_Builder $builder The section builder instance.
	 *
	 * @return void
	 */
	protected function build_customizations_section( Link_Section_Builder $builder ): void {
		$builder::make(
			_x( 'Customizations', 'Section title', 'event-tickets' ),
			'customizations'
		)
			->set_description( _x( 'Tips and tricks on making your calendar just the way you want it.', 'Section description', 'event-tickets' ) )
			->add_link(
				_x( 'Mock Getting started with customization', 'Customization article', 'event-tickets' ),
				'https://example.com/customization',
				$this->get_icon_url( 'article_icon' )
			)
			->add_link(
				_x( 'Mock Highlighting events', 'Highlighting events article', 'event-tickets' ),
				'https://example.com/highlighting',
				$this->get_icon_url( 'article_icon' )
			)
			->build();
	}

	/**
	 * Builds the Common Issues section.
	 *
	 * @since TBD
	 *
	 * @param Link_Section_Builder $builder The section builder instance.
	 *
	 * @return void
	 */
	protected function build_common_issues_section( Link_Section_Builder $builder ): void {
		$builder::make(
			_x( 'Common Issues', 'Section title', 'event-tickets' ),
			'common_issues'
		)
			->set_description(
				sprintf(
				/* translators: %s is the link to the AI Chatbot */
					_x( 'Having trouble? Find solutions to common issues or ask our %s.', 'Common issues section description', 'event-tickets' ),
					'<a href="javascript:void(0)" data-tab-target="tec-help-tab">' . _x( 'AI Chatbot', 'AI Chatbot link text', 'event-tickets' ) . '</a>'
				)
			)
			->add_link(
				_x( 'Mock Known issues', 'Known issues article', 'event-tickets' ),
				'https://example.com/issues',
				$this->get_icon_url( 'article_icon' )
			)
			->add_link(
				_x( 'Mock Release notes', 'Release notes article', 'event-tickets' ),
				'https://example.com/releases',
				$this->get_icon_url( 'article_icon' )
			)
			->build();
	}

	/**
	 * Builds the FAQ section.
	 *
	 * @since TBD
	 *
	 * @param FAQ_Section_Builder $builder The section builder instance.
	 *
	 * @return void
	 */
	protected function build_faq_section( FAQ_Section_Builder $builder ): void {
		$builder::make( 'FAQ', 'faq' )
			->set_description( _x( 'Frequently Asked Questions', 'FAQ section description', 'event-tickets' ) )
			->add_faq(
				_x( 'Can I have more than one calendar?', 'FAQ more than one calendar question', 'event-tickets' ),
				_x( 'Yes, you can use this feature in the mock environment.', 'FAQ more than one calendar answer', 'event-tickets' ),
				_x( 'Learn More', 'Link to more than one calendar article', 'event-tickets' ),
				'https://example.com/multiple-calendars'
			)
			->add_faq(
				_x( 'What do I get with Events Calendar Pro?', 'FAQ what is in Calendar Pro question', 'event-tickets' ),
				_x( 'Events Calendar Pro enhances The Events Calendar with additional views, powerful shortcodes, and a host of premium features.', 'FAQ what is in Calendar Pro answer', 'event-tickets' ),
				_x( 'Learn More', 'Link to what is in Calendar Pro article', 'event-tickets' ),
				'https://example.com/calendar-pro'
			)
			->build();
	}

	/**
	 * Retrieves the URL for a specified icon.
	 *
	 * @since TBD
	 *
	 * @param string $icon_name The name of the icon to retrieve.
	 *
	 * @return string The URL of the specified icon, or an empty string if the icon does not exist.
	 */
	public function get_icon_url( string $icon_name ): string {
		return $this->icons[ $icon_name ] ?? '';
	}

	/**
	 * Get the license validity and telemetry opt-in status.
	 *
	 * @since TBD
	 *
	 * @return array Contains 'has_valid_license' and 'is_opted_in' status.
	 */
	public function get_license_and_opt_in_status(): array {
		$has_valid_license = Tribe__PUE__Checker::is_any_license_valid();
		$common_telemetry  = tribe( Telemetry::class );
		$is_opted_in       = $common_telemetry->calculate_optin_status();

		return [
			'has_valid_license' => $has_valid_license,
			'is_opted_in'       => $is_opted_in,
		];
	}
}
