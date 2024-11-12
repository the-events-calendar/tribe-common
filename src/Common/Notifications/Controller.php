<?php
/**
 * Controller for In-App Notifications.
 *
 * @since   TBD
 *
 * @package TEC\Common\Notifications
 */

namespace TEC\Common\Notifications;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;

/**
 * Class Controller
 *
 * @since   TBD

 * @package TEC\Common\Notifications
 */
class Controller extends Controller_Contract {

	/**
	 * Registers actions and filters.
	 *
	 * @since TBD
	 */
	public function do_register(): void {
		$this->add_actions();
	}

	/**
	 * Unhooks actions and filters.
	 */
	public function unregister(): void {
		$this->remove_actions();
	}

	/**
	 * Add the action hooks.
	 *
	 * @since TBD
	 */
	public function add_actions() {
		add_action( 'tribe_plugins_loaded', [ $this, 'boot' ], 50 );

		add_action( 'tec_ian_icon', [ $this, 'show_icon' ] );

		add_action( 'wp_ajax_ian_optin', [ $this, 'opt_in' ] );
		add_action( 'wp_ajax_ian_get_feed', [ $this, 'get_feed' ] );
		add_action( 'wp_ajax_ian_dismiss', [ $this, 'handle_dismiss' ] );
	}

	/**
	 * Remove the action hooks.
	 *
	 * @since TBD
	 */
	public function remove_actions() {
		remove_action( 'tribe_plugins_loaded', [ $this, 'boot' ], 50 );

		remove_action( 'tec_ian_icon', [ $this, 'show_icon' ] );

		remove_action( 'wp_ajax_ian_optin', [ $this, 'opt_in' ] );
		remove_action( 'wp_ajax_ian_get_feed', [ $this, 'get_feed' ] );
		remove_action( 'wp_ajax_ian_dismiss', [ $this, 'handle_dismiss' ] );
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
		$this->container->make( Notifications::class );
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
