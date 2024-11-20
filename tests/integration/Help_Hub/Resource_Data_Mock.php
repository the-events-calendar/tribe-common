<?php
/**
 * Mock TEC Hub Resource Data Class
 *
 * A lightweight mock implementation of the TEC_Hub_Resource_Data class,
 * used for testing the Help Hub without relying on external resources or dependencies.
 *
 * @since   6.3.2
 * @package TEC\Common\Admin\Help_Hub\Resource_Data
 */

namespace TEC\Common\Admin\Help_Hub;

use TEC\Common\Admin\Help_Hub\Resource_Data\Help_Hub_Data_Interface;

/**
 * Class Mock_TEC_Hub_Resource_Data
 *
 * Provides mock data for testing the Help Hub's functionality.
 *
 * @since   6.3.2
 * @package TEC\Common\Admin\Help_Hub\Resource_Data
 */
class Resource_Data_Mock implements Help_Hub_Data_Interface {

	/**
	 * Mock icons array for testing.
	 *
	 * @var array
	 */
	protected array $icons = [
		'tec_icon'     => '/mock/path/to/tec-icon.svg',
		'ea_icon'      => '/mock/path/to/ea-icon.svg',
		'article_icon' => '/mock/path/to/article-icon.svg',
	];

	/**
	 * Body class array for testing the admin page styling.
	 *
	 * @var array
	 */
	protected array $admin_page_body_classes = [ 'mock_tribe_events_page' ];

	/**
	 * Adds mock body classes for the Help Hub page.
	 *
	 * @param array $classes The current array of body classes.
	 *
	 * @return array Modified array of body classes.
	 */
	public function add_admin_body_classes( array $classes ): array {
		return array_merge( $classes, $this->admin_page_body_classes );
	}

	/**
	 * Registers hooks for the Help Hub Resource Data class.
	 *
	 * This method registers filters and actions required for the Help Hub,
	 * such as adding custom body classes to the Help Hub page.
	 *
	 * @since 6.3.2
	 *
	 * @return void
	 */
	public function add_hooks(): void {
		add_filter( 'tec_help_hub_body_classes', [ $this, 'add_admin_body_classes' ] );
	}

	/**
	 * Creates a mock array of resource sections.
	 *
	 * @return array Mock data for resource sections, including titles and icons.
	 */
	public function create_resource_sections(): array {
		return [
			'getting_started' => [
				[
					'icon'  => $this->get_icon_url( 'tec_icon' ),
					'title' => 'Mock The Events Calendar',
					'link'  => '#',
				],
				[
					'icon'  => $this->get_icon_url( 'ea_icon' ),
					'title' => 'Mock Event Aggregator',
					'link'  => '#',
				],
			],
			'faqs'            => [
				[
					'question'  => 'Can I have more than one calendar?',
					'answer'    => 'Yes, you can use this feature in the mock environment.',
					'link_text' => 'Learn More',
					'link_url'  => '#',
				],
			],
		];
	}

	/**
	 * Retrieves the URL for a specified icon in the mock data.
	 *
	 * @param string $icon_name The name of the icon to retrieve.
	 *
	 * @return string The URL of the specified icon, or an empty string if the icon does not exist.
	 */
	public function get_icon_url( string $icon_name ): string {
		return $this->icons[ $icon_name ] ?? '';
	}

	/**
	 * Mocks the license validity and telemetry opt-in status.
	 *
	 * @return array Contains 'has_valid_license' and 'is_opted_in' status for testing.
	 */
	public function get_license_and_opt_in_status(): array {
		return [
			'has_valid_license' => true,  // Mocked as valid for testing.
			'is_opted_in'       => false, // Mocked as not opted-in for testing.
		];
	}
}
