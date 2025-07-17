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
use WP_REST_Server;
use WP_REST_Request;

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
	 */
	protected function get_methods(): array {
		$methods = [];

		if ( $this instanceof Readable_Endpoint ) {
			$methods[] = [
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ $this, 'read' ],
				'permission_callback' => [ $this, 'can_read' ],
				'args'                => $this->read_args(),
			];
		}

		if ( $this instanceof Creatable_Endpoint ) {
			$methods[] = [
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => [ $this, 'create' ],
				'permission_callback' => [ $this, 'can_create' ],
				'args'                => $this->create_args(),
			];
		}

		if ( $this instanceof Updatable_Endpoint ) {
			$methods[] = [
				'methods'             => self::EDITABLE,
				'callback'            => [ $this, 'update' ],
				'permission_callback' => [ $this, 'can_update' ],
				'args'                => $this->update_args(),
			];
		}

		if ( $this instanceof Deletable_Endpoint ) {
			$methods[] = [
				'methods'             => WP_REST_Server::DELETABLE,
				'callback'            => [ $this, 'delete' ],
				'permission_callback' => [ $this, 'can_delete' ],
				'args'                => $this->delete_args(),
			];
		}

		$methods['schema'] = fn() => $this->get_schema();

		return $methods;
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
}
