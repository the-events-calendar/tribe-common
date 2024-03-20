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
		$this->container->singleton( Opt_In::class, Opt_In::class );

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
		add_filter( 'stellarwp/telemetry/the-events-calendar/optin_args', [ $this, 'filter_tec_optin_args' ], 10 );
		add_filter( 'stellarwp/telemetry/event-tickets/optin_args', [ $this, 'filter_et_optin_args' ], 10 );
		add_filter( 'stellarwp/telemetry/exit_interview_args', [ $this, 'filter_exit_interview_args' ] );
		add_filter( 'http_request_args', [ $this, 'filter_telemetry_http_request_args' ], 10, 2 );
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
	public function filter_telemetry_http_request_args( $parsed_args, $url ) {
		if ( false === stripos( $url, 'telemetry.stellarwp.com/api/v1/opt-in' ) ) {
			return $parsed_args;
		}

		$parsed_args['body']['integration_id']      = 'tec_common';
		$parsed_args['body']['integration_version'] = Tribe__Main::VERSION;

		return $parsed_args;
	}

	/**
	 * Filters the arguments for telemetry data to add the opt-in user data if missing.
	 *
	 * @since 5.1.13
	 *
	 * @param array $args Telemetry args.
	 *
	 * @return array
	 */
	public function filter_send_data_args( $args ) {
		if ( ! is_array( $args ) ) {
			return $args;
		}

		if ( empty( $args['telemetry'] ) ) {
			return $args;
		}

		$telemetry = json_decode( $args['telemetry'], true );

		if ( ! empty( $telemetry['opt_in_user'] ) ) {
			return $args;
		}

		/** @var Opt_In $opt_in */
		$opt_in = $this->container->get( Opt_In::class );

		$telemetry['opt_in_user'] = $opt_in->build_opt_in_user();

		$args['telemetry'] = wp_json_encode( $telemetry );

		return $args;
	}

	/**
	 * It's super important to make sure when hooking to WordPress actions that we don't do before we are sure that
	 * telemetry was properly booted into the system.
	 *
	 * @since 5.1.3
	 * @since 5.1.13 Added filter of send_data_args to include opt-in data.
	 */
	public function hook_telemetry_init(): void {
		add_filter( 'stellarwp/telemetry/tec/send_data_args', [ $this, 'filter_send_data_args' ] );
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
	 * @param string $slug The slug of the plugin to show the opt-in modal for.
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
	 * @deprecated 5.2.2 Use the slug-specific filters instead.
	 *
	 * @param array<string|mixed> $args The current optin modal args.
	 * @param string|null         $slug The slug of the plugin to show the opt-in modal for.
	 *
	 * @return array<string|mixed>
	 */
	public function filter_optin_args( $args, $slug = null ): array {
		return $this->container->make( Telemetry::class )->filter_optin_args( $args, $slug );
	}

	/**
	 * Filters the TEC opt-in modal args, passing the correct slug.
	 *
	 * @since 5.2.2
	 *
	 * @param array<string|mixed> $args The current optin modal args.
	 *
	 * @return array<string|mixed>
	 */
	public function filter_tec_optin_args( $args ): array {
		return $this->container->make( Telemetry::class )->filter_optin_args( $args, 'the-events-calendar' );
	}

	/**
	 * Filters the ET opt-in modal args, passing the correct slug.
	 *
	 * @since 5.2.2
	 *
	 * @param array<string|mixed> $args The current optin modal args.
	 *
	 * @return array<string|mixed>
	 */
	public function filter_et_optin_args( $args ): array {
		return $this->container->make( Telemetry::class )->filter_optin_args( $args, 'event-tickets' );
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
