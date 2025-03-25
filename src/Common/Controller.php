<?php
/**
 * TEC Common Controller - For all new sub-controllers.
 *
 * @since TBD
 *
 * @package TEC\Common;
 */

namespace TEC\Common;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;

/**
 * Class Controller
 *
 * @since TBD
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
		$this->container->singleton( Template::class );
		$this->container->register( Hooks::class );
	}

	/**
	 * Removes the filters and actions hooks added by the controller.
	 *
	 * @since TBD
	 *
	 * @return void Filters and actions hooks added by the controller are be removed.
	 */
	public function unregister(): void {
		$this->container->get( Hooks::class )->unregister();
	}
}
