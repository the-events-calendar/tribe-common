<?php
/**
 * Endpoint class.
 *
 * @since TBD
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
use TEC\Common\REST\TEC\V1\Collections\QueryArgumentCollection;
use WP_REST_Server;
use WP_REST_Request;
use RuntimeException;

/**
 * Endpoint class.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Abstracts
 */
abstract class Endpoint implements Endpoint_Interface {
	/**
	 * Alias for PUT, PATCH transport methods together.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	const EDITABLE = 'PUT, PATCH';

	/**
	 * Registers the endpoint.
	 *
	 * @since TBD
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
	 * @since TBD
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

		$methods['schema'] = fn() => $this->get_schema();

		return $methods;
	}

	/**
	 * @inheritDoc
	 */
	public function get_read_attributes(): array {
		if ( ! $this instanceof Readable_Endpoint ) {
			return [];
		}

		$args = $this->read_args();

		return [
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => [ $this, 'read' ],
			'permission_callback' => [ $this, 'can_read' ],
			'args'                => $args instanceof QueryArgumentCollection ? $args->to_array() : [],
		];
	}

	/**
	 * @inheritDoc
	 */
	public function get_create_attributes(): array {
		if ( ! $this instanceof Creatable_Endpoint ) {
			return [];
		}

		$args = $this->create_args();

		return [
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => [ $this, 'create' ],
			'permission_callback' => [ $this, 'can_create' ],
			'args'                => $args instanceof QueryArgumentCollection ? $args->to_array() : [],
		];
	}

	/**
	 * @inheritDoc
	 */
	public function get_update_attributes(): array {
		if ( ! $this instanceof Updatable_Endpoint ) {
			return [];
		}

		$args = $this->update_args();

		return [
			'methods'             => self::EDITABLE,
			'callback'            => [ $this, 'update' ],
			'permission_callback' => [ $this, 'can_update' ],
			'args'                => $args instanceof QueryArgumentCollection ? $args->to_array() : [],
		];
	}

	/**
	 * @inheritDoc
	 */
	public function get_delete_attributes(): array {
		if ( ! $this instanceof Deletable_Endpoint ) {
			return [];
		}

		$args = $this->delete_args();

		return [
			'methods'             => WP_REST_Server::DELETABLE,
			'callback'            => [ $this, 'delete' ],
			'permission_callback' => [ $this, 'can_delete' ],
			'args'                => $args instanceof QueryArgumentCollection ? $args->to_array() : [],
		];
	}

	/**
	 * Returns the documentation for the endpoint.
	 *
	 * @since TBD
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
	 * @since TBD
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return string The current REST URL.
	 */
	protected function get_current_rest_url( WP_REST_Request $request ): string {
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
	 * @since TBD
	 *
	 * @return int The default posts per page.
	 */
	protected function get_default_posts_per_page(): int {
		/**
		 * Filters the default number of events per page.
		 *
		 * @since TBD
		 *
		 * @param int $per_page The default number of events per page.
		 */
		return apply_filters( 'tec_rest_events_default_per_page', (int) get_option( 'posts_per_page' ) );
	}

	/**
	 * Gets the maximum posts per page.
	 *
	 * @since TBD
	 *
	 * @return int The maximum posts per page.
	 */
	protected function get_max_posts_per_page(): int {
		/**
		 * Filters the maximum number of events per page.
		 *
		 * @since TBD
		 *
		 * @param int $max_per_page The maximum number of events per page.
		 */
		return apply_filters( 'tec_rest_events_max_per_page', 100 );
	}

	/**
	 * Returns the URL of the endpoint.
	 *
	 * @since TBD
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
	 * @since TBD
	 *
	 * @return array
	 */
	public function get_path_parameters(): array {
		return [];
	}

	/**
	 * Returns the path for the endpoint.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_path(): string {
		$parameters = $this->get_path_parameters();
		$base       = $this->get_base_path();

		$replacements = [];
		foreach ( $parameters as $parameter => $data ) {
			switch ( $data['type'] ) {
				case 'integer':
					$regex = '\\d+';
					break;
				case 'string':
					$regex = '[a-zA-Z0-9_-]+';
					break;
				default:
					$regex = $data['type'];
					break;
			}
			$replacements[] = "(?P<{$parameter}>{$regex})";
		}

		return sprintf( $base, ...$replacements );
	}

	/**
	 * Returns the OpenAPI path of the endpoint.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_open_api_path(): string {
		$parameters = $this->get_path_parameters();
		$base       = $this->get_base_path();

		$replacements = [];
		foreach ( $parameters as $parameter => $data ) {
			$replacements[] = "{{$parameter}}";
		}

		return sprintf( $base, ...$replacements );
	}
}
