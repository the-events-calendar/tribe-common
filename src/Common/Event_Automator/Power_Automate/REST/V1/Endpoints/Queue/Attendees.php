<?php
/**
 * The Power Automate Attendees Endpoint.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Power_Automate\REST\V1\Endpoints\Queue;
 */

namespace TEC\Event_Automator\Power_Automate\REST\V1\Endpoints\Queue;

use TEC\Event_Automator\Power_Automate\Api;
use TEC\Event_Automator\Power_Automate\REST\V1\Documentation\Swagger_Documentation;
use TEC\Event_Automator\Power_Automate\REST\V1\Endpoints\Abstract_REST_Endpoint;
use TEC\Event_Automator\Power_Automate\Triggers\Attendees as Trigger_Attendees;
use TEC\Event_Automator\Traits\Maps\Attendees as Attendees_map;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

/**
 * Class Attendees
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Power_Automate\REST\V1\Endpoints\Queue
 */
class Attendees extends Abstract_REST_Endpoint {
	use Attendees_map;

	/**
	 * @inheritDoc
	 */
	protected $path = '/attendees';

	/**
	 * @inheritdoc
	 */
	protected static $endpoint_id = 'attendees';

	/**
	 * @inheritdoc
	 */
	protected static $type = 'queue';

	/**
	 * The trigger accessed with this endpoint.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var Trigger_Attendees
	 */
	public $trigger;

	/**
	 * Abstract_REST_Endpoint constructor.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param Api                   $api           An instance of the Power Automate API handler.
	 * @param Swagger_Documentation $documentation An instance of the Power Automate Swagger_Documentation handler.
	 * @param Trigger_Attendees    $trigger       The trigger accessed with this endpoint.
	 */
	public function __construct( Api $api, Swagger_Documentation $documentation, Trigger_Attendees $trigger ) {
		parent::__construct( $api, $documentation );
		$this->trigger = $trigger;
	}

	/**
	 * @inheritdoc
	 */
	protected function get_display_name() : string {
		return _x( 'Attendees', 'Display name of the Power Automate endpoint for attendees.', 'tribe-common' );
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
	 * Get attendees from queue.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_REST_Response The response from the attendees queue.
	 */
	public function get( WP_REST_Request $request ) {
		// No cache headers to prevent hosting from caching the endpoint
		nocache_headers();

		$current_queue = $this->trigger->get_queue();
		if ( empty( $current_queue ) ) {
			$data = [
				'attendees' => [ 'id' => 'no-new-attendees' ],
			];

			return new WP_REST_Response( $data );
		}

		$next_attendee_id = (int) array_shift( $current_queue );
		if ( empty( $next_attendee_id ) ) {
			$this->trigger->set_queue( $current_queue );
			$data = [
				'attendees' => [ 'id' => 'no-valid-attendees' ],
			];

			return new WP_REST_Response( $data );
		}

		$next_attendee = $this->get_mapped_attendee( $next_attendee_id, false, static::$service_id );
		if ( ! $next_attendee ) {
			$this->trigger->set_queue( $current_queue );
			$data = [
				'attendees' => [ 'id' => 'not-a-valid-post-type' ],
			];

			return new WP_REST_Response( $data );
		}

		$this->trigger->set_queue( $current_queue );
		$data = [
			'attendees' => [ $next_attendee ],
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
						'description' => _x( 'Returns successful checking of the new attendee queue.', 'Description for the Power Automate Attendee REST endpoint on a successful return.', 'tribe-common' ),
						'schema'      => [
							'$ref' => '#/definitions/Power-Automate',
						],
					],
					'400' => [
						'description' => _x( 'A required parameter is missing or an input parameter is in the wrong format', 'Description for the Power Automate Attendee REST endpoint missing a required parameter.', 'tribe-common' ),
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
				'description'       => _x( 'The access token to authorize Power Automate connection.', 'Description for the Power Automate Attendee REST endpoint required parameter.', 'tribe-common' ),
			],
		];
	}
}
