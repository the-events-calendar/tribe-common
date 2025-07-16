<?php
/**
 * Controller for the TEC REST API.
 *
 * @since TBD
 *
 * @package TEC\Common\REST
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;
use TEC\Common\REST\Controller as REST_Controller;

/**
 * Controller for the TEC REST API.
 *
 * @since TBD
 *
 * @package TEC\Common\REST
 */
class Controller extends Controller_Contract {
	/**
	 * The version of the REST API.
	 *
	 * This is being used in the namespace to avoid conflicts with other versions of the API.
	 *
	 * e.g. /wp-json/tec/v1/
	 *
	 * @since TBD
	 *
	 * @var int
	 */
	public const VERSION = 1;

	/**
	 * Registers the filters and actions hooks added by the controller.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	protected function do_register(): void {
		$this->container->singleton( Documentation::class );
		$this->container->register( Endpoints::class );
	}

	/**
	 * Unregisters the filters and actions hooks added by the controller.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function unregister(): void {
		$this->container->get( Endpoints::class )->unregister();
	}

	/**
	 * Returns the namespace of the REST API.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public static function get_versioned_namespace(): string {
		return REST_Controller::NAMESPACE . '/v' . self::VERSION;
	}
}
