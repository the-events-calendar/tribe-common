<?php
/**
 * The main controller class for the REST API Classy integration.
 *
 * @since TBD
 *
 * @package TEC\Common\Classy\REST;
 */

namespace TEC\Common\Classy\REST;

use TEC\Common\Classy\REST\Endpoints\Options\Country;
use TEC\Common\lucatume\DI52\ContainerException;
use Tribe__Languages__Locations as Locations;
use WP_REST_Server;
use TEC\Common\Contracts\Provider\Controller as Controller_Contract;

/**
 * Class Controller.
 *
 * @since TBD
 *
 * @package TEC\Common\Classy\REST;
 */
class Controller extends Controller_Contract {

	const REST_NAMESPACE = 'tec/classy/v1';

	/**
	 * Subscribes the controller to the WordPress hooks it needs to operate, binds implementations.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	protected function do_register(): void {
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );

		// Since the Location class is bound to a slug, rebind it to the class name to enable auto-injection.
		$this->container->singleton( Locations::class, fn() => tribe( 'languages.locations' ) );
	}

	/**
	 * Register the routes for the REST API.
	 *
	 * @since TBD
	 *
	 * @return void This REST routes are registered.
	 *
	 * @throws ContainerException If one of the endpoint handlers cannot be reolved at runtime.
	 */
	public function register_routes(): void {
		register_rest_route(
			self::REST_NAMESPACE,
			'/options/country',
			[
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => $this->container->callback( Country::class, 'get' ),
					'permission_callback' => static function (): bool {
						return current_user_can( 'edit_posts' );
					},
					'args'                => [],
					'description'         => 'Returns a list of country choice options.',
				],
			]
		);
	}

	/**
	 * Unregisters the REST routes.
	 *
	 * @since TBD
	 *
	 * @return void The REST routes are unregistered.
	 */
	public function unregister(): void {
		remove_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}
}
