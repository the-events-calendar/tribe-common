<?php

namespace TEC\Common\Libraries\Uplink;

use tad\Codeception\SnapshotAssertions\SnapshotAssertions;
use TEC\Common\StellarWP\Uplink\Config;
use TEC\Common\StellarWP\Uplink\Register;
use TEC\Common\StellarWP\Uplink\Resources\Collection;
use Tribe__Main;
use Tribe\Tests\Traits\With_Uopz;

class Controller_Test extends \Codeception\TestCase\WPTestCase {
	use SnapshotAssertions;
	use With_Uopz;

	/**
	 * @var Controller
	 */
	private $controller;

	/**
	 * @before
	 */
	public function setup_uplink() {
		$this->controller = tribe( Controller::class );
		$this->controller->register_uplink();
		$this->register_plugin();
	}

	public function register_plugin() {
		// Register the test plugin
		Register::plugin(
			'common-test-slug',
			'common-test',
			'1.0.0',
			dirname( __FILE__ ),
			tribe( Tribe__Main::class )
		);
	}

	/**
	 * @test
	 */
	public function it_should_setup_license_fields() {
		// Retrieve the collection and the specific resource
		$collection = Config::get_container()->get( Collection::class );
		$slug = 'common-test-slug';
		$resource = $collection->get( $slug );

		// Update the license key to a known value
		$option_name = $resource->get_license_object()->get_key_option_name();
		$license_key = 'license_key' . $slug;
		update_option( $option_name, $license_key );

		// Assert the license key was updated correctly
		$option_value = get_option( $option_name );
		$this->assertEquals( $option_value, $license_key );

		// Mock the wp_create_nonce function
		$this->set_fn_return( 'wp_create_nonce', '123456789', false );

		// Initialize fields array
		$fields = [
			'tribe-form-content-start' => [],
		];

		// Register license fields and assert the HTML snapshot
		$license_fields = $this->controller->register_license_fields( $fields );
		$this->assertMatchesHtmlSnapshot( $license_fields );
	}

	/**
	 * @test
	 */
	public function it_should_add_actions_on_do_register_and_unregister() {
		$this->controller->do_register();

		// Assert actions were added
		$this->assertNotFalse( has_action( 'init', [ $this->controller, 'register_uplink' ] ) );
		$this->assertNotFalse( has_filter( 'tribe_license_fields', [ $this->controller, 'register_license_fields' ] ) );

		$this->controller->unregister(); // Then remove them

		// Assert actions were removed
		$this->assertFalse( has_action( 'init', [ $this->controller, 'register_uplink' ] ) );
		$this->assertFalse( has_filter( 'tribe_license_fields', [ $this->controller, 'register_license_fields' ] ) );
	}

	/**
	 * @test
	 */
	public function it_should_handle_unregister_before_register() {
		$this->controller->unregister(); // Call unregister before register

		// Assert actions were not added
		$this->assertFalse( has_action( 'init', [ $this->controller, 'register_uplink' ] ) );
		$this->assertFalse( has_filter( 'tribe_license_fields', [ $this->controller, 'register_license_fields' ] ) );
	}

	/**
	 * @test
	 */
	public function it_should_modify_fields_with_filter() {
		$fields = [
			'tribe-form-content-start' => [],
		];

		// Apply the filter
		$modified_fields = apply_filters( 'tribe_license_fields', $fields );

		// Assert that the filter modified the fields array
		$this->assertNotEmpty( $modified_fields );
		$this->assertArrayHasKey( 'stellarwp-uplink_common-test-slug-heading', $modified_fields );
		$this->assertArrayHasKey( 'stellarwp-uplink_common-test-slug', $modified_fields );
	}
}
