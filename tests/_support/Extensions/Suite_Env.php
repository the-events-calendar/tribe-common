<?php
/**
 * An extension to make it easier to set up the environment for specific suites.
 *
 * Usage:
 * 1. Include the extension in the main Codeception configuration file (e.g. `codeception.dist.yml`):
 * ```yaml
 * extensions:
 *     enabled:
 *       - TEC\Common\Tests\Extensions\Suite_Env
 * ```
 *
 * 2. In the main bootstrap file, not the suite ones, define one or more callbacks to run at each suite event:
 * ```php
 * <?php
 *
 * use TEC\Common\Tests\Extensions\Suite_Env;
 *
 * // This will run before any module is loaded and initialized.
 * Suite_Env::module_init( 'my_suite_name', fn() => putenv( 'MY_MODULE_INIT=1' ) );
 *
 * // This will run after modules and helpers are initialized and loaded, before the actor is loaded.
 * Suite_Env::init( 'my_suite_name', fn() => putenv( 'MY_INIT=1' ) );
 *
 * // This will run before the whole suite is executed.
 * Suite_Env::before( 'my_suite_name', fn() => putenv( 'MY_BEFORE=1' ) );
 *
 * // This will run after the whole suite has been executed.
 * Suite_Env::after( 'my_suite_name', fn() => putenv( 'MY_AFTER=1' ) );
 * ```
 *
 * For each method, you can set multiple callbacks, each one has to be a callable, to execute in sequence:
 * ```php
 * <?php
 *
 * use TEC\Common\Tests\Extensions\Suite_Env;
 *
 * $one = fn() => putenv( 'MY_ONE=1' );
 * $three = fn() => putenv( 'MY_THREE=1' );
 *
 * Suite_Env::module_init( 'my_suite_name', $one, Some_Class::some_method, $three );
 * ```
 */

namespace TEC\Common\Tests\Extensions;

use Codeception\Event\SuiteEvent;
use Codeception\Events;
use Codeception\Extension;

/**
 * Class Suite_Env.
 *
 * @since   TBD
 *
 * @package TEC\Common\Tests\Extensions;
 */
class Suite_Env extends Extension {
	static $events = [
		Events::MODULE_INIT  => 'on_module_init',
		Events::SUITE_INIT   => 'on_suite_init',
		Events::SUITE_BEFORE => 'on_suite_before',
		Events::SUITE_AFTER  => 'on_suite_after',
	];

	/**
	 * A map from suite names to the callables that will run on the `SUITE_INIT` event.
	 *
	 * @since TBD
	 *
	 * @var array<string,callable[]>
	 */
	private static $module_init_callbacks = [];

	/**
	 * A map from suite names to the callables that will run on the `SUITE_INIT` event.
	 *
	 * @since TBD
	 *
	 * @var array<string,callable[]>
	 */
	private static $init_callbacks = [];

	/**
	 * A map from suite names to the callables that will run on the `SUITE_BEFORE` event.
	 *
	 * @since TBD
	 *
	 * @var array<string,callable[]>
	 */
	private static $before_callbacks = [];

	/**
	 * A map from suite names to the callables that will run on the `SUITE_AFTER` event.
	 *
	 * @since TBD
	 *
	 * @var array<string,callable[]>
	 */
	private static $after_callbacks = [];

	/**
	 * Registers the suite module init event callbacks.
	 *
	 * @since TBD
	 *
	 * @param string   $suite_name   The name of the suite to register the callbacks for.
	 * @param callable ...$callbacks One or more callbacks to run during the suite module initialization event.
	 *
	 * @return void
	 */
	public static function module_init( string $suite_name, callable ...$callbacks ): void {
		static::$module_init_callbacks[ $suite_name ] = $callbacks;
	}

	/**
	 * Registers the suite init event callbacks.
	 *
	 * @since TBD
	 *
	 * @param string   $suite_name   The name of the suite to register the callbacks for.
	 * @param callable ...$callbacks One or more callbacks to run during the suite initialization event.
	 *
	 * @return void
	 */
	public static function init( string $suite_name, callable ...$callbacks ): void {
		static::$init_callbacks[ $suite_name ] = $callbacks;
	}

	/**
	 * Registers the suite before event callbacks.
	 *
	 * @since TBD
	 *
	 * @param string   $suite_name   The name of the suite to register the callbacks for.
	 * @param callable ...$callbacks One or more callbacks to run during the suite before event.
	 *
	 * @return void
	 */
	public static function before( string $suite_name, callable ...$callbacks ): void {
		static::$before_callbacks[ $suite_name ] = $callbacks;
	}

	/**
	 * Registers the suite after event callbacks.
	 *
	 * @since TBD
	 *
	 * @param string   $suite_name         The name of the suite to register the callbacks for.
	 * @param callable ...$setup_callbacks One or more callbacks to run during the suite after event.
	 *
	 * @return void
	 */
	public static function after( string $suite_name, callable ...$setup_callbacks ): void {
		static::$after_callbacks[ $suite_name ] = $setup_callbacks;
	}

	/**
	 * Runs the suite module init callbacks, if any.
	 *
	 * @since TBD
	 *
	 * @param SuiteEvent $event The suite initialization event.
	 *
	 * @return void The method will run the suite init callbacks, if set.
	 */
	public function on_module_init( SuiteEvent $event ): void {
		$suite_name = $event->getSuite()->getName();

		if ( ! isset( static::$module_init_callbacks[ $suite_name ] ) ) {
			return;
		}

		foreach ( static::$module_init_callbacks[ $suite_name ] as $callback ) {
			$callback();
		}
	}

	/**
	 * Runs the suite init callbacks, if any.
	 *
	 * @since TBD
	 *
	 * @param SuiteEvent $event The suite initialization event.
	 *
	 * @return void The method will run the suite init callbacks, if set.
	 */
	public function on_suite_init( SuiteEvent $event ): void {
		$suite_name = $event->getSuite()->getName();

		if ( ! isset( static::$init_callbacks[ $suite_name ] ) ) {
			return;
		}

		foreach ( static::$init_callbacks[ $suite_name ] as $callback ) {
			$callback();
		}
	}

	/**
	 * Runs the suite before callbacks, if any.
	 *
	 * @since TBD
	 *
	 * @param SuiteEvent $event The suite before event.
	 *
	 * @return void The method will run the suite before callbacks, if set.
	 */
	public function on_suite_before( SuiteEvent $event ): void {
		$suite_name = $event->getSuite()->getName();

		if ( ! isset( static::$before_callbacks[ $suite_name ] ) ) {
			return;
		}

		foreach ( static::$before_callbacks[ $suite_name ] as $callback ) {
			$callback();
		}
	}

	/**
	 * Runs the suite after callbacks, if any.
	 *
	 * @since TBD
	 *
	 * @param SuiteEvent $event The suite after event.
	 *
	 * @return void The method will run the suite before callbacks, if set.
	 */
	public function on_suite_after( SuiteEvent $event ): void {
		$suite_name = $event->getSuite()->getName();

		if ( ! isset( static::$after_callbacks[ $suite_name ] ) ) {
			return;
		}

		foreach ( static::$after_callbacks[ $suite_name ] as $callback ) {
			$callback();
		}
	}
}
