<?php

namespace TEC\Common\Contracts;

use Codeception\TestCase\WPTestCase;
use TEC\Common\Tests\Service_Providers\Custom_Registration_Action_Provider;
use TEC\Common\Tests\Service_Providers\Generic_Service_Provider;

class ContainerTest extends WPTestCase {
	/**
	 * It should fire a generic action when a service provider is registered
	 *
	 * @test
	 */
	public function should_fire_a_generic_action_when_a_service_provider_is_registered(): void {
		$registered = null;
		add_action( 'tec_container_registered_provider', static function ( string $class ) use ( &$registered ): void {
			$registered = $class;
		} );

		$container = new Container();
		$container->register( Generic_Service_Provider::class );

		$this->assertEquals( Generic_Service_Provider::class, $registered );
	}

	/**
	 * It should fire a custom action if registered provider defines one
	 *
	 * @test
	 */
	public function should_fire_a_custom_action_if_registered_provider_defines_one(): void {
		$registered = null;
		add_action( 'tec_container_registered_provider', static function ( string $class ) use ( &$registered ): void {
			$registered = $class;
		} );
		$custom_action = null;
		add_action( 'custom_action', static function ( string $class ) use ( &$custom_action ): void {
			$custom_action = $class;
		} );

		$container = new Container();
		$container->register( Custom_Registration_Action_Provider::class );

		$this->assertEquals( Custom_Registration_Action_Provider::class, $registered );
		$this->assertEquals( Custom_Registration_Action_Provider::class, $custom_action );
	}

	/**
	 * It should register service providers immediately if fired target action
	 *
	 * @test
	 */
	public function should_register_service_providers_immediately_if_fired_target_action(): void {
		// Check if the action was fired, this indicates that the provider was registered.
		$did_register = null;
		add_action( 'custom_action', static function ( string $class ) use ( &$did_register ): void {
			$did_register = $class;
		} );

		do_action( 'some_action' );

		$container = new Container();
		// The action has already been fired, so the provider should be registered immediately.
		$container->register_on_action( 'some_action', Custom_Registration_Action_Provider::class );

		$this->assertEquals( Custom_Registration_Action_Provider::class, $did_register );
	}

	/**
	 * It should delay registration of service provider to action if not yet fired
	 *
	 * @test
	 */
	public function should_delay_registration_of_service_provider_to_action_if_not_yet_fired(): void {
		// Check if the action was fired, this indicates that the provider was registered.
		$did_register = null;
		add_action( 'custom_action', static function ( string $class ) use ( &$did_register ): void {
			$did_register = $class;
		} );

		$container = new Container();
		// The action has not been fired yet, so the provider should be registered on the action.
		$container->register_on_action( 'some_action', Custom_Registration_Action_Provider::class );

		$this->assertNull( $did_register );

		// Now fire the action.
		do_action( 'some_action' );

		$this->assertEquals( Custom_Registration_Action_Provider::class, $did_register );
	}

	/**
	 * It should not register a provider hooked to an action more than once
	 *
	 * @test
	 */
	public function should_not_register_a_provider_hooked_to_an_action_more_than_once(): void {
		// Check if the action was fired, this indicates that the provider was registered.
		$did_register_times = 0;
		add_action( 'custom_action', static function ( string $class ) use ( &$did_register_times ): void {
			++ $did_register_times;
		} );

		$container = new Container();
		$container->register_on_action( 'some_action', Custom_Registration_Action_Provider::class );

		$this->assertEquals( 0, $did_register_times );

		// Now fire the action a first time.
		do_action( 'some_action' );

		$this->assertEquals( 1, $did_register_times );

		// Now fire the action a second time.
		do_action( 'some_action' );

		$this->assertEquals( 1, $did_register_times, 'The provider should only be registered once.' );

		// Now fire the action a third time.
		do_action( 'some_action' );

		$this->assertEquals( 1, $did_register_times, 'The provider should only be registered once.' );
	}

	/**
	 * It should register a provider hooked to an action once if doing that action
	 *
	 * @test
	 */
	public function should_register_a_provider_hooked_to_an_action_if_doing_that_action(): void {
		// Check if the action was fired, this indicates that the provider was registered.
		$did_register_times = 0;
		add_action( 'custom_action', static function ( string $class ) use ( &$did_register_times ): void {
			++ $did_register_times;
		} );

		$container = new Container();

		// In the process of doing `test_action` register a provider that should be registered on `test_action`.
		$register_provider = static function () use ( $container ): void {
			$container->register_on_action( 'test_action', Custom_Registration_Action_Provider::class );
		};
		add_action( 'test_action', $register_provider );

		// Fire the action a first time.
		do_action( 'test_action' );

		$this->assertEquals( 1, $did_register_times, 'The provider should be registered while doing `test_action`.' );

		// Provided we stop registering the provider on `test_action` it should not be registered again.
		remove_action( 'test_action', $register_provider );
		do_action( 'test_action' );

		$this->assertEquals( 1, $did_register_times, 'The provider should only be registered once.' );
	}

	/**
	 * It should dispatch a class specific action for each registered provider
	 *
	 * @test
	 */
	public function should_dispatch_a_class_specific_action_for_each_registered_provider(): void {
		$generally_registered = [];
		add_action(
			'tec_container_registered_provider',
			function ( string $class, array $aliases ) use ( &$generally_registered ) {
				$generally_registered[] = $class;
			}, 10, 2 );

		$generic_registered_times = 0;
		add_action(
			'tec_container_registered_provider_' . Generic_Service_Provider::class,
			function ( array $aliases ) use ( &$generic_registered_times ) {
				$generic_registered_times ++;
			}, 10, 1 );

		$custom_registered_times = 0;
		add_action(
			'tec_container_registered_provider_' . Custom_Registration_Action_Provider::class,
			function ( array $aliases ) use ( &$custom_registered_times ) {
				$custom_registered_times ++;
			}, 10, 1 );

		$container = new Container();
		$container->register( Generic_Service_Provider::class );
		$container->register( Custom_Registration_Action_Provider::class );

		$this->assertEquals( [
			Generic_Service_Provider::class,
			Custom_Registration_Action_Provider::class,
		], $generally_registered );
		$this->assertEquals( 1, $generic_registered_times );
		$this->assertEquals( 1, $custom_registered_times );
	}

	/**
	 * It should provide aliases when dispatching actions
	 *
	 * @test
	 */
	public function should_provide_aliases_when_dispatching_actions(): void {
		$did_general_registration_times = 0;
		add_action( 'tec_container_registered_provider', function ( string $class, array $aliases ) use ( &$did_general_registration_times ) {
			$did_general_registration_times ++;
			$this->assertEquals( Custom_Registration_Action_Provider::class, $class );
			$this->assertEquals( [ 'custom_one', 'custom.one', 'foo.bar' ], $aliases );
		}, 10, 2 );

		$did_class_registration_times = 0;
		add_action(
			'tec_container_registered_provider_' . Custom_Registration_Action_Provider::class,
			function ( array $aliases ) use ( &$did_class_registration_times ) {
				$did_class_registration_times ++;
				$this->assertEquals( [ 'custom_one', 'custom.one', 'foo.bar' ], $aliases );
			}, 10, 1 );

		$did_custom_registration_times = 0;
		add_action(
			'custom_action',
			function ( string $class, array $aliases ) use ( &$did_custom_registration_times ) {
				$did_custom_registration_times ++;
				$this->assertEquals( Custom_Registration_Action_Provider::class, $class );
				$this->assertEquals( [ 'custom_one', 'custom.one', 'foo.bar' ], $aliases );
			}, 10, 2 );

		$container = new Container();
		$container->register(
			Custom_Registration_Action_Provider::class,
			'custom_one', 'custom.one', 'foo.bar'
		);

		$this->assertEquals( 1, $did_general_registration_times, 'A general registration action should be fired.' );
		$this->assertEquals( 1, $did_class_registration_times, 'A class specific registration action should be fired.' );
		$this->assertEquals( 1, $did_custom_registration_times, 'A custom registration action should be fired.' );
	}
}
