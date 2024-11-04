<?php

namespace TEC\Common\Admin\Help_Hub;

use Codeception\TestCase\WPTestCase;
use ReflectionClass;
use RuntimeException;
use TEC\Common\Admin\Help_Hub\Resource_Data\Help_Hub_Data_Interface;
use TEC\Events\Admin\Help_Hub\Resource_Data_Mock;

class Hub_Test extends WPTestCase {

	/**
	 * @var Hub
	 */
	protected $hub;

	/**
	 * @var Help_Hub_Data_Interface
	 */
	protected $data;

	/**
	 * Sets up the test environment before each test.
	 *
	 * @before
	 */
	public function setUpHub(): void {
		// Initialize dependencies using tribe()
		$this->data = new Resource_Data_Mock();

		// Instantiate the Hub
		$this->hub = tribe( Hub::class );

		// Set up the Hub with the data interface
		$this->hub->setup( $this->data );
	}

	/** @test */
	public function it_sets_up_the_hub_correctly() {
		// Assert data is set correctly
		$this->assertSame( $this->data, $this->hub->get_data() );

		// Check if hooks are registered (e.g., 'admin_init' should be registered in this setup)
		$this->assertGreaterThan( 0, has_action( 'admin_init', [ $this->hub, 'generate_iframe_content' ] ) );
		$this->assertGreaterThan( 0, has_action( 'admin_enqueue_scripts', [ $this->hub, 'load_assets' ] ) );
	}

	/** @test */
	public function it_triggers_hooks_during_render() {
		// Set the expected license and opt-in status for testing
		$status = [
			'is_license_valid' => true,
			'is_opted_in'      => true,
		];

		// Stub method for testing; simulating status data return
		add_filter(
			'tec_help_hub_resource_sections',
			function () {
				return [ 'example_section' => 'Example' ];
			}
		);

		// Run render and check hooks
		do_action( 'tec_help_hub_before_render', $this->hub );
		$this->hub->render();
		do_action( 'tec_help_hub_after_render', $this->hub );

		// Assert that hooks are triggered
		$this->assertTrue( did_action( 'tec_help_hub_before_render' ) > 0 );
		$this->assertTrue( did_action( 'tec_help_hub_after_render' ) > 0 );
	}

	/** @test */
	public function it_handles_resource_sections_correctly() {
		// Filter test for resource sections using apply_filters
		$sections = apply_filters( 'tec_help_hub_resource_sections', [ 'section' => 'Test Section' ], $this->data, get_class( $this->data ) );

		$this->assertIsArray( $sections );
		$this->assertArrayHasKey( 'section', $sections );
		$this->assertEquals( 'Test Section', $sections['section'] );
	}

	/** @test */
	public function it_returns_correct_template_variant_for_license_and_opt_in() {
		// Use reflection to access the protected method
		$reflection = new ReflectionClass( Hub::class );
		$method     = $reflection->getMethod( 'get_template_variant' );
		$method->setAccessible( true );

		// Test case where license is valid and opted in
		$variant = $method->invokeArgs( null, [ true, true ] );
		$this->assertEquals( 'has-license-has-consent', $variant );

		// Test case where license is valid but not opted in
		$variant = $method->invokeArgs( null, [ true, false ] );
		$this->assertEquals( 'has-license-no-consent', $variant );

		// Test case where license is not valid
		$variant = $method->invokeArgs( null, [ false, true ] );
		$this->assertEquals( 'no-license', $variant );
	}

	/** @test */
	public function it_returns_correct_telemetry_opt_in_link() {
		// Generate expected link structure
		$expected_link = add_query_arg(
			[
				'page'      => 'tec-events-settings',
				'tab'       => 'general-debugging-tab',
				'post_type' => 'tribe_events',
			],
			admin_url( 'edit.php' )
		);

		// Assert that the generated link matches the expected link
		$this->assertEquals( $expected_link, Hub::get_telemetry_opt_in_link() );
	}

	/** @test */
	public function it_adds_custom_body_classes_correctly() {
		global $current_screen;

		$original_current_screen = $current_screen;

		// Create a mock of the current screen object
		$mock_screen    = (object) [ 'id' => 'tribe_events_page_tec-events-help-hub' ];
		$current_screen = $mock_screen;
		// Simulate custom body class addition based on Help Hub page check
		$classes = $this->hub->add_help_page_body_class( 'existing-class' );

		// Assert that expected custom classes are present
		$this->assertStringContainsString( 'tribe-help', $classes );
		$this->assertStringContainsString( 'tec-help', $classes );
		$this->assertStringContainsString( 'tribe_events_page_tec-events-settings', $classes );

		// Default to the original current screen.
		$current_screen = $original_current_screen;
	}

	/** @test */
	public function it_throws_exception_when_data_is_not_set() {
		// Create a new instance of Hub without setting up data.
		$hub_without_data = tribe( Hub::class );

		$this->expectException( RuntimeException::class );
		$this->expectExceptionMessage( 'The HelpHub data must be set using the setup method before calling this function.' );

		// Attempt to render without setting up data, which should throw an exception.
		$hub_without_data->render();
	}

	/** @test */
	public function it_executes_hooks_during_render() {
		// Setup to capture hooks being executed
		add_action(
			'tec_help_hub_before_render',
			function ( $hub ) use ( &$before_executed ) {
				$before_executed = true;
				$this->assertInstanceOf( Hub::class, $hub );
			},
			10,
			1
		);

		add_action(
			'tec_help_hub_after_render',
			function ( $hub ) use ( &$after_executed ) {
				$after_executed = true;
				$this->assertInstanceOf( Hub::class, $hub );
			},
			10,
			1
		);

		// Call render
		$this->hub->render();

		// Assert hooks were executed
		$this->assertTrue( $before_executed );
		$this->assertTrue( $after_executed );
	}
}
