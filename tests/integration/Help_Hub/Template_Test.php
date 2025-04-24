<?php
/**
 * Tests for Help Hub Templates
 *
 * @since   TBD
 * @package TEC\Common\Admin\Help_Hub
 */

namespace TEC\Common\Admin\Help_Hub;

use Codeception\TestCase\WPTestCase;
use TEC\Common\Tests\Help_Hub\Mock_Resource_Data;
use tad\Codeception\SnapshotAssertions\SnapshotAssertions;
use TEC\Common\Configuration\Configuration;
use Tribe\Tests\Traits\With_Uopz;
use Tribe__Template;

/**
 * Class TemplateTest
 *
 * @since   TBD
 * @package TEC\Common\Admin\Help_Hub
 */
class Template_Test extends WPTestCase {
	use SnapshotAssertions;
	use With_Uopz;

	/**
	 * @var Resource_Data_Mock
	 */
	protected $mock_data;

	/**
	 * @var Hub
	 */
	protected $hub;

	/**
	 * Set up the test environment.
	 *
	 * @before
	 */
	public function setup_enviroment(): void {
		// Initialize dependencies using tribe()
		$this->mock_data = new Mock_Resource_Data();

		// Instantiate necessary dependencies for the Help Hub
		$template = tribe( Tribe__Template::class );
		$config   = tribe( Configuration::class );

		// Instantiate the Hub instance with all dependencies
		$this->hub = new Hub( $this->mock_data, $config, $template );
		$this->set_fn_return( 'tribe_resource_url', 'https://example.com/' );
	}

	/**
	 * @test
	 */
	public function overall_template(): void {
		ob_start();
		$this->hub->render();
		$output = ob_get_clean();

		$this->assertMatchesHtmlSnapshot( $output );
	}

	/**
	 * @test
	 */
	public function section_rendering(): void {
		// Get the sections from the mock data
		$sections = $this->mock_data->create_resource_sections();

		// Test that sections are properly structured
		$this->assertIsArray( $sections );
		$this->assertNotEmpty( $sections );

		// Test that each section has required fields
		foreach ( $sections as $slug => $section ) {
			$this->assertArrayHasKey( 'title', $section );
			$this->assertArrayHasKey( 'slug', $section );
			$this->assertArrayHasKey( 'type', $section );
			$this->assertArrayHasKey( 'description', $section );

			// Validate content based on section type
			if ( $section['type'] === 'faqs' ) {
				$this->assertArrayHasKey( 'faqs', $section );
				$this->assertIsArray( $section['faqs'] );
				foreach ( $section['faqs'] as $faq ) {
					$this->assertArrayHasKey( 'question', $faq );
					$this->assertArrayHasKey( 'answer', $faq );
					$this->assertArrayHasKey( 'link_text', $faq );
					$this->assertArrayHasKey( 'link_url', $faq );
					$this->assertTrue( filter_var( $faq['link_url'], FILTER_VALIDATE_URL ) !== false, 'FAQ link URL must be valid' );
				}
			} else {
				// For non-FAQ sections, check for the items key that matches the section type
				$this->assertArrayHasKey( $section['type'], $section );
				$this->assertIsArray( $section[ $section['type'] ] );
				foreach ( $section[ $section['type'] ] as $item ) {
					$this->assertArrayHasKey( 'title', $item );
					$this->assertArrayHasKey( 'url', $item );
					$this->assertArrayHasKey( 'icon', $item );
					$this->assertTrue( filter_var( $item['url'], FILTER_VALIDATE_URL ) !== false, 'Link URL must be valid' );
				}
			}
		}

		// Test that sections are properly rendered
		ob_start();
		$this->hub->render();
		$output = ob_get_clean();

		$this->assertMatchesHtmlSnapshot( $output );
	}

	/**
	 * @test
	 */
	public function tab_creation(): void {
		$builder = tribe( Tab_Builder::class );
		// Clear any existing tabs
		$builder::clear_tabs();
		$builder::make(
			'tec-help-tab',
			'Support Hub',
			'tec-help-tab',
			'help-hub/support/support-hub'
		)
			->set_class( 'tec-nav__tab--active' )
			->build();

		// Get all tabs
		$tabs = $builder::get_all_tabs();
		$this->assertMatchesJsonSnapshot( json_encode( $tabs, JSON_PRETTY_PRINT ) );
	}
}
