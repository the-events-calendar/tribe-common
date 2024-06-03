<?php
namespace TEC\Common\Libraries\Uplink;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;
use TEC\Common\StellarWP\Uplink\Config;
use TEC\Common\StellarWP\Uplink\Uplink;

/**
 * Controller for setting up the stellarwp/uplink library.
 *
 * @since TBD
 *
 * @package TEC\Common\Libraries\Uplink
 */
class Controller extends Controller_Contract {
	/**
	 * Register the controller.
	 *
	 * @since TBD
	 */
	public function do_register(): void {
		$this->add_actions();
	}

	/**
	 * Unregister the controller.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function unregister(): void {
		$this->remove_actions();
	}

	/**
	 * Add the action hooks.
	 *
	 * @since TBD
	 */
	public function add_actions(): void {
		add_action( 'plugins_loaded', [ $this, 'register_uplink' ] );
	}

	/**
	 * Remove the action hooks.
	 *
	 * @since TBD
	 */
	public function remove_actions(): void {
		remove_action( 'plugins_loaded', [ $this, 'register_uplink' ] );
	}

	/**
	 * Register the uplink library.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function register_uplink(): void {
		Config::set_container( tribe() );
		Config::set_hook_prefix( 'tec' );
		Config::set_token_auth_prefix( 'tec' );
		Uplink::init();
	}
}
