<?php
/**
 * The main controller class for the REST API Classy integration.
 *
 * @since TBD
 *
 * @package TEC\Common\Classy\REST;
 */

declare( strict_types=1 );

namespace TEC\Common\Classy\REST;

use TEC\Classy\REST\Endpoints\Options\Currencies;
use TEC\Common\Classy\REST\Endpoints\Options\Country;
use TEC\Common\Contracts\Provider\Controller as Controller_Contract;
use TEC\Common\lucatume\DI52\ContainerException;
use Tribe__Languages__Locations as Locations;
use WP_REST_Server as Server;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
		$this->container->singleton( Locations::class, static fn() => tribe( 'languages.locations' ) );
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
					'methods'             => Server::READABLE,
					'callback'            => $this->container->callback( Country::class, 'get' ),
					'permission_callback' => $this->get_permission_callback(),
					'args'                => [],
					'description'         => 'Returns a list of country choice options.',
				],
			]
		);

		register_rest_route(
			self::REST_NAMESPACE,
			'/options/us-states',
			[
				[
					'methods'             => Server::READABLE,
					'callback'            => $this->container->callback( US_States::class, 'get' ),
					'permission_callback' => $this->get_permission_callback(),
					'args'                => [],
					'description'         => 'Returns a list of country choice options.',
				],
			]
		);

		register_rest_route(
			self::REST_NAMESPACE,
			'/options/currencies',
			[
				[
					'methods'             => Server::READABLE,
					'callback'            => $this->container->callback( Currencies::class, 'get' ),
					'permission_callback' => $this->get_permission_callback(),
					'args'                => [],
					'description'         => 'Returns a list of currency choice options.',
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

	/**
	 * Returns the permission callback for the REST API routes.
	 *
	 * @since TBD
	 *
	 * @return callable
	 */
	protected function get_permission_callback(): callable {
		return static fn() => current_user_can( 'edit_posts' );
	}
}
