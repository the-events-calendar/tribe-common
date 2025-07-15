<?php
/**
 * Endpoints Controller class.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;
use TEC\Common\REST\TEC\V1\Endpoints\OpenApiDocs;
use TEC\Common\Contracts\Container;

/**
 * Endpoints Controller class.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1
 */
class Endpoints extends Controller_Contract {
	/**
	 * The endpoints to register.
	 *
	 * @since TBD
	 *
	 * @var Endpoint_Interface[]
	 */
	protected const ENDPOINTS = [
		OpenApiDocs::class,
	];

	/**
	 * The documentation instance.
	 *
	 * @since TBD
	 *
	 * @var Documentation
	 */
	private Documentation $documentation;

	/**
	 * Endpoints constructor.
	 *
	 * @since TBD
	 *
	 * @param Container     $container     The container instance.
	 * @param Documentation $documentation The documentation instance.
	 */
	public function __construct( Container $container, Documentation $documentation ) {
		parent::__construct( $container );
		$this->documentation = $documentation;
	}

	/**
	 * Registers the filters and actions hooks added by the controller.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	protected function do_register(): void {
		foreach ( self::ENDPOINTS as $endpoint ) {
			$this->container->singleton( $endpoint );
		}

		add_action( 'rest_api_init', [ $this, 'register_endpoints' ] );
	}

	/**
	 * Unregisters the filters and actions hooks added by the controller.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function unregister(): void {
		remove_action( 'rest_api_init', [ $this, 'register_endpoints' ] );
	}

	/**
	 * Registers the endpoints.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function register_endpoints(): void {
		foreach ( self::ENDPOINTS as $endpoint ) {
			$endpoint = $this->container->get( $endpoint );
			$endpoint->register_routes();
			$this->documentation->register_endpoint( $endpoint );
		}
	}
}
