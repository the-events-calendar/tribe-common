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
	 * The action registration action for the request controller.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static string $registration_action = 'tec_request_controller_registered';

	/**
	 * Registers the filters and actions hooks added by the controller.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	protected function do_register(): void {
		$this->container->register( Query_Vars::class );
	}

	/**
	 * Unregisters the filters and actions hooks added by the controller.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function unregister(): void {
		$this->container->get( Query_Vars::class )->unregister();
	}
}
