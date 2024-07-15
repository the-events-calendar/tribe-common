<?php

namespace TEC\Common\Libraries\Uplink;

use tad\Codeception\SnapshotAssertions\SnapshotAssertions;
use TEC\Common\StellarWP\Uplink\Register;
use Tribe__Main;
use Tribe\Tests\Traits\With_Uopz;

class Controller_Test extends \Codeception\TestCase\WPTestCase {
	use SnapshotAssertions;
	use With_Uopz;

	/**
	 * @before
	 */
	public function setup_uplink() {
		// Register Uplink and the test plugin before each test
		tribe( Controller::class )->register_uplink();
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
		// Mock the wp_create_nonce function
		$this->set_fn_return( 'wp_create_nonce', '123456789', false );

		$fields = [
			'tribe-form-content-start' => [],
		];

		// Register license fields and assert the HTML snapshot
		$license_fields = tribe( Controller::class )->register_license_fields( $fields );
		$this->assertMatchesHtmlSnapshot( $license_fields );
	}

	/**
	 * @test
	 */
	public function it_should_add_actions_on_do_register() {
		$controller = tribe( Controller::class );
		$controller->do_register();

		// Assert actions were added
		$this->assertNotFalse( has_action( 'init', [ $controller, 'register_uplink' ] ) );
		$this->assertNotFalse( has_filter( 'tribe_license_fields', [ $controller, 'register_license_fields' ] ) );
	}

	/**
	 * @test
	 */
	public function it_should_remove_actions_on_unregister() {
		$controller = tribe( Controller::class );
		$controller->do_register(); // First add them
		$controller->unregister(); // Then remove them

		// Assert actions were removed
		$this->assertFalse( has_action( 'init', [ $controller, 'register_uplink' ] ) );
		$this->assertFalse( has_filter( 'tribe_license_fields', [ $controller, 'register_license_fields' ] ) );
	}

	/**
	 * @test
	 */
	public function it_should_handle_unregister_before_register() {
		$controller = tribe( Controller::class );
		$controller->unregister(); // Call unregister before register

		// Assert actions were not added
		$this->assertFalse( has_action( 'init', [ $controller, 'register_uplink' ] ) );
		$this->assertFalse( has_filter( 'tribe_license_fields', [ $controller, 'register_license_fields' ] ) );
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
