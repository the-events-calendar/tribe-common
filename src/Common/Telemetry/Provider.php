<?php
/**
 * Service Provider for Telemetry.
 *
 * @since   5.1.0
 *
 * @package TEC\Common\Telemetry
 */

namespace TEC\Common\Telemetry;

use TEC\Common\Contracts\Service_Provider;
use TEC\Common\StellarWP\Telemetry\Admin\Admin_Subscriber as Asset_Subscriber;

/**
 * Class Provider
 *
 * @since   5.1.0

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
	 * @since 5.1.0
	 */
	public function add_actions() {
		add_action( 'wp', [ $this, 'initialize_telemetry' ], 5 );
		add_action( 'plugins_loaded', [ $this, 'boot_telemetry' ], 50 );
		add_action( 'tec_telemetry_modal', [ $this, 'show_optin_modal' ] );
		add_action( 'tec_common_telemetry_preload', [ $this, 'migrate_existing_opt_in' ], 100 );
		add_action( 'tec_common_telemetry_loaded', [ $this, 'maybe_enqueue_admin_modal_assets' ] );
	}

	/**
	 * Add the filter hooks.
	 *
	 * @since 5.1.0
	 */
	public function add_filters() {
		add_filter( 'stellarwp/telemetry/optin_args', [ $this, 'filter_optin_args' ] );
		add_filter( 'stellarwp/telemetry/exit_interview_args', [ $this, 'filter_exit_interview_args' ] );
	}

	/**
	 * Initialize our internal Telemetry code.
	 * Drivers, start your engines...
	 *
	 * @since 5.1.0
	 *
	 * @return void
	 */
	public function boot_telemetry() {
		$this->container->make( Telemetry::class )->boot();
	}

	/**
	 * Initialize our internal Telemetry code.
	 * Drivers, start your engines...
	 *
	 * @since 5.1.0
	 *
	 * @return void
	 */
	public function initialize_telemetry() {
		$this->container->make( Telemetry::class )->init();
	}

	/**
	 * Placeholder for eventual Freemius removal hooking in to modify things.
	 *
	 * @since 5.1.0
	 * @todo @bordoni leverage this when ready.
	 *
	 * @return void
	 */
	public function migrate_existing_opt_in() {
		$this->container->make( Migration::class )->migrate_existing_opt_in();
	}

	/**
	 * Logic for if the opt-in modal should be shown.
	 *
	 * @since 5.1.0
	 *
	 * @return void
	 */
	public function show_optin_modal( $slug ) {
		$this->container->make( Telemetry::class )->show_optin_modal( $slug );
	}

	/**
	 * Filters the default opt-in modal args.
	 *
	 * @since 5.1.0
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
	 * @since 5.1.0
	 *
	 * @param array<string,mixed> $args The current args.
	 *
	 * @return array<string,mixed> $args The modified args.
	 */
	public function filter_exit_interview_args( $args ) {
		return $this->container->make( Telemetry::class )->filter_exit_interview_args( $args );
	}


	/**
	 * Ensure the assets for the modal are enqueued, if needed.
	 *
	 * @since 5.1.0
	 */
	public function maybe_enqueue_admin_modal_assets(): void {
		$this->container->make( Asset_Subscriber::class )->maybe_enqueue_admin_assets();
	}
}
