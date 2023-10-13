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
use Tribe__Main;

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
		add_action( 'tribe_plugins_loaded', [ $this, 'boot_telemetry' ], 50 );

		/**
		 * All these actions here need to be hooked from `tec_common_telemetry_preload` action to make sure that we have
		 * all the telemetry code loaded and ready to go.
		 */
		add_action( 'tec_common_telemetry_preload', [ $this, 'hook_telemetry_init' ], 5 );

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
		add_filter( 'http_request_args', [ $this, 'filter_telemetry_http_request_args' ], 10, 2 );

		/* Prefixed filters - should be 'tec' but best to grab it to be sure. */
		$prefix = Telemetry::get_hook_prefix();
		add_filter( "stellarwp/telemetry/{$prefix}/send_data_args", [ $this, 'filter_data_args' ] );
	}

	/**
	 * Filters the HTTP request arguments for TEC telemetry to add the tribe-common integration ID and version.
	 * For versioning purposes.
	 *
	 * @since 5.1.8.1
	 *
	 * @param array  $parsed_args An array of HTTP request arguments.
	 * @param string $url         The request URL.
	 */
	function filter_telemetry_http_request_args( $parsed_args, $url ) {
		if ( false === stripos( $url, 'stellarwp.com/api/v1/telemetry' ) ) {
			return $parsed_args;
		}

		$parsed_args['integration_id']      = 'tec_common';
		$parsed_args['integration_version'] = Tribe__Main::VERSION;

		return $parsed_args;
	}

	/**
	 * It's super important to make sure when hooking to WordPress actions that we don't do before we are sure that
	 * telemetry was properly booted into the system.
	 *
	 * @since 5.1.3
	 */
	public function hook_telemetry_init(): void {
		add_action( 'admin_init', [ $this, 'initialize_telemetry' ], 5 );
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

	public function filter_data_args( $args ) {
		return $this->container->make( Telemetry::class )->filter_data_args( $args );
	}
}
