<?php
/**
 * The Zapier Refunded Orders Endpoint.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Zapier\REST\V1\Endpoints;
 */

namespace TEC\Event_Automator\Zapier\REST\V1\Endpoints;

use TEC\Event_Automator\Zapier\Api;
use TEC\Event_Automator\Zapier\REST\V1\Documentation\Swagger_Documentation;
use TEC\Event_Automator\Zapier\Triggers\Refunded_Orders as Trigger_Refunded_Orders;
use WP_REST_Server;
use WP_REST_Request;
use WP_REST_Response;
use TEC\Event_Automator\Traits\Maps\Commerce\WooCommerce;
use TEC\Event_Automator\Traits\Maps\Commerce\EDD;
use TEC\Event_Automator\Traits\Maps\Commerce\Tickets_Commerce;
use Tribe__Tickets__Tickets;

/**
 * Class Refunded_Orders
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Zapier\REST\V1\Endpoints
 */
class Refunded_Orders extends Abstract_REST_Endpoint {
	use WooCommerce;
	use EDD;
	use Tickets_Commerce;

	/**
	 * @inheritDoc
	 */
	protected $path = '/refunded-orders';

	/**
	 * @inheritdoc
	 */
	protected static $endpoint_id = 'refunded_orders';

	/**
	 * @inheritdoc
	 */
	protected static $type = 'queue';

	/**
	 * Abstract_REST_Endpoint constructor.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param Api                     $api           An instance of the Zapier API handler.
	 * @param Swagger_Documentation   $documentation An instance of the Zapier Swagger_Documentation handler.
	 * @param Trigger_Refunded_Orders $trigger       The trigger accessed with this endpoint.
	 */
	public function __construct( Api $api, Swagger_Documentation $documentation, Trigger_Refunded_Orders $trigger ) {
		parent::__construct( $api, $documentation );
		$this->trigger = $trigger;
	}

	/**
	 * @inheritdoc
	 */
	protected function get_display_name() : string {
		return _x( 'Refunded Orders', 'Display name of the Zapier endpoint for refunded ticket orders.', 'tribe-common' );
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
	 * Get events from refunded orders queue.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_REST_Response The response from the refunded orders queue.
	 */
	public function get( WP_REST_Request $request ) {
		// No cache headers to prevent hosting from caching the endpoint
		nocache_headers();

		$loaded = $this->verify_and_load_key( $request );
		if ( is_wp_error( $loaded ) ) {
			return new WP_REST_Response( $loaded, 400 );
		}

		$orders = $this->get_mapped_orders_from_queue( $this->trigger->get_queue(), 'no-new-refunded-orders', 'no-valid-refunded-orders' );

		return new WP_REST_Response( $orders );
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
						'description' => _x(
							'Returns successful checking of the refunded orders queue.',
							'Description for the Zapier Refunded Order REST endpoint on a successful return.',
							'tribe-common'
						),
						'schema'      => [
							'$ref' => '#/definitions/Zapier',
						],
					],
					'400' => [
						'description' => _x(
							'A required parameter is missing or an input parameter is in the wrong format',
							'Description for the Zapier Refunded Order REST endpoint missing a required parameter.',
							'tribe-common'
						),
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
				'description'       => _x(
					'The access token to authorize Zapier connection.',
					'Description for the Zapier Refunded Order REST endpoint required parameter.',
					'tribe-common'
				),
			],
		];
	}
}
