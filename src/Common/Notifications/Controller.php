<?php
/**
 * Service Provider for In-App Notifications.
 *
 * @since   TBD
 *
 * @package TEC\Common\Notifications
 */

namespace TEC\Common\Notifications;

use TEC\Common\Contracts\Service_Provider as Provider_Contract;

/**
 * Class Provider
 *
 * @since   TBD

 * @package TEC\Common\Notifications
 */
class Controller extends Provider_Contract {

	/**
	 * Registers actions and filters.
	 *
	 * @since TBD
	 */
	public function register() {
		$this->container->bind( Notifications::class, Notifications::class );
		$this->container->singleton( Conditionals::class, Conditionals::class );

		$this->hooks();
	}

	/**
	 * Add the action hooks.
	 *
	 * @since TBD
	 */
	public function hooks() {
		add_action( 'tribe_plugins_loaded', [ $this, 'boot' ], 50 );

		add_action( 'tec_common_ian_preload', [ $this, 'hook_init' ], 5 );
		add_action( 'tec_ian_icon', [ $this, 'show_icon' ] );
		add_action( 'tec_common_ian_loaded', [ $this, 'register_assets' ] );

		add_action( 'wp_ajax_ian_optin', [ $this, 'opt_in' ] );
		add_action( 'wp_ajax_ian_get_feed', [ $this, 'get_feed' ] );
		add_action( 'wp_ajax_ian_dismiss', [ $this, 'handle_dismiss' ] );
	}

	/**
	 * Hook the IAN initialization.
	 *
	 * @since TBD
	 */
	public function hook_init(): void {
		add_action( 'admin_init', [ $this, 'initialize' ], 5 );
	}

	/**
	 * Boot our internal IAN code.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function boot() {
		$this->container->make( Notifications::class )->boot();
	}

	/**
	 * Initialize our internal IAN code.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function initialize() {
		$this->container->make( Notifications::class )->init();
	}

	/**
	 * Logic for if the Notifications icon should be shown.
	 *
	 * @since TBD
	 *
	 * @param string $slug The slug of the plugin to show the notifications in.
	 *
	 * @return void
	 */
	public function show_icon( $slug ) {
		$this->container->make( Notifications::class )->show_icon( $slug );
	}

	/**
	 * Ensure the assets for the modal are enqueued, if needed.
	 *
	 * @since TBD
	 */
	public function register_assets(): void {
		$this->container->make( Notifications::class )->register_assets();
	}

	/**
	 * AJAX handler for opting in to Notifications.
	 *
	 * @since TBD
	 */
	public function opt_in() {
		$this->container->make( Notifications::class )->opt_in();
	}

	/**
	 * AJAX handler for getting notifications.
	 *
	 * @since TBD
	 */
	public function get_feed() {
		$this->container->make( Notifications::class )->get_feed();
	}

	/**
	 * AJAX handler for dismissing notifications.
	 *
	 * @since TBD
	 */
	public function handle_dismiss() {
		$this->container->make( Notifications::class )->handle_dismiss();
	}
}
