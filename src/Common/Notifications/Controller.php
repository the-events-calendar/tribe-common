<?php
/**
 * Controller for In-App Notifications.
 *
 * @since   6.4.0
 *
 * @package TEC\Common\Notifications
 */

namespace TEC\Common\Notifications;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;
use TEC\Common\Telemetry\Telemetry;
use TEC\Common\StellarWP\Telemetry\Opt_In\Status;
use Tribe__Main;

/**
 * Class Controller
 *
 * @since   6.4.0

 * @package TEC\Common\Notifications
 */
class Controller extends Controller_Contract {

	/**
	 * The custom action that will be fired when the controller registers.
	 *
	 * @since 6.5.1
	 *
	 * @var string
	 */
	public static string $registration_action = 'tec_common_ian_loaded';

	/**
	 * Registers actions and filters.
	 *
	 * @since 6.4.0
	 */
	public function do_register(): void {
		add_action( 'tribe_plugins_loaded', [ $this, 'register_ian' ], 50 );

		add_action( 'tec_ian_icon', [ $this, 'show_icon' ] );

		add_action( 'wp_ajax_ian_optin', [ $this, 'opt_in' ] );
		add_action( 'wp_ajax_ian_get_feed', [ $this, 'get_feed' ] );
		add_action( 'wp_ajax_ian_dismiss', [ $this, 'handle_dismiss' ] );
		add_action( 'wp_ajax_ian_read', [ $this, 'handle_read' ] );
		add_action( 'wp_ajax_ian_read_all', [ $this, 'handle_read_all' ] );

		add_filter( 'tribe_general_settings_debugging_section', [ $this, 'filter_tribe_general_settings_debugging_section' ], 11 );
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
		remove_action( 'wp_ajax_ian_read', [ $this, 'handle_read' ] );
		remove_action( 'wp_ajax_ian_read_all', [ $this, 'handle_read_all' ] );

		remove_filter( 'tribe_general_settings_debugging_section', [ $this, 'filter_tribe_general_settings_debugging_section' ] );
	}

	/**
	 * Register the In-App Notifications assets.
	 *
	 * @since 6.4.0
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
						'ajaxUrl' => admin_url( 'admin-ajax.php' ),
						'nonce'   => wp_create_nonce( 'common_ian_nonce' ),
						'readTxt' => esc_html__( 'Read notifications', 'tribe-common' ),
						'feed'    => (object) [
							'read'   => [],
							'unread' => [],
						],
					],
				],
			]
		);
	}

	/**
	 * Define which pages will show the notification icon.
	 *
	 * @since 6.4.0
	 *
	 * @return bool
	 */
	public function is_ian_page() {
		$screen  = get_current_screen();
		$allowed = [];

		/**
		 * Filter the allowed pages for the Notifications icon.
		 *
		 * @since 6.4.0
		 *
		 * @param array<string> $allowed The allowed pages for the Notifications icon.
		 */
		$allowed = apply_filters( 'tec_common_ian_allowed_pages', $allowed );

		/**
		 * Filter the showing of the Notifications icon.
		 *
		 * @since 6.4.0
		 *
		 * @param bool Whether to show the icon or not.
		 */
		return apply_filters( 'tec_common_ian_show_icon', in_array( $screen->id, $allowed, true ) );
	}

	/**
	 * Logic for if the Notifications icon should be shown.
	 *
	 * @since 6.4.0
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
	 * @since 6.4.0
	 */
	public function opt_in() {
		$this->container->make( Notifications::class )->opt_in();
	}

	/**
	 * AJAX handler for getting notifications.
	 *
	 * @since 6.4.0
	 */
	public function get_feed() {
		$this->container->make( Notifications::class )->get_feed();
	}

	/**
	 * AJAX handler for dismissing notifications.
	 *
	 * @since 6.4.0
	 */
	public function handle_dismiss() {
		$this->container->make( Notifications::class )->handle_dismiss();
	}

	/**
	 * AJAX handler for marking notifications as read.
	 *
	 * @since 6.4.0
	 */
	public function handle_read() {
		$this->container->make( Notifications::class )->handle_read();
	}

	/**
	 * AJAX handler for marking all notifications as read.
	 *
	 * @since 6.4.0
	 */
	public function handle_read_all() {
		$this->container->make( Notifications::class )->handle_read_all();
	}

	/**
	 * Adds the opt in/out control to the general tab debug section.
	 *
	 * @since 6.1.1
	 *
	 * @param array<string|mixed> $fields The fields for the general tab Debugging section.
	 *
	 * @return array<string|mixed> The fields, with the optin control appended.
	 */
	public function filter_tribe_general_settings_debugging_section( $fields ): array {
		$telemetry = tribe( Telemetry::class );
		$telemetry->init();
		$status = $telemetry::get_status_object();
		$opted  = $status->get( Telemetry::get_plugin_slug() );

		switch ( $opted ) {
			case Status::STATUS_ACTIVE:
				$attributes = [
					'disabled' => 'disabled',
					'checked'  => 'checked',
				];
				break;
			default:
				$attributes = [];
				break;
		}

		$tooltip = esc_html__( 'Enable this option to receive notifications about The Events Calendar, including updates, fixes, and features. This is enabled if you have opted in to Telemetry.', 'tribe-common' );

		/**
		 * Filter the tooltip text for the IAN opt-in setting.
		 *
		 * @since 6.4.0
		 */
		$tooltip = apply_filters( 'tec_common_ian_setting_optin_tooltip', $tooltip );

		$fields['ian-notifications-opt-in'] = [
			'type'            => 'checkbox_bool',
			'label'           => esc_html__( 'In-App Notifications', 'tribe-common' ),
			'tooltip'         => $tooltip,
			'default'         => false,
			'validation_type' => 'boolean',
			'attributes'      => $attributes,
		];

		return $fields;
	}
}
