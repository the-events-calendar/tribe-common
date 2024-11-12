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
		$api = 'https://ian.stellarwp.com/feed/organization/brand/product.json';

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
	 */
	public function opt_in() {
		if ( wp_verify_nonce( tribe_get_request_var( 'nonce' ), 'common_ian_nonce' ) ) {

			tribe_update_option( 'ian-notifications-opt-in', 1 );

			wp_send_json_success( esc_html__( 'Notifications opt-in successful', 'tribe-common' ), 200 );
		} else {
			wp_send_json_error( esc_html__( 'Invalid nonce', 'tribe-common' ), 403 );
		}
	}

	/**
	 * Get the IAN notifications.
	 *
	 * @since TBD
	 */
	public function get_feed() {
		if ( ! wp_verify_nonce( tribe_get_request_var( 'nonce' ), 'common_ian_nonce' ) ) {
			wp_send_json_error( esc_html__( 'Invalid nonce', 'tribe-common' ), 403 );
		}

		// TODO: Below is an example notifications array. Send the real one from Laravel.
		$feed = [
			[
				'id'          => '101',
				'type'        => 'update',
				'slug'        => 'tec-update-664',
				'title'       => 'The Events Calendar 6.6.4 Update',
				'content'     => '<p>The latest update of The Events Calendar adds an option to allow for duplicate Venue creation, updates custom table query logic to avoid DB error, and logic that displays the “REST API blocked” banner to prevent false positives.</p>',
				'actions'     => [
					[
						'text'   => 'See Details',
						'link'   => 'https://evnt.is/1ai-',
						'target' => '_blank',
					],
					[
						'text'   => 'Update Now',
						'link'   => '/wp-admin/update-core.php',
						'target' => '_self',
					],
				],
				'dismissible' => true,
			],
			[
				'id'          => '102',
				'type'        => 'notice',
				'slug'        => 'event-tickets-upsell',
				'title'       => 'Sell Tickets & Collect RSVPs with Event Tickets',
				'content'     => '<p>Sell tickets, collect RSVPs and manage attendees for free.</p>',
				'actions'     => [
					[
						'text'   => 'Learn More',
						'link'   => 'https://evnt.is/1aj1',
						'target' => '_blank',
					],
				],
				'dismissible' => true,
			],
			[
				'id'          => '103',
				'type'        => 'warning',
				'slug'        => 'fbar-upgrade-556',
				'title'       => 'Filter Bar 5.5.6 Security Update',
				'content'     => '<p>Get the latest version of Filter Bar for important security updates.</p>',
				'actions'     => [
					[
						'text'   => 'Update',
						'link'   => '/wp-admin/plugins.php?plugin_status=upgrade',
						'target' => '_self',
					],
				],
				'dismissible' => false,
			],
		];

		$notifications = Conditionals::filter_feed( $feed );

		$template = new Template();
		foreach ( $notifications as $k => $notification ) {
			$this->slug = $notification['slug'];
			if ( $this->has_user_dismissed() ) {
				unset( $notifications[ $k ] );
				continue;
			}
			$html = $template->render_notification( $notification, false );

			$notifications[ $k ]['html'] = $html;
		}

		array_values( $notifications );

		wp_send_json_success( $notifications, 200 );
	}

	/**
	 * AJAX handler for dismissing IAN notifications.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function handle_dismiss(): void {
		$id = tribe_get_request_var( 'id' );

		if ( ! wp_verify_nonce( tribe_get_request_var( 'nonce' ), 'ian_nonce_' . $id ) ) {
			wp_send_json_error( esc_html__( 'Invalid nonce', 'tribe-common' ), 403 );
		}

		$slug = tribe_get_request_var( 'slug' );

		if ( empty( $slug ) ) {
			wp_send_json_error( esc_html__( 'Invalid notification slug', 'tribe-common' ), 403 );
		}

		$this->slug = $slug;
		$this->dismiss();

		wp_send_json_success( esc_html__( 'Notification dismissed', 'tribe-common' ), 200 );
	}
}
