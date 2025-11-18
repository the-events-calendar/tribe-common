<?php
/**
 * Endpoint class.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Abstracts
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Abstracts;

use TEC\Common\REST\TEC\V1\Contracts\Endpoint_Interface;
use TEC\Common\REST\TEC\V1\Contracts\Readable_Endpoint;
use TEC\Common\REST\TEC\V1\Contracts\Creatable_Endpoint;
use TEC\Common\REST\TEC\V1\Contracts\Updatable_Endpoint;
use TEC\Common\REST\TEC\V1\Contracts\Deletable_Endpoint;
use TEC\Common\REST\TEC\V1\Controller;
use TEC\Common\REST\TEC\V1\Collections\PathArgumentCollection;
use WP_REST_Server;
use WP_REST_Request;
use RuntimeException;
use InvalidArgumentException;
use TEC\Common\REST\TEC\V1\Exceptions\InvalidRestArgumentException;
use TEC\Common\REST\TEC\V1\Exceptions\ExperimentalEndpointException;
use TEC\Common\REST\TEC\V1\Parameter_Types\Integer;
use TEC\Common\REST\TEC\V1\Parameter_Types\Text;

/**
 * Endpoint class.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Abstracts
 */
abstract class Endpoint implements Endpoint_Interface {
	/**
	 * Alias for PUT, PATCH transport methods together.
	 *
	 * @since 6.9.0
	 *
	 * @var string
	 */
	const EDITABLE = 'PUT, PATCH';

	/**
	 * The cached schema.
	 *
	 * @since 6.9.0
	 *
	 * @var array|null
	 */
	protected ?array $cached_schema = null;

	/**
	 * Registers the endpoint.
	 *
	 * @since 6.9.0
	 *
	 * @return void
	 */
	public function register_routes(): void {
		register_rest_route(
			Controller::get_versioned_namespace(),
			$this->get_path(),
			$this->get_methods()
		);
	}

	/**
	 * Returns the methods for the endpoint.
	 *
	 * @since 6.9.0
	 *
	 * @return array
	 *
	 * @throws RuntimeException If the endpoint does not implement at least one of the following interfaces: Readable_Endpoint, Creatable_Endpoint, Updatable_Endpoint, Deletable_Endpoint.
	 */
	protected function get_methods(): array {
		$methods = [];

		if ( $this instanceof Readable_Endpoint ) {
			$methods[] = $this->get_read_attributes();
		}

		if ( $this instanceof Creatable_Endpoint ) {
			$methods[] = $this->get_create_attributes();
		}

		if ( $this instanceof Updatable_Endpoint ) {
			$methods[] = $this->get_update_attributes();
		}

		if ( $this instanceof Deletable_Endpoint ) {
			$methods[] = $this->get_delete_attributes();
		}

		if ( empty( $methods ) ) {
			throw new RuntimeException( 'Each endpoint must implement at least one of the following interfaces: Readable_Endpoint, Creatable_Endpoint, Updatable_Endpoint, Deletable_Endpoint.' );
		}

		$methods['schema'] = fn() => $this->get_cached_schema();

		return $methods;
	}

	/**
	 * @inheritDoc
	 */
	public function get_read_attributes(): array {
		if ( ! $this instanceof Readable_Endpoint ) {
			return [];
		}

		$args = $this->read_params();

		return [
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => $this->respond( [ $this, 'read' ] ),
			'permission_callback' => [ $this, 'can_read' ],
			'args'                => $args->to_array(),
		];
	}

	/**
	 * @inheritDoc
	 */
	public function get_create_attributes(): array {
		if ( ! $this instanceof Creatable_Endpoint ) {
			return [];
		}

		$args = $this->create_params();

		return [
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => $this->respond( [ $this, 'create' ] ),
			'permission_callback' => [ $this, 'can_create' ],
			'args'                => $args->to_query_argument_collection()->to_array(),
		];
	}

	/**
	 * @inheritDoc
	 */
	public function get_update_attributes(): array {
		if ( ! $this instanceof Updatable_Endpoint ) {
			return [];
		}

		$args = $this->update_params();

		return [
			'methods'             => self::EDITABLE,
			'callback'            => $this->respond( [ $this, 'update' ] ),
			'permission_callback' => [ $this, 'can_update' ],
			'args'                => $args->to_query_argument_collection()->to_array(),
		];
	}

	/**
	 * @inheritDoc
	 */
	public function get_delete_attributes(): array {
		if ( ! $this instanceof Deletable_Endpoint ) {
			return [];
		}

		$args = $this->delete_params();

		return [
			'methods'             => WP_REST_Server::DELETABLE,
			'callback'            => $this->respond( [ $this, 'delete' ] ),
			'permission_callback' => [ $this, 'can_delete' ],
			'args'                => $args->to_array(),
		];
	}

	/**
	 * Returns the documentation for the endpoint.
	 *
	 * @since 6.9.0
	 *
	 * @return array
	 */
	public function get_documentation(): array {
		$docs = [];

		if ( $this instanceof Readable_Endpoint ) {
			$docs['get'] = $this->read_schema();
		}

		if ( $this instanceof Creatable_Endpoint ) {
			$docs['post'] = $this->create_schema();
		}

		if ( $this instanceof Updatable_Endpoint ) {
			$docs['put'] = $this->update_schema();
		}

		if ( $this instanceof Deletable_Endpoint ) {
			$docs['delete'] = $this->delete_schema();
		}

		return $docs;
	}

	/**
	 * Gets the current REST URL for the request.
	 *
	 * @since 6.9.0
	 *
	 * @return string The current REST URL.
	 */
	protected function get_current_rest_url(): string {
		$request = $this->get_request();

		$url = rest_url( $request->get_route() );

		$params = $request->get_query_params();
		if ( ! empty( $params ) ) {
			$url = add_query_arg( $params, $url );
		}

		return $url;
	}

	/**
	 * Gets the default posts per page.
	 *
	 * @since 6.9.0
	 *
	 * @return int The default posts per page.
	 */
	protected function get_default_posts_per_page(): int {
		/**
		 * Filters the default number of events per page.
		 *
		 * @since 6.9.0
		 *
		 * @param int $per_page The default number of events per page.
		 */
		return apply_filters( 'tec_rest_events_default_per_page', (int) get_option( 'posts_per_page' ) );
	}

	/**
	 * Gets the maximum posts per page.
	 *
	 * @since 6.9.0
	 *
	 * @return int The maximum posts per page.
	 */
	protected function get_max_posts_per_page(): int {
		/**
		 * Filters the maximum number of events per page.
		 *
		 * @since 6.9.0
		 *
		 * @param int $max_per_page The maximum number of events per page.
		 */
		return apply_filters( 'tec_rest_events_max_per_page', 100 );
	}

	/**
	 * Returns the URL of the endpoint.
	 *
	 * @since 6.9.0
	 *
	 * @param mixed ...$args The arguments to pass to the URL.
	 *
	 * @return string
	 */
	public function get_url( ...$args ): string {
		return rest_url( Controller::get_versioned_namespace() . sprintf( $this->get_base_path(), ...array_map( 'strval', $args ) ) );
	}

	/**
	 * Returns the path parameters of the endpoint.
	 *
	 * @since 6.9.0
	 *
	 * @return PathArgumentCollection
	 */
	public function get_path_parameters(): PathArgumentCollection {
		return new PathArgumentCollection();
	}

	/**
	 * Returns the path for the endpoint.
	 *
	 * @since 6.9.0
	 *
	 * @return string
	 *
	 * @throws RuntimeException If the path parameter is invalid.
	 */
	public function get_path(): string {
		$parameters = $this->get_path_parameters();
		$base       = $this->get_base_path();

		$replacements = [];
		foreach ( $parameters as $parameter ) {
			$regex = false;

			if ( $parameter instanceof Integer ) {
				$regex = '\\d+';
			}

			if ( $parameter instanceof Text ) {
				$regex = '[a-zA-Z0-9_-]+';
			}

			if ( ! $regex ) {
				throw new RuntimeException( 'Invalid path parameter: ' . get_class( $parameter ) );
			}

			$replacements[] = "(?P<{$parameter->get_name()}>{$regex})";
		}

		return sprintf( $base, ...$replacements );
	}

	/**
	 * Returns the OpenAPI path of the endpoint.
	 *
	 * @since 6.9.0
	 *
	 * @return string
	 */
	public function get_open_api_path(): string {
		$parameters = $this->get_path_parameters();
		$base       = $this->get_base_path();

		$replacements = [];
		foreach ( $parameters as $parameter ) {
			$replacements[] = "{{$parameter->get_name()}}";
		}

		return sprintf( $base, ...$replacements );
	}

	/**
	 * Returns the request object for the endpoint.
	 *
	 * @since 6.9.0
	 *
	 * @return WP_REST_Request
	 */
	public function get_request(): WP_REST_Request {
		$container = tribe();

		if ( $container->isBound( WP_REST_Request::class ) ) {
			return $container->get( WP_REST_Request::class );
		}

		return new WP_REST_Request();
	}

	/**
	 * Responds to a request.
	 *
	 * @since 6.9.0
	 *
	 * @param callable $callback The callback to respond to the request.
	 *
	 * @return callable
	 *
	 * @throws RuntimeException If the callback is not callable.
	 */
	private function respond( callable $callback ): callable {
		if ( ! is_callable( $callback ) ) {
			throw new RuntimeException( 'You need to provide a callable to respond to requests!' );
		}

		$operation = is_array( $callback ) ? $callback[1] ?? null : null;

		$experimental_response = fn( WP_REST_Request $request ) => $this->is_experimental() && $this->assure_experimental_acknowledgement( $request );

		$params_sanitizer = fn( WP_REST_Request $request ) => $this->get_schema_defined_params( $operation, $request->get_params() );

		$params_filter = fn( array $params ) => $this->filter_params( $params, $operation );

		return static function ( WP_REST_Request $request ) use ( $callback, $params_sanitizer, $experimental_response, $params_filter ) {
			try {
				$experimental_response( $request );
				$response = $callback( $params_filter( $params_sanitizer( $request ) ) );
			} catch ( InvalidRestArgumentException $e ) {
				return $e->to_wp_error();
			} catch ( ExperimentalEndpointException $e ) {
				return $e->to_wp_error();
			}

			return $response;
		};
	}

	/**
	 * Filters the parameters for the request.
	 *
	 * @since 6.9.0
	 *
	 * @param array  $params     The parameters to filter.
	 * @param string $operation The operation to filter the parameters for.
	 *
	 * @return array The filtered parameters.
	 */
	protected function filter_params( array $params, string $operation ): array {
		$method = "filter_{$operation}_params";

		if ( ! method_exists( $this, $method ) ) {
			return $params;
		}

		return $this->$method( $params );
	}

	/**
	 * Gets the schema defined parameters.
	 *
	 * @since 6.10.0
	 *
	 * @param string $schema_name    The name of the schema. Can be `read`, `create`, `update`, or `delete`.
	 * @param array  $request_params The request parameters.
	 *
	 * @return array The schema defined parameters.
	 *
	 * @throws InvalidArgumentException     If the schema name is invalid.
	 * @throws RuntimeException             If the schema is not found.
	 */
	protected function get_schema_defined_params( string $schema_name, array $request_params = [] ): array {
		if ( ! in_array( $schema_name, [ 'read', 'create', 'update', 'delete' ], true ) ) {
			throw new InvalidArgumentException( 'Invalid schema name: ' . $schema_name );
		}

		switch ( $schema_name ) {
			case 'read':
				if ( ! $this instanceof Readable_Endpoint ) {
					throw new RuntimeException( 'The endpoint does not implement the Readable_Endpoint interface.' );
				}

				$schema = $this->read_schema();
				break;
			case 'create':
				if ( ! $this instanceof Creatable_Endpoint ) {
					throw new RuntimeException( 'The endpoint does not implement the Creatable_Endpoint interface.' );
				}

				$schema = $this->create_schema();
				break;
			case 'update':
				if ( ! $this instanceof Updatable_Endpoint ) {
					throw new RuntimeException( 'The endpoint does not implement the Updatable_Endpoint interface.' );
				}

				$schema = $this->update_schema();
				break;
			case 'delete':
				if ( ! $this instanceof Deletable_Endpoint ) {
					throw new RuntimeException( 'The endpoint does not implement the Deletable_Endpoint interface.' );
				}

				$schema = $this->delete_schema();
				break;
		}

		/** @throws InvalidRestArgumentException If one or more request parameters are invalid. */
		return $schema->filter_before_request( $request_params );
	}

	/**
	 * Assures the experimental acknowledgement.
	 *
	 * @since 6.9.0
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return void
	 *
	 * @throws ExperimentalEndpointException If the experimental acknowledgement is not provided.
	 */
	protected function assure_experimental_acknowledgement( WP_REST_Request $request ): void {
		if ( ! $this->is_experimental() ) {
			return;
		}

		$header = $request->get_header( 'X-TEC-EEA' );

		if ( ! $header ) {
			throw new ExperimentalEndpointException( __( 'Experimental endpoint requires acknowledgement header.', 'tribe-common' ) );
		}

		if ( strtolower( trim( $header ) ) !== $this->get_experimental_acknowledgement() ) {
			throw new ExperimentalEndpointException( __( 'Experimental endpoint requires appropriate acknowledgement header.', 'tribe-common' ) );
		}
	}

	/**
	 * Returns the experimental acknowledgement.
	 *
	 * @since 6.9.0
	 *
	 * @return string
	 */
	private function get_experimental_acknowledgement(): string {
		return strtolower(
			'I understand that this endpoint is experimental and may change in a future release without maintaining backward compatibility. I also understand that I am using this endpoint at my own risk, while support is not provided for it.'
		);
	}

	/**
	 * Returns whether the endpoint is experimental.
	 *
	 * @since 6.9.0
	 *
	 * @return bool
	 */
	public function is_experimental(): bool {
		/**
		 * Filters whether the endpoint is experimental.
		 *
		 * @since 6.9.0
		 *
		 * @param bool   $is_experimental Whether the endpoint is experimental.
		 * @param string $endpoint        The endpoint class name.
		 */
		$is_experimental = apply_filters( 'tec_rest_experimental_' . $this->get_open_api_path() . '_endpoint', true, $this );

		/**
		 * Filters whether the endpoint is experimental.
		 *
		 * @since 6.9.0
		 *
		 * @param bool   $is_experimental Whether the endpoint is experimental.
		 * @param string $endpoint        The endpoint class name.
		 */
		return apply_filters( 'tec_rest_experimental_endpoint', $is_experimental, $this );
	}

	/**
	 * Returns the cached schema.
	 *
	 * @since 6.9.0
	 *
	 * @return array
	 */
	protected function get_cached_schema(): array {
		if ( null !== $this->cached_schema ) {
			return $this->cached_schema;
		}

		$this->cached_schema = $this->get_schema();

		return $this->cached_schema;
	}
}
