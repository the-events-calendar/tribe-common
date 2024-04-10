<?php
/**
 * The base test case to test controllers extending the `TEC\Common\Contracts\Provider\Controller` class.
 */

namespace TEC\Common\Tests\Provider;

use Codeception\TestCase\WPTestCase;
use TEC\Common\Contracts\Provider\Controller;
use Tribe\Tests\Traits\With_Uopz;
use Tribe__Container as Container;
use WP_Hook;

/**
 * Class Controller_Test_Case.
 *
 * @since   5.0.17
 *
 * @package TEC\Common\Tests\Provider;
 * @property string $controller_class The class name of the controller to test.
 *
 * @package TEC\Common\Tests\Provider;
 */
class Controller_Test_Case extends WPTestCase {
	use With_Uopz;

	/**
	 * A reference to the container used to create the controller and run the tests.
	 *
	 * @var Container
	 */
	protected Container $test_services;

	/**
	 * A set of logs collected after the Controller has been registered.
	 * This will only contain logs generated by the Controller itself.
	 *
	 * @var array<string,array>> An array of log entries, each entry is an array with the keys `level`, `message` and
	 *                         `context`.
	 */
	protected array $controller_logs = [];
	/**
	 * A set of logs collected after the Controller has been registered, this will contain all logs, including those
	 * generated by the Controller itself.
	 *
	 * @var array<string,array>> An array of log entries, each entry is an array with the keys `level`, `message` and
	 *                         `context`.
	 */
	protected $logs = [];

	/**
	 * The controller instances created by the test case.
	 *
	 * @var array<Controller>
	 */
	private array $made_controllers = [];

	/**
	 * The original controller instance.
	 */
	private ?Controller $original_controller;
	/**
	 * ${CARET}
	 *
	 * @since TBD
	 *
	 * @var Container
	 */
	private $original_services;

	/**
	 * Unregisters the original controller and sets up the test case.
	 *
	 * @since TBD
	 *
	 * @return void
	 *
	 * @before
	 */
	protected function set_up_controller_test_case(): void {
		// Ensure the test case defines the controller class to test.
		if ( ! property_exists( $this, 'controller_class' ) ) {
			throw new RuntimeException( 'Each Controller test case must define a controller_class property.' );
		}

		// Store a reference to the original Service Locator to be used in `tearDown`.
		$original_services       = tribe();
		$this->original_services = $original_services;

		// Store a reference to the original controller to be used in `tearDown`.
		$this->original_controller = $original_services->get( $this->controller_class );

		// Unhook all the controller instances to avoid callbacks running twice: original and test Controller.
		$this->unregister_all_controller_instances( $this->original_controller );

		// Clone the original Service Locator to be used as a test Service Locator.
		$test_services = clone $original_services;

		// From now on calls to the Service Locator (the `tribe` function) will be redirected to a test Service Locator.
		uopz_set_return(
			'tribe',
			static function ( $key = null ) use ( $test_services ) {
				return $key ? $test_services->get( $key ) : $test_services;
			},
			true
		);
		// Redirect calls to init the container too.
		uopz_set_return( Container::class, 'init', $test_services );
		$this->test_services = $test_services;

		// We should now be working with the test Service Locator.
		$this->assertNotSame( $this->original_controller, $this->test_services );
		$this->assertSame( $this->test_services, tribe() );

		// Register the test Service Locator in the test Service Locator itself.
		$this->test_services->singleton( get_class( $this->test_services ), $this->test_services );
		$this->test_services->singleton( Container::class, $this->test_services );
		$this->test_services->singleton( DI52_Container::class, $this->test_services );

		// In the test Service Locator, the Controller should not be registered.
		$this->test_services->setVar( $this->controller_class . '_registered', false );
		$this->assertFalse( $this->original_controller::is_registered() );

		// Unset the previous, maybe, bound and resolved instance of the controller.
		unset( $this->test_services[ $this->controller_class ] );
	}

	/**
	 * Unregisters all the controllers created by the test case.
	 *
	 * @since TBD
	 *
	 * @return void
	 *
	 * @after
	 */
	protected function tear_down_controller_test_case(): void {
		// Unregister all the controllers created by the test case.
		foreach ( $this->made_controllers as $controller ) {
			$controller->unregister();
			unset( $controller );
		}
		$this->made_controllers = [];

		// We should be still redirecting `tribe()` calls to the test Service Locator.
		$this->assertNotSame( $this->original_services, tribe() );

		// Stop redirecting calls to the test Service Locator.
		uopz_unset_return( 'tribe' );
		// Stop redirecting calls to init the container too.
		uopz_unset_return( Container::class, 'init' );

		// We should now be working with the original Service Locator.
		$this->assertSame( $this->original_services, tribe() );

		// The original controller should not be registered: let's make sure.
		$this->original_services->setVar( $this->controller_class . '_registered', false );
		$this->assertFalse( $this->original_controller::is_registered() );

		/*
		 * Depending on the PHPUnit version, this method might run before of after the `WPTestCase::tearDown` method.
		 * For this reason here we "anticipate" the hook cleanup call the `tearDown` method would run to restore the
		 * hooks and go back to the hook initial state, register the controller, and then backup the hooks.
		 */
		$this->_restore_hooks();
		$this->original_controller->register();
		$this->_backup_hooks();

		$this->original_controller = null;
	}

	/**
	 * Creates a controller instance and sets up a dedicated Service Locator for it.
	 *
	 * In the context of the dedicated Service Locator the controller is not yet registered.
	 *
	 * @since 5.0.17
	 *
	 * @param class-string<Controller>|null $controller_class The controller class to create an instance of, or `null`
	 *                                                        to build from the `controller_class` property.
	 *
	 * @return Controller The controller instance, built on a dedicated testing Service Locator.
	 */
	protected function make_controller( string $controller_class = null ): Controller {
		$controller_class = $controller_class ?: $this->controller_class;

		// From now on, ingest all logging.
		global $wp_filter;
		$wp_filter['tribe_log'] = new WP_Hook(); // phpcs:ignore
		add_action(
			'tribe_log',
			function ( $level, $message, $context ) {
				if ( isset( $context['controller'] ) && $context['controller'] === $this->controller_class ) {
					// Log the controller logs.
					$this->controller_logs[] = [
						'level'   => $level,
						'message' => $message,
						'context' => $context,
					];
				}

				// Log everything.
				$this->logs[] = [
					'level'   => $level,
					'message' => $message,
					'context' => $context,
				];
			},
			10,
			3
		);

		// Due to the previous unset, the container will build this as a prototype.
		$controller = $this->test_services->make( $controller_class );
		$this->assertNotSame( $controller, $this->original_controller );

		$this->made_controllers[] = $controller;

		// Return a yet unregistered Controller instance.
		return $controller;
	}

	/**
	 * It should register and unregister correctly
	 *
	 * This method will run by default to make sure the Controller will clean up after itself upon unregistration.
	 *
	 * @test
	 */
	public function should_register_and_unregister_correctly(): void {
		// Run this now to check the `controller_class` property is set.
		$controller = $this->make_controller();

		$added_filters    = [];
		$controller_class = $this->controller_class;

		$this->watch_added_filters( $added_filters );
		$this->watch_removed_filters( $added_filters );

		$controller->register();
		$controller->unregister();

		$this->assertCount(
			0,
			$added_filters,
			'The controller should have removed all its filters and actions: '
			. PHP_EOL . wp_json_encode( $this->controller_added_filters, JSON_PRETTY_PRINT )
		);
	}

	/**
	 * @before
	 */
	public function reset_logs(): void {
		$this->logs            = [];
		$this->controller_logs = [];
	}

	/**
	 * Asserts the controller logged a message with the specified level and message.
	 *
	 * @since 5.0.17
	 *
	 * @param string $level  The log level.
	 * @param string $needle The message to look for, or a part of it.
	 *
	 * @return void
	 */
	protected function assert_controller_logged( string $level, string $needle ): void {
		$found              = false;
		$correct_level_logs = array_filter( $this->controller_logs, static fn( $log ) => $log['level'] === $level );
		foreach ( $correct_level_logs as $log ) {
			if ( strpos( $log['message'], $needle ) !== false ) {
				$found = true;
				break;
			}
		}
		$this->assertTrue( $found, "Could not find a log with level {$level} and message matching {$needle}" );
	}

	/**
	 * Asserts a message with the specified level and message was logged.
	 *
	 * This assertion will look in all logs, including the ones logged by the controller.
	 *
	 * @since 5.0.17
	 *
	 * @param string $level  The log level.
	 * @param string $needle The message to look for, or a part of it.
	 *
	 * @return void
	 */
	protected function assert_logged( string $level, string $needle ): void {
		$found              = false;
		$correct_level_logs = array_filter( $this->logs, static fn( $log ) => $log['level'] === $level );
		foreach ( $correct_level_logs as $log ) {
			if ( strpos( $log['message'], $needle ) !== false ) {
				$found = true;
				break;
			}
		}
		$this->assertTrue( $found, "Could not find a log with level {$level} and message matching {$needle}" );
	}

	/**
	 * Removes any instance of the controller from any filter or action it might have hooked to.
	 *
	 * @since TBD
	 *
	 * @param Controller $original_controller The controller to unhook.
	 */
	protected function unregister_all_controller_instances( Controller $original_controller ): void {
		// Unregister the original controller to avoid actions and filters hooking twice.
		$original_controller->unregister();

		// We'll run the Controller registration again, so we need to reset the registered flag.
		tribe()->setVar( get_class( $original_controller ) . '_registered', false );

		// The original controller should not be registered at this point.
		$this->assertFalse( $original_controller::is_registered() );

		// Combing all wp_filter to remove any instance of the controller would be too slow.
		// Here we use the controller to let use know what filters it would hook to by
		// intercepting the `add_filter` function.
		$hooked = [];
		uopz_set_return(
			'add_filter',
			function ( $tag, $function_to_add, $priority = 10 ) use ( &$hooked ) {
				if ( ! ( is_array( $function_to_add ) && $function_to_add[0] instanceof Controller ) ) {
					return false;
				}

				$hooked[] = [ $tag, $priority ];
			},
			true
		);
		// The controller will also flag itself as registered in the Service Locator.
		$original_controller->register();
		// No need to mock add_filter anymore.
		uopz_unset_return( 'add_filter' );
		// Comb wp_filters to remove the filters added by **any instance** of the controller.
		global $wp_filter;
		$to_remove = [];
		foreach ( $hooked as [$tag, $priority] ) {
			if ( ! isset( $wp_filter[ $tag ]->callbacks[ $priority ] ) ) {
				continue;
			}
			foreach ( $wp_filter[ $tag ]->callbacks[ $priority ] as $hook ) {
				if ( is_array( $hook['function'] ) && $hook['function'][0] instanceof Controller ) {
					$to_remove[] = [ $tag, $hook['function'], $priority ];
				}
			}
		}
		foreach ( $to_remove as [$tag, $hook, $priority] ) {
			remove_filter( $tag, $hook, $priority );
		}

		// Unregister the original controller again for good measure.
		$original_controller->unregister();

		// The controller flagged itself as registered in the Service Locator, lower the flag.
		$this->original_services->setVar( get_class( $original_controller ) . '_registered', false );

		// The original controller should not be registered at this point.
		$this->assertFalse( $original_controller::is_registered() );
	}

	/**
	 * Watches, and logs, the filters added by the Controller.
	 */
	private function watch_added_filters( array &$added_filters ): void {
		$controller_class = $this->controller_class;

		$this->set_fn_return(
			'add_filter',
			function (
				string $tag,
				callable $callback,
				int $priority = 10,
				int $args = 1
			) use ( $controller_class, &$added_filters ) {
				if ( is_array( $callback ) && $callback[0] instanceof $controller_class ) {
					$added_filters[] = [ $tag, $callback, $priority ];
				}
				add_filter( $tag, $callback, $priority, $args );
			},
			true
		);
	}

	/**
	 * Watches the filters removed by the Controller.
	 */
	private function watch_removed_filters( &$added_filters ): void {
		$controller_class = $this->controller_class;

		$this->set_fn_return(
			'remove_filter',
			function (
				string $tag,
				callable $callback,
				int $priority = 10
			) use ( $controller_class, &$added_filters ) {
				if (
					is_array( $callback )
					&& $callback[0] instanceof $controller_class
				) {
					$found = array_search( [ $tag, $callback, $priority ], $added_filters, true );
					if ( $found !== false ) {
						unset( $added_filters[ $found ] );
					}
				}
				remove_filter( $tag, $callback, $priority );
			},
			true
		);
	}
}
