<?php
/**
 * TrustedLogin Controller.
 *
 * Provides integration for TrustedLogin within the TEC plugin architecture,
 * handling registration and unregistration of related hooks via the container.
 *
 * @since TBD
 *
 * @package TEC\Common\TrustedLogin
 */

declare( strict_types=1 );

namespace TEC\Common\TrustedLogin;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;
use Tribe__Main;
use Tribe__Template;

/**
 * Controller for registering and unregistering TrustedLogin functionality.
 *
 * This controller wraps the Trusted_Login_Manager class to integrate it into
 * the larger TEC plugin architecture using the shared container.
 *
 * @since TBD
 *
 * @package TEC\Common\TrustedLogin
 */
class Controller extends Controller_Contract {

	/**
	 * Registers the filters and actions hooks added by the controller.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	protected function do_register(): void {
		$this->container->singleton( Trusted_Login_Manager::class );

		$this->hooks();
	}

	/**
	 * Unregisters the filters and actions hooks added by the controller.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function unregister(): void {
		$this->unhook();
	}

	/**
	 * Initialize TrustedLogin via the Trusted_Login_Manager.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function init_trustedlogin(): void {
		$config = Trusted_Login_Config::build();

		if ( empty( $config ) ) {
			return;
		}

		tribe( Trusted_Login_Manager::class )->init( $config );
	}

	/**
	 * Set up hooks for classes.
	 *
	 * @since TBD
	 */
	protected function hooks(): void {
		add_action( 'tribe_common_loaded', [ $this, 'init_trustedlogin' ], 0 );
		add_action( 'admin_init', [ $this, 'tec_trustedlogin_revoke_redirect' ] );
		add_filter( 'tec_help_hub_register_tabs', [ $this, 'help_hub_tabs' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'override_trustedlogin_reload_url' ], 20 );
		add_filter( 'trustedlogin/the-events-calendar/template/auth', [ $this, 'get_auth_template' ] );
	}

	/**
	 * Alter TrustedLogin Auth template data.
	 *
	 * Hook into the TrustedLogin template filter to inspect the HTML
	 * and arguments before rendering.
	 *
	 * @since TBD
	 *
	 * @param string $html The default TrustedLogin template HTML.
	 *
	 * @return string The modified or original HTML.
	 */
	public function get_auth_template( $html ) {
		return $this->get_template()->template( 'help-hub/trusted-login/auth-template', [], false );
	}

	/**
	 * Registers the "Support Access" tab in the Help Hub interface.
	 *
	 * This tab loads the TrustedLogin view template, allowing users to
	 * grant or revoke support access directly from the Help Hub.
	 *
	 * @since TBD
	 *
	 * @param object $builder The Help Hub Tab Builder instance.
	 *
	 * @return object The modified Tab Builder instance with the new tab.
	 */
	public function help_hub_tabs( $builder ) {
		$builder::make(
			'tec-support-access-tab',
			__( 'Support Access', 'tribe-common' ),
			'tec-support-access-tab',
			'help-hub/trusted-login/view'
		)->build();

		return $builder;
	}

	/**
	 * Override the TrustedLogin reload URL to redirect back to the Help Hub tab.
	 *
	 * TrustedLogin's JS reloads the page after granting access using the URL
	 * stored in `tl_obj.query_string`. By default, this points to the current page.
	 * We override it here so that users return to the Help Hub → Support Access tab
	 * after granting or revoking access.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function override_trustedlogin_reload_url(): void {
		// Build redirect URL for the Help Hub tab.
		$redirect_url = add_query_arg(
			[
				'post_type' => 'tribe_events',
				'page'      => 'tec-events-help-hub',
				'tab'       => 'tec-support-access-tab',
			],
			admin_url( 'edit.php' )
		);

		// Safely JSON encode the URL for inline JS.
		$redirect_url_json = wp_json_encode( $redirect_url );


		// Generate the inline JS.
		$inline_js = <<<JS
		if (typeof tl_obj !== 'undefined') {
			tl_obj.query_string = {$redirect_url_json};
		}
		JS;

		// Inject the script after TrustedLogin's script loads.
		wp_add_inline_script(
			'trustedlogin-the-events-calendar',
			$inline_js,
			'after'
		);
	}

	/**
	 * Redirect TrustedLogin revoke requests back to the Help Hub tab.
	 *
	 * When TrustedLogin access is revoked, the default behavior redirects users
	 * to the WP Admin Dashboard. We intercept this and send them to the Help Hub
	 * → Support Access tab instead for better UX.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function tec_trustedlogin_revoke_redirect() {
		$revoke_tl = tec_get_request_var( 'revoke-tl', null );
		// Bail if not in admin or if query param doesn't exist.
		if ( ! is_admin() || empty( $revoke_tl ) ) {
			return;
		}

		// Only redirect for The Events Calendar.
		if ( 'the-events-calendar' !== sanitize_text_field( wp_unslash( $revoke_tl ) ) ) {
			return;
		}

		// Redirect to Help Hub → Support Access tab in Events context.
		$redirect_url = add_query_arg(
			[
				'post_type' => 'tribe_events',
				'page'      => 'tec-events-help-hub',
				'tab'       => 'tec-support-access-tab',
			],
			admin_url( 'edit.php' )
		);

		// phpcs:ignore WordPressVIPMinimum.Security.ExitAfterRedirect.NoExit
		wp_safe_redirect( $redirect_url );
		tribe_exit();
	}

	/**
	 * Retrieves the Tribe Template instance used to render TrustedLogin templates.
	 *
	 * Sets the template origin and folder to the common admin-views directory
	 * so that we can load custom views for TrustedLogin screens.
	 *
	 * @since TBD
	 *
	 * @return Tribe__Template Configured template instance.
	 */
	protected function get_template() {
		$main     = Tribe__Main::instance();
		$template = new Tribe__Template();
		$template->set_template_origin( $main );
		$template->set_template_folder( 'src/admin-views' );
		$template->set_template_context_extract( true );

		return $template;
	}

	/**
	 * Remove hooks for classes.
	 *
	 * @since TBD
	 */
	protected function unhook(): void {
		remove_action( 'tribe_common_loaded', [ $this, 'init_trustedlogin' ] );
	}
}
