<?php
/**
 * The Power Automate New Event Endpoint.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Power_Automate\REST\V1\Endpoints\Queue;
 */

namespace TEC\Event_Automator\Power_Automate\REST\V1\Endpoints\Queue;

use TEC\Event_Automator\Power_Automate\Api;
use TEC\Event_Automator\Power_Automate\REST\V1\Documentation\Swagger_Documentation;
use TEC\Event_Automator\Power_Automate\REST\V1\Endpoints\Abstract_REST_Endpoint;
use TEC\Event_Automator\Power_Automate\Triggers\New_Events as Trigger_New_Events;
use TEC\Event_Automator\Traits\Maps\Event;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

/**
 * Class New_Events
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Power_Automate\REST\V1\Endpoints\Queue
 */
class New_Events extends Abstract_REST_Endpoint {
	use Event;

	/**
	 * @inheritDoc
	 */
	protected $path = '/new-events';

	/**
	 * @inheritdoc
	 */
	protected static $endpoint_id = 'new_events';

	/**
	 * @inheritdoc
	 */
	protected static $type = 'queue';

	/**
	 * The trigger accessed with this endpoint.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var Trigger_New_Events
	 */
	public $trigger;

	/**
	 * Abstract_REST_Endpoint constructor.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param Api                   $api           An instance of the Power Automate API handler.
	 * @param Swagger_Documentation $documentation An instance of the Power Automate Swagger_Documentation handler.
	 * @param Trigger_New_Events    $trigger       The trigger accessed with this endpoint.
	 */
	public function __construct( Api $api, Swagger_Documentation $documentation, Trigger_New_Events $trigger ) {
		parent::__construct( $api, $documentation );
		$this->trigger = $trigger;
	}

	/**
	 * @inheritdoc
	 */
	protected function get_display_name() : string {
		return _x( 'New Events', 'Display name of the Power Automate endpoint for new events.', 'tribe-common' );
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
				'permission_callback' => [ $this, 'can_access' ],
			]
		);

		$this->documentation->register_documentation_provider( $this->get_endpoint_path(), $this );
	}

	/**
	 * Get events from new event queue.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_REST_Response The response from the new event queue.
	 */
	public function get( WP_REST_Request $request ) {
		// No cache headers to prevent hosting from caching the endpoint
		nocache_headers();

		$current_queue = $this->trigger->get_queue();
		if ( empty( $current_queue ) ) {
			$data = [
				'events' => [ 'id' => 'no-new-events' ],
			];

			return new WP_REST_Response( $data );
		}

		$next_event_id = (int) array_shift( $current_queue );
		if ( empty( $next_event_id ) ) {
			$this->trigger->set_queue( $current_queue );
			$data = [
				'events' => [ 'id' => 'no-valid-events' ],
			];

			return new WP_REST_Response( $data );
		}

		$next_event = $this->get_mapped_event( $next_event_id, false, static::$service_id );
		if ( ! $next_event ) {
			$this->trigger->set_queue( $current_queue );
			$data = [
				'events' => [ 'id' => 'not-a-valid-post-type' ],
			];

			return new WP_REST_Response( $data );
		}

		$this->trigger->set_queue( $current_queue );
		$data = [
			'events' => [ $next_event ],
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
						'description' => _x( 'Returns successful checking of the new event queue.', 'Description for the Power Automate New Event REST endpoint on a successful return.', 'tribe-common' ),
						'schema'      => [
							'$ref' => '#/definitions/power-automate',
						],
					],
					'400' => [
						'description' => _x( 'A required parameter is missing or an input parameter is in the wrong format', 'Description for the Power Automate New Event REST endpoint missing a required parameter.', 'tribe-common' ),
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
				'description'       => _x( 'The access token to authorize Power Automate connection.', 'Description for the Power Automate New Event REST endpoint required parameter.', 'tribe-common' ),
			],
		];
	}
}
