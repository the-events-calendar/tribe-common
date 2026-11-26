<?php
/**
 * TrustedLogin Controller.
 *
 * Provides integration for TrustedLogin within the TEC plugin architecture,
 * handling core initialization, URL management, and template overrides.
 *
 * @since 6.9.5
 *
 * @package TEC\Common\TrustedLogin
 */

declare( strict_types=1 );

namespace TEC\Common\TrustedLogin;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;

/**
 * Controller for registering and unregistering TrustedLogin functionality.
 *
 * This controller wraps the Trusted_Login_Manager class to integrate it into
 * the larger TEC plugin architecture using the shared container.
 *
 * @since 6.9.5
 *
 * @package TEC\Common\TrustedLogin
 */
class Controller extends Controller_Contract {

	/**
	 * The TrustedLogin client filter the public key website callback was added to, kept so it is
	 * removed from that same filter even if the namespace changes in between.
	 *
	 * @since 6.14.2
	 *
	 * @var string
	 */
	private string $public_key_website_hook = '';

	/**
	 * Registers all TrustedLogin components and their dependencies.
	 *
	 * @since 6.9.5
	 *
	 * @return void
	 */
	protected function do_register(): void {
		$this->container->singleton(
			Trusted_Login_Manager::class,
			function () {
				$config = Trusted_Login_Config::build();

				return new Trusted_Login_Manager( $config );
			}
		);
		$this->hooks();
	}

	/**
	 * Unregisters the filters and actions hooks added by the controller.
	 *
	 * @since 6.9.5
	 *
	 * @return void
	 */
	public function unregister(): void {
		$this->unhook();
	}

	/**
	 * Initialize TrustedLogin via the Trusted_Login_Manager.
	 *
	 * @since 6.9.5
	 *
	 * @return void
	 */
	public function init_trustedlogin(): void {
		tribe( Trusted_Login_Manager::class )->init();
		$this->hooks();
	}

	/**
	 * Points the TrustedLogin client's vendor public key request at the URL that serves the key.
	 *
	 * @since 6.14.2
	 *
	 * @param mixed $website The vendor website URL the TrustedLogin client was configured with; a
	 *                       value another filter turned into a non-string is returned unchanged.
	 *
	 * @return mixed Website URL the public key is fetched from.
	 */
	public function filter_public_key_website( $website ) {
		if ( ! is_string( $website ) ) {
			return $website;
		}

		return $this->container->get( Trusted_Login_Config::class )->get_public_key_website_url( $website );
	}

	/**
	 * Initializes all TrustedLogin components and hooks.
	 *
	 * @since 6.9.5
	 * @since 6.14.2 Hooks the public key website filter.
	 *
	 * @return void
	 */
	protected function hooks(): void {
		tribe( Help_Hub_Integration::class )->register_hooks();
		add_action( 'tribe_common_loaded', [ $this, 'init_trustedlogin' ], 0 );

		$hook = $this->get_public_key_website_hook();

		if ( $hook !== $this->public_key_website_hook ) {
			if ( '' !== $this->public_key_website_hook ) {
				remove_filter( $this->public_key_website_hook, [ $this, 'filter_public_key_website' ] );
			}

			$this->public_key_website_hook = $hook;
		}

		add_filter( $this->public_key_website_hook, [ $this, 'filter_public_key_website' ] );
	}

	/**
	 * Unregisters all TrustedLogin components and hooks.
	 *
	 * @since 6.9.5
	 * @since 6.14.2 Removes the init action at the priority it was added with, and the public key website filter.
	 *
	 * @return void
	 */
	protected function unhook(): void {
		tribe( Help_Hub_Integration::class )->unregister_hooks();
		remove_action( 'tribe_common_loaded', [ $this, 'init_trustedlogin' ], 0 );

		if ( '' !== $this->public_key_website_hook ) {
			remove_filter( $this->public_key_website_hook, [ $this, 'filter_public_key_website' ] );
			$this->public_key_website_hook = '';
		}
	}

	/**
	 * Returns the TrustedLogin client's filter for the website the vendor public key is fetched from.
	 *
	 * The namespace is read from the final configuration and sanitized the way the client's
	 * `Config::ns()` does, since `tec_trustedlogin_config` can change it after `get_namespace()`.
	 *
	 * @since 6.14.2
	 *
	 * @return string The filter name, scoped to the TrustedLogin namespace the client uses.
	 */
	private function get_public_key_website_hook(): string {
		$config = Trusted_Login_Config::build();

		return 'trustedlogin/' . Utils::sanitize_with_dashes( $config['vendor']['namespace'] ?? '' ) . '/vendor/public_key/website';
	}
}
