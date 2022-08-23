<?php
/**
 * The Zapier API Key Endpoint.
 *
 * @package TEC\Common\ZapierREST\V1\Endpoints;
 * @since   TBD
 */

namespace TEC\Common\Zapier\REST\V1\Endpoints;

use TEC\Common\Zapier\Api;
use Tribe__Events__REST__V1__Endpoints__Base as REST_V1_Endpoints_Base;
use Tribe__REST__Endpoints__READ_Endpoint_Interface as READ_Endpoint_Interface;
use Tribe__Documentation__Swagger__Provider_Interface as Swagger_Provider_Interface;
use Tribe__REST__Messages_Interface;
use Tribe__Events__REST__Interfaces__Post_Repository;
use Tribe__Events__Validator__Interface;
use Tribe__Events__Main;
use WP_REST_Request;
use WP_REST_Response;
use WP_Error;

class Api_Key
	extends REST_V1_Endpoints_Base
	implements READ_Endpoint_Interface, Swagger_Provider_Interface {

	/**
	 * @var Tribe__REST__Main
	 */
	protected $main;

	/**
	 * @var WP_REST_Request
	 */
	protected $serving;

	/**
	 * @var Tribe__Events__REST__Interfaces__Post_Repository
	 */
	protected $post_repository;

	/**
	 * @var Tribe__Events__Validator__Interface
	 */
	protected $validator;

	/**
	 * An instance of the Zapier API handler.
	 *
	 * @since TBD
	 *
	 * @var Api
	 */
	protected $api;

	/**
	 * Tribe__Events__REST__V1__Endpoints__Archive_Event constructor.
	 *
	 * @since TBD
	 *
	 * @param Tribe__REST__Messages_Interface                  $messages
	 * @param Tribe__Events__REST__Interfaces__Post_Repository $repository
	 * @param Tribe__Events__Validator__Interface              $validator
	 */
	public function __construct(
		Tribe__REST__Messages_Interface $messages,
		Tribe__Events__REST__Interfaces__Post_Repository $repository,
		Tribe__Events__Validator__Interface $validator,
		Api $api
	) {
		parent::__construct( $messages );
		$this->post_type = Tribe__Events__Main::POSTTYPE;
		$this->api = $api;
	}

	/**
	 * Get new events from queue.
	 *
	 * @since TBD
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return mixed|void|WP_Error|WP_REST_Response
	 */
	public function get( WP_REST_Request $request ) {
		$consumer_id     = $request->get_param( 'consumer_id' );
		$consumer_secret = $request->get_param( 'consumer_secret' );

		$loaded = $this->api->load_account_by_id( $consumer_id, $consumer_secret );
		if ( ! $loaded ) {
			return [];
		}

		//@todo, return jwt token with the below information
		return [
			'consumer_id' => $consumer_id,
		];

		$issuedAt = time();
		$token    = [
			'iss'  => get_bloginfo( 'url' ),
			'iat'  => $issuedAt,
			'nbf'  => $issuedAt,
			'exp'  => $issuedAt + 300,
			'data' => [
				'user_id' => $user->data->ID,
			],
		];

		return [
			'token' => JWT::encode( $token, $secret_key ),
		];
	}

	/**
	 * Returns an array in the format used by Swagger 2.0.
	 *
	 * While the structure must conform to that used by v2.0 of Swagger the structure can be that of a full document
	 * or that of a document part.
	 * The intelligence lies in the "gatherer" of informations rather than in the single "providers" implementing this
	 * interface.
	 *
	 * @since TBD
	 *
	 * @link http://swagger.io/
	 *
	 * @return array<string|mixed> An array description of a Swagger supported component.
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
	 * Provides the content of the `args` array to register the endpoint support for GET requests.
	 *
	 * @since TBD
	 *
	 * @return array<string|mixed> An array of read 'args'.
	 */
	public function READ_args() {
		return [
			'consumer_id'       => [
				'required'          => true,
				'validate_callback' => [ $this->validator, 'is_string' ],
				'type'              => 'string',
				'description'       => __( 'The consumer id to authorize Zapier connection.', 'tribe-common' ),
			],
			'consumer_secret'       => [
				'required'          => true,
				'validate_callback' => [ $this->validator, 'is_string' ],
				'type'              => 'string',
				'description'       => __( 'The consumer secret to authorize Zapier connection.', 'tribe-common' ),
			],
		];
	}
}
