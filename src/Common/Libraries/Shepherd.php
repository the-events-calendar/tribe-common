<?php
/**
 * The Controller to set up the Uplink library.
 */

namespace TEC\Common\Libraries;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;
use TEC\Common\Libraries\Provider as Libraries_Provider;
use TEC\Common\StellarWP\Shepherd\Provider as Shepherd_Provider;
use TEC\Common\StellarWP\Shepherd\Config;

/**
 * Controller for setting up the Shepherd library.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\Libraries\Shepherd
 */
class Shepherd extends Controller_Contract {
	/**
	 * Register the controller.
	 *
	 * @since 6.9.0
	 */
	public function do_register(): void {
		Config::set_container( $this->container );
		Config::set_hook_prefix( tribe( Libraries_Provider::class )->get_hook_prefix() );

		$this->container->register( Shepherd_Provider::class );
	}

	/**
	 * Unregister the controller.
	 *
	 * @since 6.9.0
	 *
	 * @return void
	 */
	public function unregister(): void {
		// Nothing to do.
	}
}
