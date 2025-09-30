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
	 * Initializes all TrustedLogin components and hooks.
	 *
	 * @since 6.9.5
	 *
	 * @return void
	 */
	protected function hooks(): void {
		tribe( Help_Hub_Integration::class )->register_hooks();
		add_action( 'tribe_common_loaded', [ $this, 'init_trustedlogin' ], 0 );
	}

	/**
	 * Unregisters all TrustedLogin components and hooks.
	 *
	 * @since 6.9.5
	 *
	 * @return void
	 */
	protected function unhook(): void {
		tribe( Help_Hub_Integration::class )->unregister_hooks();
		remove_action( 'tribe_common_loaded', [ $this, 'init_trustedlogin' ] );
	}
}
