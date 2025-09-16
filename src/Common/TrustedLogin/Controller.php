<?php
/**
 * TrustedLogin Controller.
 *
 * Provides integration for TrustedLogin within the TEC plugin architecture,
 * handling registration and unregistration of related hooks via the container.
 *
 * @since   6.9.0
 *
 * @package TEC\Common\TrustedLogin
 */

declare( strict_types=1 );

namespace TEC\Common\TrustedLogin;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;
use Tribe__Main;

/**
 * Controller for registering and unregistering TrustedLogin functionality.
 *
 * This controller wraps the Trusted_Login_Manager class to integrate it into
 * the larger TEC plugin architecture using the shared container.
 *
 * @since   TBD
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
		$this->container->get( Trusted_Login_Manager::class )->unregister();
	}

	/**
	 * Initialize TrustedLogin via the Trusted_Login_Manager.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function init_trustedlogin(): void {
		$config = $this->get_config();

		if ( empty( $config ) || ! class_exists( Trusted_Login_Manager::class ) ) {
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
	 * Build TrustedLogin configuration.
	 *
	 * @see   https://docs.trustedlogin.com/Client/configuration#all-options
	 *
	 * @since TBD
	 *
	 * @return array<string,mixed>
	 */
	protected function get_config(): array {
		$logo_source = tribe_resource_url( 'images/logo/the-events-calendar.svg', false, null, Tribe__Main::instance() );

		return [
			'auth'   => [
				'api_key'     => '1d9fc7a576cb88ed',
				'license_key' => '',
			],
			'vendor' => [
				'namespace'   => 'tec-common', // Matches namespace used in the composer.json file.
				'title'       => 'The Events Calendar',
				'logo_url'    => $logo_source,
				'email'       => 'support@theeventscalendar.com',
				'support_url' => 'https://theeventscalendar.com/support/',
				'website'     => 'https://theeventscalendar.com',
			],
			'menu' => false,
			'decay'      => WEEK_IN_SECONDS,
			'role'       => 'administrator',
			'        ' => false,
		];
	}
}
