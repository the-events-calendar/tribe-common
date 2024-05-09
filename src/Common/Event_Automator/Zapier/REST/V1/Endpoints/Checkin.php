<?php
/**
 * The Zapier Ticket Sales Endpoint.
 *
 * @since 1.0.0
 *
 * @package TEC\Common\Event_Automator\Zapier\REST\V1\Endpoints;
 */

namespace TEC\Common\Event_Automator\Zapier\REST\V1\Endpoints;

use TEC\Common\Event_Automator\Zapier\Api;
use TEC\Common\Event_Automator\Zapier\REST\V1\Documentation\Swagger_Documentation;
use TEC\Common\Event_Automator\Zapier\Triggers\Checkin as Trigger_Checkin;
use WP_REST_Server;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Class Checkin
 *
 * @since 1.0.0
 *
 * @package TEC\Common\Event_Automator\Zapier\REST\V1\Endpoints
 */
class Checkin extends Abstract_REST_Endpoint {

	/**
	 * @inheritDoc
	 */
	protected $path = '/checkin';

	/**
	 * @inheritdoc
	 */
	protected static $endpoint_id = 'checkin';

	/**
	 * @inheritdoc
	 */
	protected static $type = 'queue';

	/**
	 * The trigger accessed with this endpoint.
	 *
	 * @since 1.0.0
	 *
	 * @var Trigger_Checkin
	 */
	public $trigger;

	/**
	 * Abstract_REST_Endpoint constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param Api                   $api           An instance of the Zapier API handler.
	 * @param Swagger_Documentation $documentation An instance of the Zapier Swagger_Documentation handler.
	 * @param Trigger_Checkin     $trigger       The trigger accessed with this endpoint.
	 */
	public function __construct( Api $api, Swagger_Documentation $documentation, Trigger_Checkin $trigger ) {
		parent::__construct( $api, $documentation );
		$this->trigger = $trigger;
	}

	/**
	 * @inheritdoc
	 */
	protected function get_display_name() : string {
		return _x( 'Checkins', 'Display name of the Zapier endpoint for checkins.', 'tribe-common' );
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
			] );

		$this->documentation->register_documentation_provider( $this->get_endpoint_path(), $this );
	}

	/**
	 * Get attendees from new attendee queue.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_REST_Response The response from the new attendee queue.
	 */
	public function get( WP_REST_Request $request ) {
		// No cache headers to prevent hosting from caching the endpoint
		nocache_headers();

		$loaded = $this->verify_and_load_key( $request );
		if ( is_wp_error( $loaded ) ) {
			return new WP_REST_Response( $loaded, 400 );
		}

		$attendees = $this->get_mapped_attendees_from_queue( $this->trigger->get_queue(), true, 'no-new-checkins', 'no-valid-checkins' );

		return new WP_REST_Response( $attendees );
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
						'description' => _x( 'Returns successful checking of the new checkin queue.', 'Description for the Zapier Checkin REST endpoint on a successful return.', 'tribe-common' ),
						'schema'      => [
							'$ref' => '#/definitions/Zapier',
						],
					],
					'400' => [
						'description' => _x( 'A required parameter is missing or an input parameter is in the wrong format', 'Description for the Zapier Checkin REST endpoint missing a required parameter.', 'tribe-common' ),
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
				'required'          => true,
				'validate_callback' => [ $this, 'sanitize_callback' ],
				'type'              => 'string',
				'description'       => _x( 'The access token to authorize Zapier connection.', 'Description for the Zapier Checkin REST endpoint required parameter.', 'tribe-common' ),
			],
		];
	}
}
