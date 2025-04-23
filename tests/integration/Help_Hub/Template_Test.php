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
		$this->set_fn_return('tribe_resource_url','http://example.com/');
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
	public function section_builder(): void {
		// Clear any existing sections
		Section_Builder::clear_sections();

		// Test default section
		Section_Builder::make( 'Getting Started', 'getting_started', 'default' )
			->set_description( 'Learn the basics of The Events Calendar.' )
			->add_link( 'The Events Calendar', '#', '/path/to/tec-icon.svg' )
			->add_link( 'Event Aggregator', '#', '/path/to/ea-icon.svg' )
			->build();

		// Test FAQ section
		Section_Builder::make( 'Frequently Asked Questions', 'faqs', 'faq' )
			->set_description( 'Get quick answers to common questions.' )
			->add_faq(
				'Can I have more than one calendar?',
				'Yes, you can use this feature in the mock environment.',
				'Learn More',
				'#'
			)
			->build();

		// Get all sections and assert against JSON snapshot
		$sections = Section_Builder::get_all_sections();
		$this->assertMatchesJsonSnapshot( json_encode( $sections, JSON_PRETTY_PRINT ) );
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
