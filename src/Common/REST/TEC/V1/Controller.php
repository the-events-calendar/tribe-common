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
use Tribe__Main as TCMN;
use Tribe__Events__Main as TEC;
use Tribe__Tickets__Main as ET;
use Tribe__Events__Pro__Main as ECP;
use Tribe__Tickets_Plus__Main as ETP;
use InvalidArgumentException;
use Tribe__Cache as Cache;

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

	/**
	 * Returns the cache key for the implementation.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public static function get_implementation_cache_key(): string {
		$versions = [
			TCMN::VERSION,
		];

		if ( did_action( 'tec_events_fully_loaded' ) ) {
			$versions[] = TEC::VERSION;
		}

		if ( did_action( 'tec_tickets_fully_loaded' ) ) {
			$versions[] = ET::VERSION;
		}

		if ( did_action( 'tec_tickets_plus_fully_loaded' ) ) {
			$versions[] = ETP::VERSION;
		}

		if ( did_action( 'tec_events_pro_fully_loaded' ) ) {
			$versions[] = ECP::VERSION;
		}

		// Will always be 48 chars. As a result whatever we add here should not be longer than 172 - 48 = 124 chars.
		return 'tec_rest_' . self::get_versioned_namespace() . '_' . md5( implode( '', $versions ) );
	}

	/**
	 * Returns the cache key for the implementation.
	 *
	 * @since TBD
	 *
	 * @param string $key The key to add to the cache key.
	 *
	 * @return string
	 *
	 * @throws InvalidArgumentException If the cache key is longer than 123 characters.
	 */
	public static function get_cache_key( string $key ): string {
		if ( strlen( $key ) > 123 ) {
			throw new InvalidArgumentException( 'The cache key must be less than 123 characters.' );
		}

		return self::get_implementation_cache_key() . '_' . $key;
	}

	/**
	 * Returns the cache for the REST API.
	 *
	 * @since TBD
	 *
	 * @param string $key The key to get the cache for.
	 *
	 * @return mixed
	 */
	public static function get_rest_cache( string $key ) {
		/** @var Cache $cache */
		$cache = tribe_cache();

		$cache_key = self::get_cache_key( $key );

		return wp_using_ext_object_cache() ? $cache->get( $cache_key ) : $cache->get_transient( $cache_key );
	}

	/**
	 * Sets the cache for the REST API.
	 *
	 * @since TBD
	 *
	 * @param string $key   The key to set the cache for.
	 * @param mixed  $value The value to set the cache for.
	 *
	 * @return void
	 */
	public static function set_rest_cache( string $key, $value ) {
		/** @var Cache $cache */
		$cache = tribe_cache();

		$cache_key = self::get_cache_key( $key );

		if ( wp_using_ext_object_cache() ) {
			$cache->set( $cache_key, $value, WEEK_IN_SECONDS );
		} else {
			$cache->set_transient( $cache_key, $value, WEEK_IN_SECONDS );
		}
	}

	/**
	 * Deletes the cache for the REST API.
	 *
	 * @since TBD
	 *
	 * @param string $key The key to delete the cache for.
	 *
	 * @return void
	 */
	public static function delete_rest_cache( string $key ) {
		/** @var Cache $cache */
		$cache = tribe_cache();

		$cache_key = self::get_cache_key( $key );

		if ( wp_using_ext_object_cache() ) {
			$cache->delete( $cache_key );
		} else {
			$cache->delete_transient( $cache_key );
		}
	}
}
