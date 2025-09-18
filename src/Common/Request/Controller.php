<?php
/**
 * Controller for request-level sanitization in TEC.
 *
 * @since TBD
 *
 * @package TEC\Request
 */

declare(strict_types=1);

namespace TEC\Common\Request;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;

/**
 * Class Controller
 *
 * @since TBD
 *
 * @package TEC\Common\Request
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
		$this->container->register( Query_Vars::class, Query_Vars::class );

		do_action( 'tec_request_controller_registered' );
	}

	/**
	 * Unregisters the filters and actions hooks added by the controller.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function unregister(): void {
		if ( ! $this->container->isBound( Query_Vars::class ) ) {
			return;
		}

		$this->container->get( Query_Vars::class )->unregister();
	}
}
