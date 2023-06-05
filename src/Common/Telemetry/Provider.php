<?php
/**
 * Service Provider for Telemetry.
 *
 * @since   TBD
 *
 * @package TEC\Common\Telemetry
 */

namespace TEC\Common\Telemetry;

use TEC\Common\Contracts\Service_Provider;

/**
 * Class Provider
 *
 * @since   TBD

 * @package TEC\Common\Telemetry
 */
class Provider extends Service_Provider {


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

	/**
	 * Add the action hooks.
	 *
	 * @since TBD
	 */
	public function add_actions() {
		add_action( 'wp', [ $this, 'initialize_telemetry' ] );
		add_action( 'tec-telemetry-modal', [ $this, 'show_optin_modal' ] );
		add_action( 'tec_common_telemetry_loaded', [ $this, 'migrate_existing_opt_in' ], 100 );
		add_action( 'tec_telemetry_auto_opt_in', [ $this, 'auto_opt_in' ] );
	}

	/**
	 * Add the filter hooks.
	 *
	 * @since TBD
	 */
	public function add_filters() {
		add_filter( 'stellarwp/telemetry/optin_args', [ $this, 'filter_optin_args' ] );
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
		$this->container->make( Migration::class )->migrate_existing_opt_in();
	}

	/**
	 * Triggers the automatic opt-in for folks who opted in to Freemius.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function auto_opt_in() {
		$this->container->make( Migration::class )->auto_opt_in();
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

	/**
	 * Filters the exit questionnaire shown during plugin deactivation/uninstall.
	 *
	 * @since TBD
	 *
	 * @param array<string,mixed> $args The current args.
	 *
	 * @return array<string,mixed> $args The modified args.
	 */
	public function filter_exit_interview_args( $args ) {
		return $this->container->make( Telemetry::class )->filter_exit_interview_args( $args );
	}
}
