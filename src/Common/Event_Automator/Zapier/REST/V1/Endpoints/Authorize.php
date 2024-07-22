<?php
/**
 * The Zapier API Key Endpoint.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Zapier\REST\V1\Endpoints
 */

namespace TEC\Event_Automator\Zapier\REST\V1\Endpoints;

use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use TEC\Common\Firebase\JWT\JWT;

/**
 * Class Authorize
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Zapier\REST\V1\Endpoints
 */
class Authorize extends Abstract_REST_Endpoint {

	/**
	 * @inheritDoc
	 */
	protected $path = '/authorize';

	/**
	 * @inheritdoc
	 */
	protected static $endpoint_id = 'authorize';

	/**
	 * @inheritdoc
	 */
	protected static $type = 'authorize';

	/**
	 * @inheritdoc
	 */
	protected function get_display_name() : string {
		return _x( 'Authorize', 'Display name of the Zapier endpoint for authorization.', 'tribe-common' );
	}

	/**
	 * @inheritDoc
	 */
	public function register() {
		// If disabled, then do not register the route.
		if ( ! $this->enabled ) {
			return;
		}

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
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_REST_Response The response to authorizing Zapier access for an API Key pair.
	 */
	public function get( WP_REST_Request $request ) {
		// No cache headers to prevent hosting from caching the endpoint
		nocache_headers();

		$consumer_id       = $request->get_param( 'consumer_id' );
		$consumer_secret   = $request->get_param( 'consumer_secret' );
		$token['app_name'] = $request->get_param( 'app_name' );
		$loaded            = $this->load_api_key_pair( $consumer_id, $consumer_secret, $token );

		if ( is_wp_error( $loaded ) ) {
			return new WP_REST_Response( $loaded, 400 );
		}

		$issuedAt = time();
		$access_token    = [
			'iss'  => get_bloginfo( 'url' ),
			'iat'  => $issuedAt,
			'nbf'  => $issuedAt,
			'data' => [
				'consumer_id'     => $consumer_id,
				'consumer_secret' => $consumer_secret,
				'app_name'        => esc_html( $token['app_name'] ),
			],
		];

		$data = [
			'access_token' => JWT::encode( $access_token, $this->api->get_api_secret(), 'HS256' ),
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
					'200' => [
						'description' => _x( 'Returns successful authentication', 'Zapier REST API authorize success message.', 'tribe-common' ),
						'schema'      => [
							'$ref' => '#/definitions/Zapier',
						],
					],
					'400' => [
						'description' => _x( 'A required authentication parameter is missing or an input parameter is in the wrong format', 'Zapier REST API authorize failure message.', 'tribe-common' ),
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
				'description'       => _x( 'The consumer id to authorize Zapier connection.', 'Zapier REST API description for consumer id parameter.', 'tribe-common' ),
			],
			'consumer_secret'   => [
				'required'          => true,
				'validate_callback' => [ $this, 'sanitize_callback' ],
				'type'              => 'string',
				'description'       => _x( 'The consumer secret to authorize Zapier connection.', 'Zapier REST API description for consumer secret parameter.', 'tribe-common' ),
			],
			'app_name'          => [
				'required'          => false,
				'validate_callback' => [ $this, 'sanitize_callback' ],
				'type'              => 'string',
				'description'       => _x( 'The app name of the Zapier connection.', 'Zapier app name parameter.', 'tribe-common' ),
			],
		];
	}
}
