<?php

namespace Common\Tests\Provider;

use TEC\Common\Tests\Controller\Controller_With_Subcontrollers;
use TEC\Common\Tests\Controller\Sub_Controller_One;
use TEC\Common\Tests\Controller\Sub_Controller_Two;
use TEC\Common\Tests\Provider\Controller_Test_Case;

class Controller_With_Subcontrollers_Test extends Controller_Test_Case {
	protected $controller_class = Controller_With_Subcontrollers::class;
	protected $sub_controller_classes = [
		Sub_Controller_One::class,
		Sub_Controller_Two::class,
	];

	/**
	 * This function will run after WP has loaded, once before any other tests.
	 * Here we're using it to set up the scene like a plugin would do: the plugin loads
	 * and registers its controllers. By the time the first test runs, the main controller
	 * and the two sub-controllers will be registered as they would be in production.
	 */
	public static function wpSetUpBeforeClass(): void {
		tribe_register_provider( Controller_With_Subcontrollers::class );
	}

	/**
	 * This test verifies that the main controller is unregistered in the
	 * `Controller_Test_Case::setUpTestCase` method and with it are unregistered
	 * the sub-controllers.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function test_controller_registration(): void {
		$this->assertFalse(
			has_action( 'main_controller_action' ),
			'At the start of the test the main controller should be unregistered.'
		);
		$this->assertFalse(
			has_filter( 'main_controller_filter' ),
			'At the start of the test the main controller should be unregistered.'
		);
		$this->assertFalse(
			has_action( 'sub_controller_one_action' ),
			'At the start of the test the sub-controller one should be unregistered.'
		);
		$this->assertFalse(
			has_filter( 'sub_controller_one_filter' ),
			'At the start of the test the sub-controller one should be unregistered.'
		);
		$this->assertFalse(
			has_action( 'sub_controller_two_action' ),
			'At the start of the test the sub-controller two should be unregistered.'
		);
		$this->assertFalse(
			has_filter( 'sub_controller_two_filter' ),
			'At the start of the test the sub-controller two should be unregistered.'
		);

		$controller = $this->make_controller();
		$controller->register();
		$sub_controller_one = tribe()->get( Sub_Controller_One::class );
		$sub_controller_two = tribe()->get( Sub_Controller_Two::class );

		$this->assertEquals(
			10,
			has_action( 'main_controller_action', [ $controller, 'on_main_controller_action' ] ),
			'The main controller should be registered with the right priority.'
		);
		$this->assertEquals(
			10,
			has_filter( 'main_controller_filter', [ $controller, 'on_main_controller_filter' ] ),
			'The main controller should be registered with the right priority.'
		);
		$this->assertEquals( 10,
			has_action( 'sub_controller_one_action', [ $sub_controller_one, 'on_sub_controller_one_action' ] ),
			'The sub-controller one should be registered with the right priority.'
		);
		$this->assertEquals( 10,
			has_filter( 'sub_controller_one_filter', [ $sub_controller_one, 'on_sub_controller_one_filter' ] ),
			'The sub-controller one should be registered with the right priority.'
		);
		// Sub Controller Two.
		$this->assertEquals( 10,
			has_action( 'sub_controller_two_action', [ $sub_controller_two, 'on_sub_controller_two_action' ] ),
			'The sub-controller two should be registered with the right priority.'
		);
		$this->assertEquals( 10,
			has_filter( 'sub_controller_two_filter', [ $sub_controller_two, 'on_sub_controller_two_filter' ] ),
			'The sub-controller two should be registered with the right priority.'
		);
	}
}
