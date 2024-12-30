<?php
/**
 * The Zapier Update Event Endpoint.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Zapier\REST\V1\Endpoints;
 */

namespace TEC\Event_Automator\Zapier\REST\V1\Endpoints\Actions;

use TEC\Event_Automator\Zapier\Api;
use TEC\Event_Automator\Zapier\REST\V1\Documentation\Swagger_Documentation;
use TEC\Event_Automator\Zapier\REST\V1\Endpoints\Abstract_REST_Endpoint;
use Tribe__Events__REST__V1__Endpoints__Single_Event as Single_Event_Endpoints;
use Tribe__Events__REST__V1__Validator__Base;
use Tribe__Events__Validator__Base;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use WP_Error;

/**
 * Class Find_Events
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Zapier\REST\V1\Endpoints
 */
class Update_Events extends Abstract_REST_Endpoint {

	/**
	 * @inheritDoc
	 *
	 * @var string
	 */
	protected $path = '/update-events';

	/**
	 * @inheritDoc
	 *
	 * @var string
	 */
	protected static $endpoint_id = 'update_events';

	/**
	 * @inheritDoc
	 *
	 * @var string
	 */
	protected static $type = 'update';

	/**
	 * @inheritDoc
	 *
	 * @var array<string>
	 */
	protected array $dependents = [ 'tec' ];

	/**
	 * The REST instance endpoint to use.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var Single_Event_Endpoints
	 */
	protected $rest_endpoint = null;

	/**
	 * The REST validator to use.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var Tribe__Events__Validator__Base
	 */
	protected $validator;

	/**
	 * Abstract_REST_Endpoint constructor.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param Api                   $api           An instance of the Zapier API handler.
	 * @param Swagger_Documentation $documentation An instance of the Zapier Swagger_Documentation handler.
	 */
	public function __construct( Api $api, Swagger_Documentation $documentation ) {
		parent::__construct( $api, $documentation );
		if ( $this->is_rest_request() && class_exists( 'Tribe__Events__REST__V1__Validator__Base', false ) ) {
			$this->rest_endpoint = tribe( 'tec.rest-v1.endpoints.single-event' );
			$this->validator     = tribe( 'tec.rest-v1.validator' );
		}
	}

	/**
	 * @inheritdoc
	 */
	protected function get_display_name() : string {
		return _x( 'Update Events', 'Display name of the Zapier endpoint.', 'tribe-common' );
	}

	/**
	 * @inheritDoc
	 */
	public function register() {
		// If disabled or missing dependency, then do not register the route.
		if (
			! $this->enabled
			|| $this->missing_dependency
		) {
			return;
		}

		// Safety Check for the classes.
		if (
			! $this->rest_endpoint instanceof Single_Event_Endpoints
			|| ! $this->validator instanceof Tribe__Events__REST__V1__Validator__Base
		) {
			return;
		}

		register_rest_route(
			$this->get_events_route_namespace(),
			$this->get_endpoint_path(),
			[
				'methods'             => WP_REST_Server::EDITABLE,
				'args'                => $this->EDIT_args(),
				'callback'            => [ $this, 'post' ],
				'permission_callback' => [ $this, 'can_edit' ],
			]
		);

		$this->documentation->register_documentation_provider( $this->get_endpoint_path(), $this );
	}

	/**
	 * Whether the current user is set and the api is loaded.
	 * The test for creating is done on the rest_pre_dispatch hook, if the api is not ready or no user was loaded then it failed.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return bool Whether the current user and api are loaded.
	 */
	public function can_edit( $request ) {
		$verified_token = $this->verify_and_load_key( $request );
		if ( is_wp_error( $verified_token ) ) {
			return false;
		}

		if ( ! $this->api->is_ready() ) {
			return false;
		}

		$user = $this->api->get_user();
		if ( empty( $user->ID ) ) {
			return false;
		}

		$current_user_id = get_current_user_id();
		if ( $user->ID !== $current_user_id ) {
			return false;
		}

		return true;
	}

	/**
	 * Get Events for Creation
	 *
	 * Required method from abstract that only returns an error.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_REST_Response The REST response.
	 */
	public function get( WP_REST_Request $request ) {
		$error_msg = _x( 'GET responses not accepted on the update events endpoint, please us a PATCH request.', 'Zapier API error for using GET request.', 'tribe-common' );

		$user_error = new WP_Error( 'zapier_incorrect_get_request', $error_msg, [ 'status' => 400 ] );

		return new WP_REST_Response( $user_error, 400 );
	}

	/**
	 * Update an event.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_REST_Response The response from creating an event.
	 */
	public function post( WP_REST_Request $request ) {
		// No cache headers to prevent hosting from caching the endpoint.
		nocache_headers();

		$response = $this->rest_endpoint->update( $request );

		// Cast as string to prevent validation error in Zapier.
		if ( ! $response->data instanceof WP_Error && empty( $response->data['image'] ) ) {
			$response->data['image'] = (string) $response->data['image'];
		}

		return $response;
	}

	/**
	 * @inheritDoc
	 */
	public function get_documentation() {
		$post_defaults = [
			'in'      => 'formData',
			'default' => '',
			'type'    => 'string',
		];
		$post_args     = array_merge( $this->EDIT_args() );

		return [
			'post' => [
				'consumes'   => [ 'application/x-www-form-urlencoded' ],
				'parameters' => $this->swaggerize_args( $post_args, $post_defaults ),
				'responses'  => [
					'201' => [
						'description' => _x( 'Returns creation of a new event.', 'Description for the Zapier Update Event REST endpoint on a successful return.', 'tribe-common' ),
						'schema'      => [
							'$ref' => '#/definitions/Zapier',
						],
					],
					'400' => [
						'description' => _x( 'A required parameter is missing or an input parameter is in the wrong format', 'Description for the Zapier Update Event REST endpoint missing a required parameter.', 'tribe-common' ),
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
			'access_token' => [
				'required'          => false,
				'validate_callback' => [ $this, 'sanitize_callback' ],
				'type'              => 'string',
				'description'       => _x( 'The access token to authorize Zapier connection.', 'Description for the Zapier Update Event REST endpoint required parameter.', 'tribe-common' ),
			],
		];
	}

	/**
	 * @inheritDoc
	 */
	public function EDIT_args() {
		$update_args = $this->rest_endpoint->EDIT_args();

		array_walk( $update_args, [ $this, 'unrequire_arg' ] );

		$update_event_args = [
			'access_token' => [
				'required'          => false,
				'validate_callback' => [ $this, 'sanitize_callback' ],
				'type'              => 'string',
				'description'       => _x( 'The access token to authorize Zapier connection.', 'Description for the Zapier Update Event REST endpoint required parameter.', 'tribe-common' ),
			],
			'id'           => [
				'required'          => true,
				'in'                => 'path',
				'type'              => 'integer',
				'description'       => __( 'the event post ID', 'avent-automator' ),
				'validate_callback' => [ $this->validator, 'is_event_id' ],
			],
		];

		$update_event_args = array_merge( $update_event_args, $update_args );

		return $update_event_args;
	}
}
