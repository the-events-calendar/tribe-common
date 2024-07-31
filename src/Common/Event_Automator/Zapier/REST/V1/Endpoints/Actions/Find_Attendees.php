<?php
/**
 * The Zapier Find Attendees Endpoint.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\ZapierREST\V1\Endpoints;
 */

namespace TEC\Event_Automator\Zapier\REST\V1\Endpoints\Actions;

use TEC\Event_Automator\Traits\Maps\Event;
use TEC\Event_Automator\Zapier\Api;
use TEC\Event_Automator\Zapier\REST\V1\Documentation\Swagger_Documentation;
use TEC\Event_Automator\Zapier\REST\V1\Endpoints\Abstract_REST_Endpoint;
use Tribe__Tickets__REST__V1__Endpoints__Attendee_Archive;
use Tribe__Tickets__Validator__Base;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

/**
 * Class Find_Attendees
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Zapier\REST\V1\Endpoints
 */
class Find_Attendees extends Abstract_REST_Endpoint {
	use Event;

	/**
	 * @inheritDoc
	 *
	 * @var string
	 */
	protected $path = '/find-attendees';

	/**
	 * @inheritDoc
	 *
	 * @var string
	 */
	protected static $endpoint_id = 'find_attendees';

	/**
	 * @inheritDoc
	 *
	 * @var string
	 */
	protected static $type = 'search';

	/**
	 * The REST instance endpoint to use.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var Tribe__Tickets__REST__V1__Endpoints__Attendee_Archive
	 */
	protected $rest_endpoint = null;

	/**
	 * @inheritDoc
	 *
	 * @var array<string>
	 */
	protected array $dependents = [ 'tec' ];

	/**
	 * The REST validator to use.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var Tribe__Tickets__Validator__Base
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
		if ( $this->is_rest_request() && class_exists( 'Tribe__Tickets__Validator__Base', false ) ) {
			$this->rest_endpoint = tribe( 'tickets.rest-v1.endpoints.attendees-archive' );
			$this->validator     = tribe( 'tickets.rest-v1.validator' );
		}
	}

	/**
	 * @inheritdoc
	 */
	protected function get_display_name(): string {
		return _x( 'Find Attendees', 'Display name of the Zapier endpoint.', 'tribe-common' );
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
				'permission_callback' => [ $this, 'can_view' ],
			]
		);

		$this->documentation->register_documentation_provider( $this->get_endpoint_path(), $this );
	}

	/**
	 * Whether the current user is set and the api is loaded.
	 * The test for creating is done one the rest_pre_dispatch hook, if the api is not ready or no user loaded then it failed.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return bool Whether the current user and api are loaded.
	 */
	public function can_view( $request ) {
		$verified_token = $this->verify_token( $request );
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
	 * Get attendees from archive endpoint.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return array[<string|mixed>]|\WP_REST_Response The response for find attendees request.
	 */
	public function get( WP_REST_Request $request ) {
		// No cache headers to prevent hosting from caching the endpoint.
		nocache_headers();

		$verified_token = $this->verify_token( $request );
		if ( is_wp_error( $verified_token ) ) {
			return new WP_REST_Response( $verified_token, 400 );
		}

		$loaded = $this->load_api_key_pair( $verified_token['consumer_id'], $verified_token['consumer_secret'], $verified_token );
		if ( is_wp_error( $loaded ) ) {
			return new WP_REST_Response( $loaded, 400 );
		}

		$response        = $this->rest_endpoint->get( $request );
		$found_attendees = $response->data['attendees'];
		$attendees       = [];
		foreach ( $found_attendees as $attendee ) {
			// Ensure that $next_attendee_id is numeric before typecasting to integer.
			if ( ! is_numeric( $attendee['id'] ) ) {
				continue;
			}

			$next_attendee_id = (int) $attendee['id'];
			$next_attendee    = $this->get_mapped_attendee( $next_attendee_id );
			if ( empty( $next_attendee ) ) {
				continue;
			}

			$attendees[] = $next_attendee;
		}

		return empty( $attendees ) ? [] : [ [ 'attendees' => $attendees ] ];
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
		$post_args     = array_merge( $this->READ_args() );

		return [
			'post' => [
				'consumes'   => [ 'application/x-www-form-urlencoded' ],
				'parameters' => $this->swaggerize_args( $post_args, $post_defaults ),
				'responses'  => [
					'201' => [
						'description' => _x( 'Returns successful checking of the find attendee archive.', 'Description for the Zapier Find Attendees REST endpoint on a successful return.', 'tribe-common' ),
						'schema'      => [
							'$ref' => '#/definitions/Zapier',
						],
					],
					'400' => [
						'description' => _x( 'A required parameter is missing or an input parameter is in the wrong format.', 'Description for the Zapier Find Attendees REST endpoint missing a required parameter.', 'tribe-common' ),
					],
				],
			],
		];
	}

	/**
	 * @inheritDoc
	 */
	public function READ_args() {
		$read_args = [];
		if ( $this->rest_endpoint ) {
			$read_args = $this->rest_endpoint->READ_args();
		}

		$read_event_args = [
			'access_token' => [
				'required'          => false,
				'validate_callback' => [ $this, 'sanitize_callback' ],
				'type'              => 'string',
				'description'       => _x( 'The access token to authorize Zapier connection.', 'Description for the Zapier Find Attendees REST endpoint required parameter.', 'tribe-common' ),
			],
		];

		$read_event_args = array_merge( $read_event_args, $read_args );

		return $read_event_args;
	}
}
