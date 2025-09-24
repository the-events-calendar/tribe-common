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

/**
 * Controller for registering and unregistering TrustedLogin functionality.
 *
 * This controller wraps the Trusted_Login_Manager class to integrate it into
 * the larger TEC plugin architecture using the shared container.
 *
 * @since TBD
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

		Trusted_Login_Manager::instance()->init( $config );
	}

	/**
	 * Set up hooks for classes.
	 *
	 * @since TBD
	 */
	protected function hooks(): void {
		add_action( 'tribe_common_loaded', [ $this, 'init_trustedlogin' ], 0 );
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
