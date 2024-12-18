<?php
/**
 * Handles hooking all the actions and filters used by the module.
 *
 * To remove a filter:
 * ```php
 *  remove_filter( 'some_filter', [ tribe( TEC\Event_Automator\Hooks::class ), 'some_filtering_method' ] );
 * ```
 *
 * To remove an action:
 * ```php
 *  remove_action( 'some_action', [ tribe( TEC\Event_Automator\Hooks::class ), 'some_method' ] );
 * ```
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator;
 */

namespace TEC\Event_Automator;

use TEC\Common\Contracts\Service_Provider;
use TEC\Event_Automator\Admin\Tabs\Tabs_Provider;

/**
 * Class Hooks.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator;
 */
class Hooks extends Service_Provider {

	/**
	 * Binds and sets up implementations.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 */
	public function register() {
		$this->container->singleton( static::class, $this );
		$this->container->singleton( 'event-automator.hooks', $this );

		add_action( 'admin_init', [ $this, 'run_updates' ], 10, 0 );
	}

	/**
	 * Run Updates on Plugin Upgrades.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 */
	public function run_updates() {
		if ( ! class_exists( 'Tribe__Events__Updater', false ) ) {
			return; // Core needs to be updated for compatibility.
		}

		$updater = new Updater( Plugin::VERSION );
		$updater->run_updates();
	}

	/**
	 * Register providers at admin_init, so dependencies are loaded.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 * @deprecated 6.4.1
	 */
	public function admin_register() {
		_deprecated_function( __METHOD__, '6.4.1' );
	}
}
