<?php
/**
 * The Zapier Find Event Endpoint.
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
use Tribe__Events__REST__V1__Endpoints__Archive_Event;
use Tribe__Events__Validator__Base;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

/**
 * Class Find_Events
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Zapier\REST\V1\Endpoints
 */
class Find_Events extends Abstract_REST_Endpoint {
	use Event;

	/**
	 * @inheritDoc
	 *
	 * @var string
	 */
	protected $path = '/find-events';

	/**
	 * @inheritDoc
	 *
	 * @var string
	 */
	protected static $endpoint_id = 'find_events';

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
	 * @var Tribe__Events__REST__V1__Endpoints__Archive_Event
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
			$this->rest_endpoint = tribe( 'tec.rest-v1.endpoints.archive-event' );
			$this->validator     = tribe( 'tec.rest-v1.validator' );
		}
	}

	/**
	 * @inheritdoc
	 */
	protected function get_display_name(): string {
		return _x( 'Find Events', 'Display name of the Zapier endpoint.', 'tribe-common' );
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
	 * Get events from event archive.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return array[<string|mixed>]|WP_REST_Response The response for find events request.
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

		$response     = $this->rest_endpoint->get( $request );
		$found_events = $response->data['events'];
		$events       = [];
		foreach ( $found_events as $event ) {
			// Ensure that $next_event_id is numeric before typecasting to integer.
			if ( ! is_numeric( $event['id'] ) ) {
				continue;
			}

			$next_event_id = (int) $event['id'];
			$next_event    = $this->get_mapped_event( $next_event_id );
			if ( empty( $next_event ) ) {
				continue;
			}

			$events[] = $next_event;
		}

		return empty( $events ) ? [] : [ [ 'events' => $events ] ];
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
						'description' => _x( 'Returns successful checking of the find event archive.', 'Description for the Zapier Find Event REST endpoint on a successful return.', 'tribe-common' ),
						'schema'      => [
							'$ref' => '#/definitions/Zapier',
						],
					],
					'400' => [
						'description' => _x( 'A required parameter is missing or an input parameter is in the wrong format.', 'Description for the Zapier Find Event REST endpoint missing a required parameter.', 'tribe-common' ),
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
				'description'       => _x( 'The access token to authorize Zapier connection.', 'Description for the Zapier Find Event REST endpoint required parameter.', 'tribe-common' ),
			],
		];

		$read_event_args = array_merge( $read_event_args, $read_args );

		return $read_event_args;
	}
}
