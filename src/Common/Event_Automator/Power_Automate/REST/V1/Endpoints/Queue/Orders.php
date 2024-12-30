<?php
/**
 * The Power Automate Orders Endpoint.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Power_Automate\REST\V1\Endpoints\Queue;
 */

namespace TEC\Event_Automator\Power_Automate\REST\V1\Endpoints\Queue;

use TEC\Event_Automator\Power_Automate\Api;
use TEC\Event_Automator\Power_Automate\REST\V1\Documentation\Swagger_Documentation;
use TEC\Event_Automator\Power_Automate\REST\V1\Endpoints\Abstract_REST_Endpoint;
use TEC\Event_Automator\Power_Automate\Triggers\Orders as Trigger_Orders;
use WP_REST_Server;
use WP_REST_Request;
use WP_REST_Response;
use TEC\Event_Automator\Traits\Maps\Commerce\WooCommerce;
use TEC\Event_Automator\Traits\Maps\Commerce\EDD;
use TEC\Event_Automator\Traits\Maps\Commerce\Tickets_Commerce;
use Tribe__Tickets__Tickets;

/**
 * Class Orders
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Power_Automate\REST\V1\Endpoints
 */
class Orders extends Abstract_REST_Endpoint {
	use WooCommerce;
	use EDD;
	use Tickets_Commerce;

	/**
	 * @inheritDoc
	 */
	protected $path = '/orders';

	/**
	 * @inheritdoc
	 */
	protected static $endpoint_id = 'orders';

	/**
	 * @inheritdoc
	 */
	protected static $type = 'queue';

	/**
	 * The trigger accessed with this endpoint.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var Trigger_Orders
	 */
	public $trigger;

	/**
	 * Abstract_REST_Endpoint constructor.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param Api                   $api           An instance of the Power Automate API handler.
	 * @param Swagger_Documentation $documentation An instance of the Power Automate Swagger_Documentation handler.
	 * @param Trigger_Orders  $trigger       The trigger accessed with this endpoint.
	 */
	public function __construct( Api $api, Swagger_Documentation $documentation, Trigger_Orders $trigger ) {
		parent::__construct( $api, $documentation );
		$this->trigger = $trigger;
	}

	/**
	 * @inheritdoc
	 */
	protected function get_display_name() : string {
		return _x( 'Orders', 'Display name of the Power Automate endpoint for ticket orders.', 'tribe-common' );
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
				'orders' => [ 'id' => 'no-new-sales' ],
			];

			return new WP_REST_Response( $data );
		}

		$next_order_id = (int) array_shift( $current_queue );
		if ( empty( $next_order_id ) ) {
			$this->trigger->set_queue( $current_queue );
			$data = [
				'orders' => [ 'id' => 'no-valid-sales' ],
			];

			return new WP_REST_Response( $data );
		}

		$provider = tribe_tickets_get_ticket_provider( $next_order_id );
		if (
			class_exists( 'Tribe__Tickets_Plus__Commerce__EDD__Main', false ) &&
			function_exists( 'edd_get_order')
		) {
			$next_order = edd_get_order( $next_order_id );

			if ( $next_order instanceof \EDD\Orders\Order ) {
				/** @var \Tribe__Tickets_Plus__Commerce__EDD__Main $commerce_edd */
				$provider = tribe( 'tickets-plus.commerce.edd' );
			}
		}

		if ( ! $provider instanceof Tribe__Tickets__Tickets ) {
			$this->trigger->set_queue( $current_queue );
			$data = [
				'orders' => [ 'id' => 'no-valid-provider' ],
			];

			return new WP_REST_Response( $data );
		}

		$next_order = [];
		if ( $provider->orm_provider === 'woo' ) {
			$next_order = $this->get_woo_order_by_id( $next_order_id, static::$service_id );
		} elseif ( $provider->orm_provider === 'edd' ) {
			$next_order = $this->get_edd_order_by_id( $next_order_id, static::$service_id );
		} elseif ( $provider->orm_provider === 'tickets-commerce' ) {
			$next_order = $this->get_tc_order_by_id( $next_order_id, static::$service_id );
		}

		if ( empty( $next_order['id'] ) ) {
			$this->trigger->set_queue( $current_queue );
			$data = [
				'orders' => [ 'id' => 'not-a-valid-sale' ],
			];

			return new WP_REST_Response( $data );
		}

		$this->trigger->set_queue( $current_queue );
		$data = [
			'orders' => [ $next_order ],
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
						'description' => _x( 'Returns successful checking of the new event queue.', 'Description for the Power Automate Order REST endpoint on a successful return.', 'tribe-common' ),
						'schema'      => [
							'$ref' => '#/definitions/Power_Automate',
						],
					],
					'400' => [
						'description' => _x( 'A required parameter is missing or an input parameter is in the wrong format', 'Description for the Power Automate Order REST endpoint missing a required parameter.', 'tribe-common' ),
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
				'description'       => _x( 'The access token to authorize Power Automate connection.', 'Description for the Power Automate Checkin REST endpoint required parameter.', 'tribe-common' ),
			],
		];
	}
}
