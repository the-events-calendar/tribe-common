<?php
/**
 * Controller for the TEC REST API.
 *
 * @since 6.9.0
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
use JsonSerializable;
use DateTimeImmutable;
use DateTimeZone;

/**
 * Controller for the TEC REST API.
 *
 * @since 6.9.0
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
	 * @since 6.9.0
	 *
	 * @var int
	 */
	public const VERSION = 1;

	/**
	 * The whitelisted fields that will not be hidden.
	 *
	 * @since 6.9.0
	 *
	 * @var array
	 */
	protected const WHITELISTED_FIELDS = [
		'id',
		'date',
		'date_gmt',
		'guid',
		'modified',
		'modified_gmt',
		'slug',
		'status',
		'type',
		'link',
		'title',
		'author',
		'class_list',
	];

	/**
	 * Registers the filters and actions hooks added by the controller.
	 *
	 * @since 6.9.0
	 *
	 * @return void
	 */
	protected function do_register(): void {
		$this->container->singleton( Documentation::class );
		$this->container->register( Endpoints::class );
		add_filter( 'rest_pre_dispatch', [ $this, 'bind_request_object' ], 10, 3 );
		add_filter( 'tec_rest_v1_post_entity_transform', [ $this, 'hide_password_protected_data' ], 10000, 2 );
	}

	/**
	 * Unregisters the filters and actions hooks added by the controller.
	 *
	 * @since 6.9.0
	 *
	 * @return void
	 */
	public function unregister(): void {
		$this->container->get( Endpoints::class )->unregister();
		remove_filter( 'rest_pre_dispatch', [ $this, 'bind_request_object' ] );
		remove_filter( 'tec_rest_v1_post_entity_transform', [ $this, 'hide_password_protected_data' ], 10000 );
	}

	/**
	 * Returns the namespace of the REST API.
	 *
	 * @since 6.9.0
	 *
	 * @return string
	 */
	public static function get_versioned_namespace(): string {
		return REST_Controller::NAMESPACE . '/v' . self::VERSION;
	}

	/**
	 * Binds the request object to the singleton.
	 *
	 * @since 6.9.0
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
	 * Hides the password protected data from the entity.
	 *
	 * @since 6.9.0
	 *
	 * @param array  $entity    The entity to hide the password protected data from.
	 * @param string $post_type The post type of the entity.
	 *
	 * @return array
	 */
	public function hide_password_protected_data( array $entity, string $post_type ): array {
		$id = $entity['id'] ?? null;

		if ( ! $id ) {
			return $entity;
		}

		$post = get_post( $id );

		if ( ! $post ) {
			return $entity;
		}

		/**
		 * Filters if the post requires a password to be shown.
		 *
		 * @since 6.9.0
		 *
		 * @param bool   $requires_password Whether the post requires a password to be shown.
		 * @param array  $entity            The entity to check if it requires a password to be shown.
		 * @param string $post_type         The post type of the entity.
		 */
		$requires_password = (bool) apply_filters( 'tec_rest_v1_post_password_required', post_password_required( $post ), $entity, $post_type );

		if ( ! $requires_password ) {
			return $entity;
		}

		return $this->recursive_hide_password_protected_data( $entity, $post_type );
	}

	/**
	 * Returns the cache key for the implementation.
	 *
	 * @since 6.9.0
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
	 * @since 6.9.0
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
	 * @since 6.9.0
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
	 * @since 6.9.0
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
	 * @since 6.9.0
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

	/**
	 * Recursively hides the password protected data from the entity.
	 *
	 * @since 6.9.0
	 *
	 * @param array  $data      The data to hide the password protected data from.
	 * @param string $post_type The post type of the entity.
	 *
	 * @return mixed
	 */
	protected function recursive_hide_password_protected_data( array $data, string $post_type ): array {
		/**
		 * Filters the whitelisted fields that will not be hidden.
		 *
		 * @since 6.9.0
		 *
		 * @param array  $whitelisted_fields The whitelisted fields.
		 * @param string $post_type          The post type of the entity.
		 */
		$whitelisted_fields = (array) apply_filters( 'tec_rest_v1_post_password_whitelisted_fields', self::WHITELISTED_FIELDS, $post_type );

		foreach ( $data as $key => $value ) {
			if ( in_array( $key, $whitelisted_fields, true ) ) {
				continue;
			}

			if ( $value instanceof JsonSerializable ) {
				$value = json_decode( wp_json_encode( $value ), true );
			}

			if ( $value instanceof DateTimeImmutable ) {
				$value = new DateTimeImmutable( '1970-01-01 00:00:00', new DateTimeZone( 'UTC' ) );
			}

			if ( is_callable( $value ) ) {
				$data[ $key ] = null;
				continue;
			}

			if ( is_array( $value ) || is_object( $value ) ) {
				$data[ $key ] = $this->recursive_hide_password_protected_data( (array) $value, $post_type );
				continue;
			}

			if ( ! is_numeric( $value ) && ! is_string( $value ) ) {
				$data[ $key ] = null;
				continue;
			}

			$data[ $key ] = is_string( $value ) ? __( 'Password protected', 'tribe-common' ) : 0;
		}

		return $data;
	}
}
