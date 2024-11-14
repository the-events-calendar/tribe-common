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
use Tribe__Main;

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
		add_action( 'tribe_plugins_loaded', [ $this, 'register_ian' ], 50 );

		add_action( 'tec_ian_icon', [ $this, 'show_icon' ] );

		add_action( 'wp_ajax_ian_optin', [ $this, 'opt_in' ] );
		add_action( 'wp_ajax_ian_get_feed', [ $this, 'get_feed' ] );
		add_action( 'wp_ajax_ian_dismiss', [ $this, 'handle_dismiss' ] );
	}

	/**
	 * Unhooks actions and filters.
	 */
	public function unregister(): void {
		remove_action( 'tribe_plugins_loaded', [ $this, 'register_ian' ], 50 );

		remove_action( 'tec_ian_icon', [ $this, 'show_icon' ] );

		remove_action( 'wp_ajax_ian_optin', [ $this, 'opt_in' ] );
		remove_action( 'wp_ajax_ian_get_feed', [ $this, 'get_feed' ] );
		remove_action( 'wp_ajax_ian_dismiss', [ $this, 'handle_dismiss' ] );
	}

	/**
	 * Register the In-App Notifications assets.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function register_ian() {
		tribe_assets(
			Tribe__Main::instance(),
			[
				[ 'ian-client-css', 'ian-client.css' ],
				[ 'ian-client-js', 'ian-client.js', [ 'jquery' ] ],
			],
			'admin_enqueue_scripts',
			[
				'conditionals' => [ $this, 'is_ian_page' ],
				'in_footer'    => false,
				'localize'     => [
					'name' => 'commonIan',
					'data' => [
						'ajax_url' => admin_url( 'admin-ajax.php' ),
						'nonce'    => wp_create_nonce( 'common_ian_nonce' ),
					],
				],
			]
		);
	}

	/**
	 * Define which pages will show the notification icon.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public function is_ian_page() {
		$screen  = get_current_screen();
		$allowed = [ 'tribe_events', 'edit-tribe_events', 'tribe_events_page_tec-events-settings' ];

		/**
		 * Filter the allowed pages for the Notifications icon.
		 *
		 * @since TBD
		 *
		 * @param array<string> $allowed The allowed pages for the Notifications icon.
		 */
		$allowed = apply_filters( 'tec_common_ian_allowed_pages', $allowed );

		/**
		 * Filter the showing of the Notifications icon.
		 *
		 * @since TBD
		 *
		 * @param bool Whether to show the icon or not.
		 */
		return apply_filters( 'tec_common_ian_show_icon', in_array( $screen->id, $allowed, true ) );
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
		if ( self::is_ian_page() && current_user_can( 'manage_options' ) ) {
			$this->container->make( Notifications::class )->show_icon( $slug );
		}
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
