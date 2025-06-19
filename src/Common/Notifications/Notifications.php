<?php
/**
 * Handles In-App Notifications setup and actions.
 *
 * @since 6.4.0
 *
 * @package TEC\Common\Notifications
 */

namespace TEC\Common\Notifications;

use TEC\Common\Admin\Conditional_Content\Dismissible_Trait;

/**
 * Class Notifications
 *
 * @since 6.4.0

 * @package TEC\Common\Notifications
 */
final class Notifications {
	use Dismissible_Trait;
	use Readable_Trait;

	/**
	 * The slugs for plugins that support In-App Notifications.
	 *
	 * @since 6.4.0
	 *
	 * @var array
	 */
	private $slugs = [];

	/**
	 * The Notifications API URL.
	 *
	 * @since 6.4.0
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
	 * @since 6.4.0
	 */
	public function __construct() {
		$this->api_url = $this->get_api_url();
		$this->slugs   = $this->get_plugins();
	}

	/**
	 * Get the API URL for the In-App Notifications.
	 *
	 * @since 6.4.0
	 *
	 * @return string
	 */
	public function get_api_url() {
		$api = defined( 'TEC_COMMON_IAN_API_URL' ) ? TEC_COMMON_IAN_API_URL : 'https://ian.stellarwp.com/feed/stellar/tec/plugins.json';

		/**
		 * Filter the API URL for the In-App Notifications.
		 *
		 * @since 6.4.0
		 *
		 * @param string $api      The API URL for the In-App Notifications.
		 * @param object $instance The current instance of the class.
		 */
		$api = apply_filters( 'tec_common_ian_api_url', $api, $this );

		return $api;
	}

	/**
	 * Register the plugins that support In-App Notifications.
	 *
	 * @since 6.4.0
	 *
	 * @return array<string> The slugs for plugins that support IAN.
	 */
	public function get_plugins() {
		$plugins = [ 'the-events-calendar', 'event-tickets' ];

		/**
		 * Filter the plugin slugs for the In-App Notifications.
		 *
		 * @since 6.4.0
		 *
		 * @param array<string> $slugs The slugs for plugins that support IAN.
		 */
		return apply_filters( 'tec_common_ian_slugs', $plugins );
	}

	/**
	 * Show our notification icon.
	 *
	 * @since 6.4.0
	 *
	 * @param string $slug The plugin slug for IAN.
	 *
	 * @return void
	 */
	public function show_icon( $slug ): void {
		if ( ! in_array( $slug, $this->get_plugins(), true ) ) {
			return;
		}

		/**
		 * Filter allowing disabling of the Notifications by returning false.
		 *
		 * @since 6.4.0
		 *
		 * @param bool $show Whether to render the IAN sidebar or not.
		 */
		$show = (bool) apply_filters( 'tec_common_ian_render', true, $slug );

		if ( ! $show ) {
			return;
		}

		$template = new Template();
		$template->render_sidebar( [ 'slug' => $slug ], true );
	}

	/**
	 * Optin to IAN notifications.
	 *
	 * @since 6.4.0
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
	 * @since 6.4.0
	 * @since TBD Broke the filtering out into a separate method.
	 *
	 * @return void
	 */
	public function get_feed() {
		if ( ! wp_verify_nonce( tec_get_request_var( 'nonce' ), 'common_ian_nonce' ) ) {
			wp_send_json_error( esc_html__( 'Invalid nonce', 'tribe-common' ), 403 );
			return;
		}

		$cache = tribe_cache();
		$slug  = tec_get_request_var( 'plugin' );
		$feed  = $cache->get_transient( 'tec_ian_api_feed_' . $slug );

		if ( false === $feed || ! is_array( $feed ) ) {
			// phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.wp_remote_get_wp_remote_get
			$response = wp_remote_get( $this->api_url );
			if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
				$cache->set_transient( 'tec_ian_api_feed_' . $slug, [], 15 * MINUTE_IN_SECONDS );
				wp_send_json_error( wp_remote_retrieve_response_message( $response ), wp_remote_retrieve_response_code( $response ) );
				return;
			}

			$body = json_decode( wp_remote_retrieve_body( $response ), true );
			$feed = $this->filter_notifications( $body, $slug );
			$cache->set_transient( 'tec_ian_api_feed_' . $slug, $feed, 15 * MINUTE_IN_SECONDS );
		}

		$template = new Template();
		foreach ( $feed as $k => $notification ) {
			$this->slug = $notification['slug'];
			if ( $this->has_user_dismissed() ) {
				unset( $feed[ $k ] );
				continue;
			}

			$notification['read'] = $this->has_user_read();

			$feed[ $k ]['html'] = $template->render_notification( $notification, false );
			$feed[ $k ]['read'] = $notification['read'] ?? false;
		}
		array_values( $feed );

		/**
		 * Filter the final feed before it's sent as JSON.
		 *
		 * @since TBD
		 *
		 * @param array  $feed The filtered notifications feed.
		 * @param string $slug The plugin slug.
		 */
		$feed = apply_filters( 'tec_common_ian_feed', $feed, $slug );

		wp_send_json_success( $feed, 200 );
	}

	/**
	 * Filters notifications from the API response based on the plugin slug.
	 *
	 * @since TBD
	 *
	 * @param array  $body The API response body.
	 * @param string $slug The plugin slug.
	 *
	 * @return array
	 */
	private function filter_notifications( $body, $slug ) {
		/**
		 * Filter the callback used to filter notifications from the API response.
		 * Default callback filters by plugin slug. Plugins can provide their own filtering logic.
		 *
		 * @since TBD
		 *
		 * @param callable $callback The filtering callback function.
		 * @param array    $body     The full API response body.
		 * @param string   $slug     The plugin slug.
		 */
		$filter_callback = apply_filters(
			'tec_common_ian_filter_callback',
			[ $this, 'filter_notifications_by_slug' ],
			$body,
			$slug
		);

		// Ensure we have a valid callback
		if ( ! is_callable( $filter_callback ) ) {
			$filter_callback = [ $this, 'filter_notifications_by_slug' ];
		}

		$notifications = call_user_func( $filter_callback, $body, $slug );

		return Conditionals::filter_feed( $notifications );
	}

	/**
	 * Default filter that gets notifications by plugin slug.
	 *
	 * @since TBD
	 *
	 * @param array  $body The API response body.
	 * @param string $slug The plugin slug.
	 *
	 * @return array
	 */
	private function filter_notifications_by_slug( $body, $slug ) {
		return $body['notifications_by_area'][ 'general-' . $slug ] ?? [];
	}

	/**
	 * AJAX handler for dismissing IAN notifications.
	 *
	 * @since 6.4.0
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

	/**
	 * AJAX handler for marking IAN notifications as read.
	 *
	 * @since 6.4.0
	 *
	 * @return void
	 */
	public function handle_read(): void {
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
		$this->read();

		wp_send_json_success( esc_html__( 'Notification marked as read', 'tribe-common' ), 200 );
	}

	/**
	 * AJAX handler for marking all IAN notifications as read.
	 *
	 * @since 6.4.0
	 *
	 * @return void
	 */
	public function handle_read_all(): void {
		if ( ! wp_verify_nonce( tec_get_request_var( 'nonce' ), 'common_ian_nonce' ) ) {
			wp_send_json_error( esc_html__( 'Invalid nonce', 'tribe-common' ), 403 );
			return;
		}

		$unread = json_decode( stripslashes( tec_get_request_var( 'unread', '' ) ), true );

		foreach ( $unread as $slug ) {
			$this->slug = $slug;
			$this->read();
		}

		wp_send_json_success( esc_html__( 'All notifications marked as read', 'tribe-common' ), 200 );
	}
}
