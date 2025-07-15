<?php
/**
 * OpenAPI docs endpoint.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Endpoints
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Endpoints;

// phpcs:disable StellarWP.Classes.ValidClassName.NotSnakeCase

use TEC\Common\REST\TEC\V1\Abstracts\Endpoint;
use TEC\Common\REST\TEC\V1\Contracts\Readable_Endpoint;
use TEC\Common\REST\TEC\V1\Documentation;
use WP_REST_Request;
use WP_REST_Response;

/**
 * OpenAPI docs endpoint.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Endpoints
 */
class OpenApiDocs extends Endpoint implements Readable_Endpoint {
	/**
	 * Returns the arguments for the read method.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function read_args(): array {
		return [];
	}

	/**
	 * Returns the response for the read method.
	 *
	 * @since TBD
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_REST_Response
	 */
	public function read( WP_REST_Request $request ): WP_REST_Response {
		/** @var Documentation $documentation */
		$documentation = tribe( Documentation::class );
		return new WP_REST_Response( $documentation->get() );
	}

	/**
	 * Returns the schema for the endpoint.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function get_schema(): array {
		return [];
	}

	/**
	 * Returns the documentation for the endpoint.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function get_documentation(): array {
		return [];
	}

	/**
	 * Returns the path of the endpoint.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_path(): string {
		return '/docs';
	}
}
