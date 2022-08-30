<?php
/**
 * The Zapier API Key Endpoint.
 *
 * @since   TBD
 *
 * @package TEC\Common\Zapier\REST\V1\Endpoints
 */

namespace TEC\Common\Zapier\REST\V1\Endpoints;

use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use Firebase\JWT\JWT;

/**
 * Class Authorize
 *
 * @since   TBD
 *
 * @package TEC\Common\Zapier\REST\V1\Endpoints
 */
class Authorize extends Abstract_REST_Endpoint {

	/**
	 * @inheritDoc
	 */
	protected $path = '/authorize';

	/**
	 * @inheritDoc
	 */
	public function register() {
		register_rest_route(
			$this->get_events_route_namespace(),
			$this->get_endpoint_path(),
			[
				'methods'             => WP_REST_Server::READABLE,
				'args'                => $this->READ_args(),
				'callback'            => [ $this, 'get' ],
				'permission_callback' => '__return_true',
			]
		);

		$this->documentation->register_documentation_provider( $this->get_endpoint_path(), $this );
	}

	/**
	 * Authorize Access to the Zapier EndPoints.
	 *
	 * @since TBD
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_REST_Response The response to authorizing Zapier access for an API Key pair.
	 */
	public function get( WP_REST_Request $request ) {
		$consumer_id     = $request->get_param( 'consumer_id' );
		$consumer_secret = $request->get_param( 'consumer_secret' );
		$loaded          = $this->load_api_key_pair( $consumer_id, $consumer_secret );

		if ( is_wp_error( $loaded ) ) {
			return new WP_REST_Response( $loaded, 400 );
		}

		$issuedAt = time();
		$token    = [
			'iss'  => get_bloginfo( 'url' ),
			'iat'  => $issuedAt,
			'nbf'  => $issuedAt,
			'data' => [
				'consumer_id'     => $consumer_id,
				'consumer_secret' => $consumer_secret,
			],
		];

		$data = [
			'token' => JWT::encode( $token, $this->api->get_api_secret() ),
		];

		return new WP_REST_Response( $data );
	}

	/**
	 * @inheritDoc
	 */
	public function get_documentation() {
		$POST_defaults = [
			'in'      => 'formData',
			'default' => '',
			'type'    => 'string',
		];
		$post_args     = array_merge( $this->READ_args() );

		return [
			'post' => [
				'consumes'   => [ 'application/x-www-form-urlencoded' ],
				'parameters' => $this->swaggerize_args( $post_args, $POST_defaults ),
				'responses'  => [
					'201' => [
						'description' => __( 'Returns successful authentication', 'tribe-common' ),
						'schema'      => [
							'$ref' => '#/definitions/Zapier',
						],
					],
					'400' => [
						'description' => __( 'A required authentication parameter is missing or an input parameter is in the wrong format', 'tribe-common' ),
					],
				],
			],
		];
	}

	/**
	 * @inheritDoc
	 */
	public function READ_args() {
		return [
			'consumer_id'       => [
				'required'          => true,
				'validate_callback' => [ $this, 'sanitize_callback' ],
				'type'              => 'string',
				'description'       => __( 'The consumer id to authorize Zapier connection.', 'tribe-common' ),
			],
			'consumer_secret'       => [
				'required'          => true,
				'validate_callback' => [ $this, 'sanitize_callback' ],
				'type'              => 'string',
				'description'       => __( 'The consumer secret to authorize Zapier connection.', 'tribe-common' ),
			],
		];
	}
}
