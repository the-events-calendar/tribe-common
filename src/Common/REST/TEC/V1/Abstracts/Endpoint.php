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
}
