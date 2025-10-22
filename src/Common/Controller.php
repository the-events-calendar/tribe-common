<?php
/**
 * TEC Common Controller - For all new sub-controllers.
 *
 * @since 6.5.4
 *
 * @package TEC\Common;
 */

namespace TEC\Common;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;
use TEC\Common\Json_Packer\Json_Packer;
use TEC\Common\Lists\Country as Country_List;
use TEC\Common\REST\Controller as REST_Controller;

/**
 * Class Controller
 *
 * @since 6.5.4
 */
class Controller extends Controller_Contract {

	/**
	 * Registers the filters and actions hooks added by the controller.
	 *
	 * @since 6.5.4
	 *
	 * @return void
	 */
	protected function do_register(): void {
		$this->container->singleton( Template::class );
		$this->container->singleton( Country_List::class );
		$this->container->singleton( Json_Packer::class );

		$this->container->register( Hooks::class );
		$this->container->register( Key_Value_Cache\Controller::class );
		$this->container->register( SVG\Controller::class );

		// Load controllers after all common libs are loaded and initial hooks are in place.
		add_action( 'tribe_common_loaded', [ $this, 'load_controllers' ] );
	}

	/**
	 * Load controllers after all common libs are loaded and initial hooks are in place.
	 *
	 * @since 6.9.0
	 *
	 * @return void
	 */
	public function load_controllers(): void {
		$this->container->register( AI\Controller::class );
		$this->container->register( REST_Controller::class );
	}

	/**
	 * Removes the filters and actions hooks added by the controller.
	 *
	 * @since 6.5.4
	 *
	 * @return void Filters and actions hooks added by the controller are be removed.
	 */
	public function unregister(): void {
		$this->container->get( Hooks::class )->unregister();
		$this->container->get( Key_Value_Cache\Controller::class )->unregister();
		$this->container->get( REST_Controller::class )->unregister();
		$this->container->get( AI\Controller::class )->unregister();
		$this->container->get( SVG\Controller::class )->unregister();
	}
}
