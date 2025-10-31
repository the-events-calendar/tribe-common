<?php
/**
 * Controller for the TEC REST API.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST
 */

declare( strict_types=1 );

namespace TEC\Common\REST;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;
use TEC\Common\REST\TEC\V1\Controller as V1_Controller;

/**
 * Controller for the TEC REST API.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST
 */
class Controller extends Controller_Contract {
	/**
	 * The namespace of the REST API.
	 *
	 * @since 6.9.0
	 *
	 * @var string
	 */
	public const NAMESPACE = 'tec';

	/**
	 * Registers the filters and actions hooks added by the controller.
	 *
	 * @since 6.9.0
	 *
	 * @return void
	 */
	protected function do_register(): void {
		$this->container->register( V1_Controller::class );
	}

	/**
	 * Unregisters the filters and actions hooks added by the controller.
	 *
	 * @since 6.9.0
	 *
	 * @return void
	 */
	public function unregister(): void {
		$this->container->get( V1_Controller::class )->unregister();
	}
}
