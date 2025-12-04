<?php

namespace Common\Tests\Provider;

use PHPUnit\Framework\Assert;
use TEC\Common\Tests\Controller\Simple_Controller;
use TEC\Common\Tests\Provider\Controller_Test_Case;

/*
 * This test should just run the default methods with no failures.
 */

class Simple_Controller_Test extends Controller_Test_Case {
	protected $controller_class = Simple_Controller::class;

	/**
	 * This function will run after WP has loaded, once before any other tests.
	 * Here we're using it to set up the scene like a plugin would do: the plugin loads
	 * and registers its controllers. Among them, this one.
	 */
	public static function wpSetUpBeforeClass(): void {
		// Sanity check.
		Assert::assertFalse(
			has_action( 'simple_controller_test_action' ),
			'By default the controller should not be registered.'
		);
		Assert::assertFalse(
			has_filter( 'simple_controller_test_filter' ),
			'By default the controller should not be registered.'
		);

		// Here do what a plugin would do registering a controller during initialization.
		tribe_register_provider( Simple_Controller::class );

		// The controller should now be registered as part of the initial state the controller test case operates in.
		$controller = tribe( Simple_Controller::class );
		Assert::assertEquals( 10,
			has_action( 'simple_controller_test_action', [ $controller, 'on_simple_controller_test_action' ] ),
			'The controller should be registered.'
		);
		Assert::assertEquals( 10,
			has_filter( 'simple_controller_test_filter', [ $controller, 'on_simple_controller_test_filter' ] ),
			'The controller should be registered.'
		);
	}

	public function test_controller_registration(): void {
		$this->assertFalse(
			has_action( 'simple_controller_test_action' ),
			'At the start of the test the controller should be unregistered.'
		);
		$this->assertFalse(
			has_filter( 'simple_controller_test_filter' ),
			'At the start of the test the controller should be unregistered.'
		);

		$controller = $this->make_controller();
		$controller->register();

		$this->assertEquals( 10,
			has_action( 'simple_controller_test_action', [ $controller, 'on_simple_controller_test_action' ] ),
			'The controller should be registered with the right priority.'
		);
		$this->assertEquals( 10,
			has_filter( 'simple_controller_test_filter', [ $controller, 'on_simple_controller_test_filter' ] ),
			'The controller should be registered with the right priority.'
		);
	}
}
