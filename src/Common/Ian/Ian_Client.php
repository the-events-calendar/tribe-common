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
	 *
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function boot(): void {
		$container = Container::init();

		$ian_server = ! defined( 'STELLARWP_IAN_SERVER' ) ? 'https://ian.stellarwp.com/api/v1' : STELLARWP_IAN_SERVER;

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

		$optin = Conditionals::get_user_opt_in();

		if ( ! $optin ) {
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

		load_template( Tribe__Main::instance()->plugin_path . 'src/admin-views/ian/icon.php', true, [ 'slug' => $slug ] );
	}
}
