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
use TEC\Common\Lists\Country as Country_List;

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
		$this->container->register( Hooks::class );
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
	}
}
