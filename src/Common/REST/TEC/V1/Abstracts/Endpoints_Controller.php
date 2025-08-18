<?php
/**
 * Endpoints Controller abstract class.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Abstracts
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Abstracts;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;
use TEC\Common\REST\TEC\V1\Contracts\Endpoint_Interface;
use TEC\Common\REST\TEC\V1\Contracts\Definition_Interface;
use TEC\Common\REST\TEC\V1\Contracts\Endpoints_Controller_Interface;
use TEC\Common\REST\TEC\V1\Contracts\Tag_Interface;
use TEC\Common\REST\TEC\V1\Documentation;
use RuntimeException;

/**
 * Endpoints Controller abstract class.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Abstracts
 */
abstract class Endpoints_Controller extends Controller_Contract implements Endpoints_Controller_Interface {
	/**
	 * Registers the filters and actions hooks added by the controller.
	 *
	 * @since 6.9.0
	 *
	 * @return void
	 */
	protected function do_register(): void {
		foreach ( $this->get_endpoints() as $endpoint ) {
			$this->container->singleton( $endpoint );
		}

		foreach ( $this->get_tags() as $tag ) {
			$this->container->singleton( $tag );
		}

		foreach ( $this->get_definitions() as $definition ) {
			$this->container->singleton( $definition );
		}

		add_action( 'rest_api_init', [ $this, 'register_endpoints' ] );
	}

	/**
	 * Unregisters the filters and actions hooks added by the controller.
	 *
	 * @since 6.9.0
	 *
	 * @return void
	 */
	public function unregister(): void {
		remove_action( 'rest_api_init', [ $this, 'register_endpoints' ] );
	}

	/**
	 * Registers the endpoints.
	 *
	 * @since 6.9.0
	 *
	 * @return void
	 *
	 * @throws RuntimeException If the endpoint or definition does not implement the required interface.
	 */
	public function register_endpoints(): void {
		$documentation = $this->container->get( Documentation::class );

		foreach ( $this->get_endpoints() as $endpoint ) {
			$endpoint = $this->container->get( $endpoint );
			if ( ! $endpoint instanceof Endpoint_Interface ) {
				throw new RuntimeException( 'Endpoint must implement Endpoint_Interface' );
			}

			$endpoint->register_routes();
			$documentation->register_endpoint( $endpoint );
		}

		foreach ( $this->get_definitions() as $definition ) {
			$definition = $this->container->get( $definition );
			if ( ! $definition instanceof Definition_Interface ) {
				throw new RuntimeException( 'Definition must implement Definition_Interface' );
			}

			$documentation->register_definition( $definition );
		}

		foreach ( $this->get_tags() as $tag ) {
			$tag = $this->container->get( $tag );
			if ( ! $tag instanceof Tag_Interface ) {
				throw new RuntimeException( 'Tag must implement Tag_Interface' );
			}

			$documentation->register_tag( $tag );
		}
	}
}
