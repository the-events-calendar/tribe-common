<?php
/**
 * Service Provider for Telemetry.
 *
 * @since   TBD
 *
 * @package TEC\Common\Telemetry
 */

namespace TEC\Common\Telemetry;

use TEC\Common\lucatume\DI52\ServiceProvider;

/**
 * Class Provider
 *
 * @since   TBD

 * @package TEC\Common\Telemetry
 */
class Provider extends ServiceProvider {


	/**
	 * Registers the handlers and modifiers for notifying the site
	 * that Legacy views are removed.
	 *
	 * @since 5.13.0
	 */
	public function register() {
		$this->container->bind( Telemetry::class, Telemetry::class );

		$this->add_actions();
		$this->add_filters();
	}

	public function add_actions() {
		add_action( 'tribe_plugins_loaded', [ $this, 'initialize_telemetry' ] );
		// add_action( 'admin_init', [ $this, 'migrate_existing_opt_in' ], 11 );
		add_action( 'admin_init', [ $this, 'save_opt_in_setting_field' ] );
		add_action( 'tec-telemetry-modal', [ $this, 'show_optin_modal' ] );
		// @todo For testing, remove before release!
		add_action( 'stellarwp/telemetry/tec/last_send_expire_seconds', [ $this, 'filter_last_send_expire' ] );
	}

	public function add_filters() {
		add_filter( "stellarwp/telemetry/optin_args", [ $this, 'filter_optin_args' ] );
		add_filter( 'stellarwp/telemetry/tec/should_show_optin', 'should_show_optin', 10, 1 );
		add_filter( 'stellarwp/telemetry/exit_interview_args', [ $this, 'filter_exit_interview_args' ] );
	}

	/**
	 * Initialize our internal Telemetry code.
	 * Drivers, start your engines...
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function initialize_telemetry() {
		$this->container->make( Telemetry::class )->init();
	}

	/**
	 * Placeholder for eventual Freemius removal hooking in to modify things.
	 *
	 * @since TBD
	 * @todo @bordoni leverage this when ready.
	 *
	 * @return void
	 */
	public function migrate_existing_opt_in() {
		$this->container->make( Telemetry::class )->migrate_existing_opt_in();
	}

	/**
	 * Saves the settings field if it exists.
	 * Ensures all connected plugins follow opt in/out in lockstep.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function save_opt_in_setting_field() {
		$this->container->make( Telemetry::class )->save_opt_in_setting_field();
	}

	/**
	 * Logic for if the opt-in modal should be shown.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function show_optin_modal() {
		$this->container->make( Telemetry::class )->show_optin_modal();
	}

	/**
	 * Filters the default opt-in modal args.
	 *
	 * @since TBD
	 *
	 * @param array<string|mixed> $args The current optin modal args.
	 *
	 * @return array<string|mixed>
	 */
	public function filter_optin_args( $args ): array  {
		return $this->container->make( Telemetry::class )->filter_optin_args( $args );
	}

	public function filter_exit_interview_args( $args ) {
		return $this->container->make( Telemetry::class )->filter_exit_interview_args( $args );
	}

	/**
	 * Filters the "polling time" so we can see changes on test servers quickly.
	 * @todo: remove before release!
	 *
	 * @since TBD
	 *
	 * @param integer $expire_seconds
	 * @return integer
	 */
	public function filter_last_send_expire( $expire_seconds ): int {
		return MINUTE_IN_SECONDS * 2;
	}
}
