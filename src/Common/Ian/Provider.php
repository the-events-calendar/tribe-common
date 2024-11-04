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
	}

	/**
	 * Add the action hooks.
	 *
	 * @since TBD
	 */
	public function add_actions() {
		add_action( 'tribe_plugins_loaded', [ $this, 'boot_ian' ], 50 );

		add_action( 'tec_common_ian_preload', [ $this, 'hook_ian_init' ], 5 );
		add_action( 'tec_ian_icon', [ $this, 'show_ian_icon' ] );
		add_action( 'tec_common_ian_loaded', [ $this, 'maybe_enqueue_admin_assets' ] );

		add_action( 'wp_ajax_optin_ian', [ $this, 'ajax_optin_ian' ] );
		add_action( 'wp_ajax_get_ian', [ $this, 'ajax_get_ian' ] );

		add_action( 'wp_ajax_ian_notification_dismiss', [ $this, 'handle_dismiss' ] );
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
	 * @since TBD
	 *
	 * @param string $slug The slug of the plugin to show the opt-in modal for.
	 *
	 * @return void
	 */
	public function show_ian_icon( $slug ) {
		$this->container->make( Ian_Client::class )->show_ian_icon( $slug );
	}

	/**
	 * Ensure the assets for the modal are enqueued, if needed.
	 *
	 * @since TBD
	 */
	public function maybe_enqueue_admin_assets(): void {
		$this->container->make( Ian_Client::class )->register_ian_assets();
	}

	/**
	 * AJAX handler for opting in to IAN.
	 *
	 * @since TBD
	 */
	public function ajax_optin_ian() {
		$this->container->make( Ian_Client::class )->ajax_optin_ian();
	}

	/**
	 * AJAX handler for getting IAN notifications.
	 *
	 * @since TBD
	 */
	public function ajax_get_ian() {
		$this->container->make( Ian_Client::class )->ajax_get_ian();
	}

	/**
	 * AJAX handler for dismissing IAN notifications.
	 *
	 * @since TBD
	 */
	public function handle_dismiss() {
		$this->container->make( Ian_Client::class )->handle_dismiss();
	}
}
