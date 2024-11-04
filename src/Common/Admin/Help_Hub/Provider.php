<?php
/**
 * Provider for the Help Hub.
 *
 * Registers the required dependencies and factory for the Help Hub functionality,
 * allowing components such as the `Hub` class to retrieve and utilize the required
 * data and configuration through dependency injection.
 *
 * @since   TBD
 * @package TEC\Common\Admin\Help_Hub
 */

namespace TEC\Common\Admin\Help_Hub;

use TEC\Common\Configuration\Configuration;
use TEC\Common\Contracts\Service_Provider;
use TEC\Events\Admin\Help_Hub\TEC_Hub_Resource_Data;
use Tribe__Template;

/**
 * Class Provider
 *
 * Registers the Help Hub logic and dependencies, allowing for easier dependency management
 * and a centralized setup for Help Hub-specific functionality.
 *
 * @since   TBD
 *
 * @package TEC\Common\Admin\Help_Hub
 */
class Provider extends Service_Provider {

	/**
	 * Registers the functionality related to this module, including binding
	 * the Help Hub Factory, TEC and ET data classes in the DI container.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function register(): void {
		// Register the provider instance as a singleton within the container.
		$this->container->singleton( self::class, $this );
		$this->container->bind( Hub::class );

		/**
		 * Fires when the provider is registered.
		 *
		 * @since TBD
		 *
		 * @param Provider $this The provider instance.
		 */
		do_action( 'tec_help_hub_registered', $this );
	}
}
