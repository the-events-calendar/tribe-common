<?php
/**
 * TrustedLogin Help Hub Integration.
 *
 * Handles Help Hub tab registration and template overrides for TrustedLogin
 * functionality within the TEC Help Hub interface.
 *
 * @since 6.9.5
 *
 * @package TEC\Common\TrustedLogin
 */

declare( strict_types=1 );

namespace TEC\Common\TrustedLogin;

use TEC\Common\Admin\Help_Hub\Tab_Builder;
use Tribe__Main;
use Tribe__Template;

/**
 * Manages TrustedLogin integration with the Help Hub interface.
 *
 * This class handles:
 * - Help Hub tab registration
 * - Template override for TrustedLogin auth screen
 * - Template context and rendering
 *
 * @since 6.9.5
 *
 * @package TEC\Common\TrustedLogin
 */
class Help_Hub_Integration {

	/**
	 * Cached template instance.
	 *
	 * @since 6.9.5
	 *
	 * @var Tribe__Template|null
	 */
	private ?Tribe__Template $template = null;

	/**
	 * Registers hooks for Help Hub integration.
	 *
	 * @since 6.9.5
	 *
	 * @return void
	 */
	public function register_hooks(): void {
		add_filter( 'tec_help_hub_register_tabs', [ $this, 'register_help_hub_tab' ] );
		add_filter( 'trustedlogin/the-events-calendar/template/auth', [ $this, 'override_auth_template' ] );
		add_action( 'admin_init', [ $this, 'handle_revoke_redirect' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'inject_redirect_override_script' ], 20 );
	}

	/**
	 * Unregisters hooks for Help Hub integration.
	 *
	 * @since 6.9.5
	 *
	 * @return void
	 */
	public function unregister_hooks(): void {
		remove_filter( 'tec_help_hub_register_tabs', [ $this, 'register_help_hub_tab' ] );
		remove_filter( 'trustedlogin/the-events-calendar/template/auth', [ $this, 'override_auth_template' ] );
		remove_action( 'admin_init', [ $this, 'handle_revoke_redirect' ] );
		remove_action( 'admin_enqueue_scripts', [ $this, 'inject_redirect_override_script' ], 20 );
	}

	/**
	 * Registers the "Support Access" tab in the Help Hub interface.
	 *
	 * This tab loads the TrustedLogin view template, allowing users to
	 * grant or revoke support access directly from the Help Hub.
	 *
	 * @since 6.9.5
	 *
	 * @param Tab_Builder $builder The Help Hub Tab Builder instance.
	 *
	 * @return Tab_Builder The modified Tab Builder instance with the new tab.
	 */
	public function register_help_hub_tab( Tab_Builder $builder ) {
		$builder::make(
			'tec-support-access-tab',
			__( 'Support Access', 'tribe-common' ),
			'tec-support-access-tab',
			'help-hub/trusted-login/view'
		)->build();

		return $builder;
	}

	/**
	 * Overrides the TrustedLogin auth template with custom Help Hub template.
	 *
	 * Hook into the TrustedLogin template filter to replace the default
	 * auth template with a custom one designed for the Help Hub interface.
	 *
	 * @since 6.9.5
	 *
	 * @return string The custom Help Hub template HTML.
	 */
	public function override_auth_template() {
		return $this->get_template()->template( 'help-hub/trusted-login/auth-template', [], false );
	}

	/**
	 * Injects JavaScript to override TrustedLogin's reload URL.
	 *
	 * TrustedLogin's JS reloads the page after granting access using the URL
	 * stored in `tl_obj.query_string`. By default, this points to the current page.
	 * We override it here so that users return to the Help Hub → Support Access tab
	 * after granting or revoking access.
	 *
	 * @since 6.9.5
	 *
	 * @return void
	 */
	public function inject_redirect_override_script(): void {
		// Build redirect URL for the Help Hub tab.
		$redirect_url = $this->get_help_hub_redirect_url();

		// Inline JS logic only, no data injected directly.
		$inline_js = <<<JS
		if (typeof tl_obj !== 'undefined' && typeof tecTrustedLoginVars !== 'undefined') {
			tl_obj.query_string = tecTrustedLoginVars.redirectUrl;
		}
		JS;

		// Safely pass data to JavaScript.
		wp_localize_script(
			'trustedlogin-the-events-calendar',
			'tecTrustedLoginVars',
			[ 'redirectUrl' => $redirect_url ]
		);

		// Append inline JS after the script loads.
		wp_add_inline_script(
			'trustedlogin-the-events-calendar',
			$inline_js,
			'after'
		);
	}

	/**
	 * Handles redirect after TrustedLogin access revocation.
	 *
	 * When TrustedLogin access is revoked, the default behavior redirects users
	 * to the WP Admin Dashboard. We intercept this and send them to the Help Hub
	 * → Support Access tab instead for better UX.
	 *
	 * @since 6.9.5
	 *
	 * @return void
	 */
	public function handle_revoke_redirect(): void {
		$revoke_tl = tec_get_request_var( 'revoke-tl', null );

		// Bail if not in admin or if query param doesn't exist.
		if ( ! is_admin() || empty( $revoke_tl ) ) {
			return;
		}

		// Only redirect for The Events Calendar.
		if ( 'the-events-calendar' !== sanitize_text_field( wp_unslash( $revoke_tl ) ) ) {
			return;
		}

		// Redirect to the previous page or to Help Hub → Support Access tab in Events context.
		// @TODO: There has to be a better way, but this will get the job done for now.
		$redirect_url = $_SERVER['HTTP_REFERER'] ?? admin_url(); // phpcs:ignore WordPress.Security.NonceVerification.Recommended,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		wp_safe_redirect( $redirect_url );
		tribe_exit();
	}

	/**
	 * Generates the Help Hub redirect URL for Support Access tab.
	 *
	 * @since 6.9.5
	 *
	 * @return string The complete Help Hub redirect URL.
	 */
	private function get_help_hub_redirect_url(): string {
		$url  = admin_url( 'admin.php' );
		$page = tec_get_request_var( 'page' );
		$args = [
			'tab'  => 'tec-support-access-tab',
			'page' => $page,
		];

		$post_type = tec_get_request_var( 'post_type', false );

		if ( 'tribe_events' === $post_type ) {
			$args['post_type'] = 'tribe_events';
			$url               = admin_url( 'edit.php' );
		}

		return add_query_arg( $args, $url );
	}

	/**
	 * Retrieves the configured Tribe Template instance.
	 *
	 * Sets the template origin and folder to the common admin-views directory
	 * so that we can load custom views for TrustedLogin screens.
	 *
	 * @since 6.9.5
	 *
	 * @return Tribe__Template Configured template instance.
	 */
	private function get_template(): Tribe__Template {
		if ( $this->template === null ) {
			$main           = Tribe__Main::instance();
			$this->template = new Tribe__Template();
			$this->template->set_template_origin( $main );
			$this->template->set_template_folder( 'src/admin-views' );
			$this->template->set_template_context_extract( true );
		}

		return $this->template;
	}
}
