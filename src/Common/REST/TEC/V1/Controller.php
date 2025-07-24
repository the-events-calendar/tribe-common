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
use WP_REST_Server;
use WP_REST_Request;

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
		add_filter( 'rest_pre_dispatch', [ $this, 'bind_request_object' ], 10, 3 );
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
		remove_filter( 'rest_pre_dispatch', [ $this, 'bind_request_object' ] );
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

	/**
	 * Binds the request object to the singleton.
	 *
	 * @since TBD
	 *
	 * @param mixed           $response The request object.
	 * @param WP_REST_Server  $server   The REST server.
	 * @param WP_REST_Request $request  The request object.
	 *
	 * @return WP_REST_Request
	 */
	public function bind_request_object( $response, WP_REST_Server $server, WP_REST_Request $request ) {
		$this->container->singleton( WP_REST_Request::class, $request );

		return $response;
	}
}
