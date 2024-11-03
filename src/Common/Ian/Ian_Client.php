<?php
/**
 * Handles IAN Client setup and actions.
 *
 * @since   TBD
 *
 * @package TEC\Common\Ian
 */

namespace TEC\Common\Ian;

use Tribe__Container as Container;
use Tribe__Main;
use TEC\Common\Telemetry\Telemetry;

/**
 * Class Ian_Client
 *
 * @since   TBD

 * @package TEC\Common\Ian
 */
final class Ian_Client {

	/**
	 * The slugs for plugins that support IAN.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	private $plugin_slugs = [
		'the-events-calendar',
		'event-tickets',
	];

	/**
	 * The IAN server URL.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	private $ian_server = '';

	/**
	 * The IAN API URL.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	private $api_url = '';


	/**
	 * Boot the IAN Client.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function boot(): void {
		$container = Container::init();

		$this->ian_server = ! defined( 'STELLARWP_IAN_SERVER' ) ? 'https://ian.stellarwp.com/feed/' : STELLARWP_IAN_SERVER;

		// TODO - Get the organization, brand, and product from... where?
		$organization = $brand = $product = '';
		$this->api_url = $this->ian_server . $organization . '/' . $brand . '/' . $product . '.json';

		/**
		 * Allow plugins to hook in and add themselves,
		 * running their own actions once IAN Client is initiated.
		 *
		 * @since TBD
		 *
		 * @param self $ian The IAN Client instance.
		 */
		do_action( 'tec_common_ian_preload', $this );
	}

	/**
	 * Initializes the plugins and triggers the "loaded" action.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function init(): void {
		/**
		 * Filter the base parent slugs for IAN.
		 *
		 * @since TBD
		 *
		 * @param array<string> $plugin_slugs The slugs for plugins that support IAN.
		 */
		$this->plugin_slugs = apply_filters( 'tec_common_ian_plugin_slugs', $this->plugin_slugs );

		/**
		 * Allow plugins to hook in and add themselves,
		 * running their own actions once IAN is initiated.
		 *
		 * @since TBD
		 *
		 * @param self $ian The IAN instance.
		 */
		do_action( 'tec_common_ian_loaded', $this );
	}

	/**
	 * Register the Admin assets for the IAN Client.
	 *
	 * @since  TBD
	 *
	 * @return void
	 */
	public function register_ian_assets(): void {
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
						'dismiss'  => esc_html__( 'Dismiss', 'tribe-common' ),
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
		$screen = get_current_screen();

		if ( in_array( $screen->id, [ 'tribe_events', 'edit-tribe_events', 'tribe_events_page_tec-events-settings' ], true ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Register the plugins that are hooked into `tec_ian_slugs`.
	 * This keeps all TEC plugins in sync and only requires one notifications sidebar.
	 *
	 * @since TBD
	 *
	 * @param bool|null $opted Whether to opt in or out. If null, will calculate based on existing status.
	 *
	 * @return void
	 */
	public function register_tec_ian_plugins( $opted = null ) {
		// Let's reduce the amount this triggers.
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		global $pagenow;

		// Only run on the plugins page, or when we're manually setting an opt-in!
		if ( $pagenow !== 'plugins.php' && is_null( $opted ) ) {
			return;
		}

		$tec_slugs = $this->plugin_slugs;

		// We've got no plugins?
		if ( empty( $tec_slugs ) ) {
			return;
		}

		// Check for cached slugs.
		$cached_slugs = tribe( 'cache' )['tec_ian_slugs'] ?? null;

		// We have already run and the slug list hasn't changed since then. Or we are manually running.
		if ( is_null( $opted ) && ! empty( $cached_slugs ) && $cached_slugs == $tec_slugs ) {
			return;
		}

		// No cached slugs, or the list has changed, or we're running manually - so (re)set the cached value.
		tribe( 'cache' )['tec_ian_slugs'] = $tec_slugs;
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
	public function show_ian_icon( $slug ): void {
		if ( ! in_array( $slug, $this->plugin_slugs, true ) || ! $this->is_ian_page() ) {
			return;
		}

		/**
		 * Filter allowing disabling of the IAN icon by returning false.
		 *
		 * @since TBD
		 *
		 * @param bool $show Whether to show the modal or not.
		 */
		$show = (bool) apply_filters( 'tec_common_ian_show_icon', true, $slug );

		if ( ! $show ) {
			return;
		}

		$main  = Tribe__Main::instance();

		load_template(
			$main->plugin_path . 'src/admin-views/ian/icon.php',
			true,
			[
				'slug'  => $slug,
				'main'  => $main,
				'optin' => Conditionals::get_ian_opt_in(),
				'url'   => Telemetry::get_permissions_url(),
			]
		);
	}

	/**
	 * Optin to IAN notifications.
	 *
	 * @since TBD
	 */
	public function ajax_optin_ian() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		if ( wp_verify_nonce( $nonce, 'common_ian_nonce' ) ) {

			tribe_update_option( 'ian-client-opt-in', 1 );

			wp_send_json_success( 'IAN opt-in successful', 200 );
		} else {
			wp_send_json_error( 'Invalid nonce', 403 );
		}
	}

	/**
	 * Get the IAN notifications.
	 *
	 * @since TBD
	 */
	public function ajax_get_ian() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		if ( wp_verify_nonce( $nonce, 'common_ian_nonce' ) ) {

			// TODO: The call to Laravel. GET or POST? Do we need to send any data? Auth?
			$response = wp_remote_request(
				$this->ian_server,
				[
					'method'    => 'POST',
					'headers'   => [ 'Content-Type' => 'application/json; charset=utf-8' ],
					'timeout'   => 15, // phpcs:ignore WordPressVIPMinimum.Performance.RemoteRequestTimeout.timeout_timeout
					'sslverify' => false, // we trust our server.
					'body'      => wp_json_encode(
						[
							'param1' => '',
							'param2' => '',
							'token'  => '??',
						]
					),
				]
			);

			if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
				$body = json_decode( wp_remote_retrieve_body( $response ), true );
			} else {
				wp_send_json_error( wp_remote_retrieve_response_message( $response ), wp_remote_retrieve_response_code( $response ) );
			}

			$notifications = Conditionals::filter_ian_feed( $body['notifications'] );

			// TODO: Below is an example notifications array. Send the real one.
			wp_send_json_success(
				[
					[
						'id'          => '101',
						'type'        => 'update',
						'slug'        => 'tec-update-664',
						'title'       => 'The Events Calendar 6.6.4 Update',
						'content'     => '<p>The latest update of The Events Calendar adds an option to allow for duplicate Venue creation, updates custom table query logic to avoid DB error, and logic that displays the “REST API blocked” banner to prevent false positives.</p>',
						'cta'         => [
							'text'   => 'See Details',
							'link'   => 'https://evnt.is/1ai-',
							'target' => '_blank',
						],
						'dismissible' => true,
					],
					[
						'id'          => '102',
						'type'        => 'notice',
						'slug'        => 'event-tickets-upsell',
						'title'       => 'Sell Tickets & Collect RSVPs with Event Tickets',
						'content'     => '<p>Sell tickets, collect RSVPs and manage attendees for free.</p>',
						'cta'         => [
							'text'   => 'Learn More',
							'link'   => 'https://evnt.is/1aj1',
							'target' => '_blank',
						],
						'dismissible' => true,
					],
					[
						'id'          => '103',
						'type'        => 'warning',
						'slug'        => 'fbar-upgrade-556',
						'title'       => 'Filter Bar 5.5.6 Security Update',
						'content'     => '<p>Get the latest version of Filter Bar for important security updates.</p>',
						'cta'         => [
							'text'   => 'Update',
							'link'   => '/wp-admin/plugins.php?plugin_status=upgrade',
							'target' => '_self',
						],
						'dismissible' => false,
					],
				],
				200
			);
		} else {
			wp_send_json_error( 'Invalid nonce', 403 );
		}
	}
}
