<?php
/**
 * Handles In-App Notifications setup and actions.
 *
 * @since   TBD
 *
 * @package TEC\Common\Notifications
 */

namespace TEC\Common\Notifications;

use Tribe__Main;
use TEC\Common\Admin\Conditional_Content\Dismissible_Trait;

/**
 * Class Notifications
 *
 * @since   TBD

 * @package TEC\Common\Notifications
 */
final class Notifications {
	use Dismissible_Trait;

	/**
	 * The slugs for plugins that support In-App Notifications.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	private $slugs = [];

	/**
	 * The Notifications API URL.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	private $api_url = '';

	/**
	 * Notification slug for dismissible content.
	 *
	 * @var string
	 */
	protected string $slug = '';

	/**
	 * Constructor.
	 *
	 * @since TBD
	 */
	public function __construct() {
		$this->register_assets();

		$this->api_url = $this->get_api_url();
		$this->slugs   = $this->get_plugins();

		/**
		 * Allow plugins to hook in and add themselves,
		 * running their own actions after IAN is initiated.
		 *
		 * @since TBD
		 *
		 * @param self $ian The IAN instance.
		 */
		do_action( 'tec_common_ian_loaded', $this );
	}


	/**
	 * Register the Admin assets for the In-App Notifications.
	 *
	 * @since  TBD
	 *
	 * @return void
	 */
	public function register_assets(): void {
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
	 * Get the API URL for the In-App Notifications.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_api_url() {
		$api = defined( 'TEC_COMMON_IAN_API_URL' ) ? TEC_COMMON_IAN_API_URL : 'https://ian.stellarwp.com/feed/stellar/tec/plugins.json';

		/**
		 * Filter the API URL for the In-App Notifications.
		 *
		 * @since TBD
		 *
		 * @param string $api The API URL for the In-App Notifications.
		 */
		$api = apply_filters( 'tec_common_ian_api_url', $api, $this );

		return $api;
	}

	/**
	 * Define which pages will show the notification icon.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public function is_ian_page() {
		$screen = get_current_screen();

		$allowed = [ 'tribe_events', 'edit-tribe_events', 'tribe_events_page_tec-events-settings' ];

		/**
		 * Filter the allowed pages for the Notifications icon.
		 *
		 * @since TBD
		 *
		 * @param array<string> $allowed The allowed pages for the Notifications icon.
		 */
		$allowed = apply_filters( 'tec_common_ian_allowed_pages', $allowed );

		if ( in_array( $screen->id, $allowed, true ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Register the plugins that support In-App Notifications.
	 *
	 * @since TBD
	 *
	 * @return array<string> The slugs for plugins that support IAN.
	 */
	public function get_plugins() {
		$plugins = [ 'the-events-calendar', 'event-tickets' ];

		/**
		 * Filter the plugin slugs for the In-App Notifications.
		 *
		 * @since TBD
		 *
		 * @param array<string> $slugs The slugs for plugins that support IAN.
		 */
		return apply_filters( 'tec_common_ian_slugs', $plugins );
	}

	/**
	 * Show our notification icon.
	 *
	 * @since TBD
	 *
	 * @param string $slug The plugin slug for IAN.
	 *
	 * @return void
	 */
	public function show_icon( $slug ): void {
		if ( ! in_array( $slug, $this->get_plugins(), true ) || ! $this->is_ian_page() ) {
			return;
		}

		/**
		 * Filter allowing disabling of the Notifications icon by returning false.
		 *
		 * @since TBD
		 *
		 * @param bool $show Whether to show the icon or not.
		 */
		$show = (bool) apply_filters( 'tec_common_ian_show_icon', true, $slug );

		if ( ! $show ) {
			return;
		}

		$template = new Template();
		$template->render_icon( [ 'slug' => $slug ], true );
	}

	/**
	 * Optin to IAN notifications.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function opt_in() {
		if ( ! wp_verify_nonce( tec_get_request_var( 'nonce' ), 'common_ian_nonce' ) ) {
			wp_send_json_error( esc_html__( 'Invalid nonce', 'tribe-common' ), 403 );
			return;
		}

		tribe_update_option( 'ian-notifications-opt-in', 1 );

		wp_send_json_success( esc_html__( 'Notifications opt-in successful', 'tribe-common' ), 200 );
	}

	/**
	 * Get the IAN notifications.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function get_feed() {
		if ( ! wp_verify_nonce( tec_get_request_var( 'nonce' ), 'common_ian_nonce' ) ) {
			wp_send_json_error( esc_html__( 'Invalid nonce', 'tribe-common' ), 403 );
			return;
		}

		$cache    = tribe_cache();
		$response = $cache->get( 'ian_api_feed_response' );

		if ( false === $response ) {
			// phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.wp_remote_get_wp_remote_get
			$response = wp_remote_get( $this->api_url );
			$cache->set( 'ian_api_feed_response', $response, 15 * MINUTE_IN_SECONDS );
		}

		if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
			wp_send_json_error( wp_remote_retrieve_response_message( $response ), wp_remote_retrieve_response_code( $response ) );
			return;
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );
		$feed = Conditionals::filter_feed( $body['notifications_by_area']['general-tec'] ?? [] );

		$template = new Template();
		foreach ( $feed as $k => $notification ) {
			$this->slug = $notification['slug'];
			if ( $this->has_user_dismissed() ) {
				unset( $feed[ $k ] );
				continue;
			}
			$feed[ $k ]['html'] = $template->render_notification( $notification, false );
		}
		array_values( $feed );

		wp_send_json_success( $feed, 200 );
	}

	/**
	 * AJAX handler for dismissing IAN notifications.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function handle_dismiss(): void {
		$id = tec_get_request_var( 'id' );

		if ( ! wp_verify_nonce( tec_get_request_var( 'nonce' ), 'ian_nonce_' . $id ) ) {
			wp_send_json_error( esc_html__( 'Invalid nonce', 'tribe-common' ), 403 );
			return;
		}

		$slug = tec_get_request_var( 'slug' );

		if ( empty( $slug ) ) {
			wp_send_json_error( esc_html__( 'Invalid notification slug', 'tribe-common' ), 403 );
			return;
		}

		$this->slug = $slug;
		$this->dismiss();

		wp_send_json_success( esc_html__( 'Notification dismissed', 'tribe-common' ), 200 );
	}
}
