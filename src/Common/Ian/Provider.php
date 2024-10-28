<?php
/**
 * Service Provider for IAN Client (In-App Notifications).
 *
 * @since   TBD
 *
 * @package TEC\Common\Ian
 */

namespace TEC\Common\Ian;

use TEC\Common\Contracts\Service_Provider;
use Tribe__Main;

/**
 * Class Provider
 *
 * @since   TBD

 * @package TEC\Common\Ian
 */
class Provider extends Service_Provider {

	/**
	 * Registers actions and filters.
	 *
	 * @since TBD
	 */
	public function register() {
		$this->container->bind( Ian_Client::class, Ian_Client::class );
		$this->container->singleton( Conditionals::class, Conditionals::class );

		$this->add_actions();
		$this->add_filters();
	}

	/**
	 * Add the action hooks.
	 *
	 * @since 5.1.0
	 */
	public function add_actions() {
		add_action( 'tribe_plugins_loaded', [ $this, 'boot_ian' ], 50 );

		add_action( 'tec_common_ian_preload', [ $this, 'hook_ian_init' ], 5 );
		add_action( 'tec_ian_icon', [ $this, 'show_ian_icon' ] );
		add_action( 'tec_common_ian_loaded', [ $this, 'maybe_enqueue_admin_ian_assets' ] );
	}

	/**
	 * Add the filter hooks.
	 *
	 * @since 5.1.0
	 */
	public function add_filters() {
		add_filter( 'http_request_args', [ $this, 'filter_ian_http_request_args' ], 10, 2 );
	}

	/**
	 * Filters the HTTP request arguments for IAN to add the tribe-common integration ID and version.
	 * For versioning purposes.
	 *
	 * @since TBD
	 *
	 * @param array  $parsed_args An array of HTTP request arguments.
	 * @param string $url         The request URL.
	 */
	public function filter_ian_http_request_args( $parsed_args ) {
		// TODO - Do we need this?
		$parsed_args['body']['integration_id']      = 'tec_common';
		$parsed_args['body']['integration_version'] = Tribe__Main::VERSION;

		return $parsed_args;
	}

	/**
	 * Hook the IAN initialization.
	 *
	 * @since TBD
	 */
	public function hook_ian_init(): void {
		add_action( 'admin_init', [ $this, 'initialize_ian' ], 5 );
	}

	/**
	 * Boot our internal IAN code.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function boot_ian() {
		$this->container->make( Ian_Client::class )->boot();
	}

	/**
	 * Initialize our internal IAN code.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function initialize_ian() {
		$this->container->make( Ian_Client::class )->init();
	}

	/**
	 * Logic for if the IAN icon should be shown.
	 *
	 * @since 5.1.0
	 *
	 * @param string $slug The slug of the plugin to show the opt-in modal for.
	 *
	 * @return void
	 */
	public function show_ian_icon( $slug ) {
		$this->container->make( Ian_Client::class )->show_ian_icon( $slug );
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
		return $this->container->make( Ian_Client::class )->filter_optin_args( $args, $slug );
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
		return $this->container->make( Ian_Client::class )->filter_optin_args( $args, 'the-events-calendar' );
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
		return $this->container->make( Ian_Client::class )->filter_optin_args( $args, 'event-tickets' );
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
		return $this->container->make( Ian_Client::class )->filter_exit_interview_args( $args );
	}


	/**
	 * Ensure the assets for the modal are enqueued, if needed.
	 *
	 * @since 5.1.0
	 */
	public function maybe_enqueue_admin_ian_assets(): void {
		// TODO enqueue the assets this way
		// $this->container->make( Asset_Subscriber::class )->maybe_enqueue_admin_assets();
	}
}
