<?php
/**
 * The Zapier API Key Endpoint.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Zapier\REST\V1\Endpoints
 */

namespace TEC\Event_Automator\Zapier\REST\V1\Endpoints;

use TEC\Event_Automator\Integrations\REST\V1\Endpoints\Queue\Integration_REST_Endpoint;
use TEC\Event_Automator\Zapier\Api;
use TEC\Event_Automator\Zapier\REST\V1\Documentation\Swagger_Documentation;
use TEC\Event_Automator\Zapier\REST\V1\Traits\REST_Namespace as Zapier_REST_Namespace;
use TEC\Event_Automator\Traits\Maps\Attendees as Attendees_Map;
use TEC\Event_Automator\Traits\Maps\Event as Event_Map;
use TEC\Event_Automator\Traits\Maps\Commerce\WooCommerce as WooCommerce_Map;
use TEC\Event_Automator\Traits\Maps\Commerce\EDD as EDD_Map;
use TEC\Event_Automator\Traits\Maps\Commerce\Tickets_Commerce as Tickets_Commerce_Map;
use Tribe__Tickets__Tickets;

/**
 * Abstract REST Endpoint Zapier
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 * @since 6.0.0 Migrated to Common from Event Automator - Utilize Integration_Trigger_Queue to share coding among integrations.
 *
 * @package TEC\Event_Automator\Zapier\REST\V1\Endpoints
 */
abstract class Abstract_REST_Endpoint extends Integration_REST_Endpoint {
	use Attendees_Map;
	use Event_Map;
	use EDD_Map;
	use Tickets_Commerce_Map;
	use WooCommerce_Map;
	use Zapier_REST_Namespace;

	/**
	 * @inheritDoc
	 */
	protected static $endpoint_details_prefix = '_tec_zapier_endpoint_details_';

	/**
	 * @inheritDoc
	 */
	protected static $service_id = 'zapier';

	/**
	 * Abstract_REST_Endpoint constructor.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param Api $api An instance of the Zapier API handler.
	 * @param Swagger_Documentation $documentation An instance of the Zapier Swagger_Documentation handler.
	 */
	public function __construct( Api $api, Swagger_Documentation $documentation ) {
		$this->api                = $api;
		$this->documentation      = $documentation;
		$this->details            = $this->get_endpoint_details();
		$this->enabled            = empty( $this->details['enabled'] ) ? false : true;
		$this->missing_dependency = empty( $this->details['missing_dependency'] ) ? false : true;
	}

	/**
	 * Retrieves a list of mapped attendees from the specified queue.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param array<string|mixed> $current_queue  The queue of attendee IDs to be processed.
	 * @param bool                $add_updated_id Whether to add updated IDs to the attendees array.
	 * @param string              $empty_code     The code to return if the current queue is empty.
	 * @param string              $no_valid_code  The code to return if no valid attendees are found.
	 *
	 * @return array<string|mixed> An array of mapped attendees or an array with a specific 'id' code.
	 */
	public function get_mapped_attendees_from_queue( $current_queue, bool $add_updated_id, string $empty_code, string $no_valid_code ) {
		if ( empty( $current_queue ) ) {
			return [ [ 'id' => $empty_code ] ];
		}

		$attendees = [];
		foreach ( $current_queue as $next_attendee_id ) {
			// Ensure that $next_attendee_id is numeric before typecasting to integer.
			if ( ! is_numeric( $next_attendee_id ) ) {
				continue;
			}

			$next_attendee_id = (int) $next_attendee_id;
			$next_attendee    = $this->get_mapped_attendee( $next_attendee_id, $add_updated_id );
			if ( empty( $next_attendee ) ) {
				continue;
			}

			$attendees[] = $next_attendee;
		}

		return ! empty( $attendees ) ? $attendees : [ [ 'id' => $no_valid_code ] ];
	}

	/**
	 * Retrieves a list of mapped events from the specified queue.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param array<string|mixed> $current_queue  The queue of event IDs to be processed.
	 * @param bool                $add_updated_id Whether to add updated IDs to the events array.
	 * @param string              $empty_code     The code to return if the current queue is empty.
	 * @param string              $no_valid_code  The code to return if no valid events are found.
	 *
	 * @return array<string|mixed> An array of mapped attendees or an array with a specific 'id' code.
	 */
	public function get_mapped_events_from_queue( $current_queue, bool $add_updated_id, string $empty_code, string $no_valid_code ) {
		if ( empty( $current_queue ) ) {
			return [ [ 'id' => $empty_code ] ];
		}

		$events = [];
		foreach ( $current_queue as $next_event_id ) {
			// Ensure that $next_event_id is numeric before typecasting to integer.
			if ( ! is_numeric( $next_event_id ) ) {
				continue;
			}

			$next_event_id = (int) $next_event_id;
			$next_event    = $this->get_mapped_event( $next_event_id, $add_updated_id );
			if ( empty( $next_event ) ) {
				continue;
			}

			$events[] = $next_event;
		}

		return ! empty( $events ) ? $events : [ [ 'id' => $no_valid_code ] ];
	}

	/**
	 * Retrieves a list of mapped orders from the specified queue.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param array<string|mixed> $current_queue The queue of order IDs to be processed.
	 * @param string              $empty_code    The code to return if the current queue is empty.
	 * @param string              $no_valid_code The code to return if no valid orders are found.
	 *
	 * @return array<string|mixed> An array of mapped attendees or an array with a specific 'id' code.
	 */
	public function get_mapped_orders_from_queue( $current_queue, string $empty_code, string $no_valid_code ) {
		if ( empty( $current_queue ) ) {
			return [ [ 'id' => $empty_code ] ];
		}

		$orders = [];
		foreach ( $current_queue as $next_order_id ) {
			// Ensure that $next_order_id is numeric before typecasting to integer.
			if ( ! is_numeric( $next_order_id ) ) {
				continue;
			}

			$next_order_id = (int) $next_order_id;
			$provider      = tribe_tickets_get_ticket_provider( $next_order_id );
			if ( empty( $provider->orm_provider ) && class_exists( 'Tribe__Tickets_Plus__Commerce__EDD__Main', false ) && function_exists( 'edd_get_order' ) ) {
				$next_order = edd_get_order( $next_order_id );

				if ( $next_order instanceof \EDD\Orders\Order ) {
					/** @var \Tribe__Tickets_Plus__Commerce__EDD__Main $commerce_edd */
					$provider = tribe( 'tickets-plus.commerce.edd' );
				}
			}

			if ( ! $provider instanceof Tribe__Tickets__Tickets ) {
				continue;
			}

			if ( $provider->orm_provider === 'woo' ) {
				$next_order = $this->get_woo_order_by_id( $next_order_id );
			} elseif ( $provider->orm_provider === 'edd' ) {
				$next_order = $this->get_edd_order_by_id( $next_order_id );
			} elseif ( $provider->orm_provider === 'tickets-commerce' ) {
				$next_order = $this->get_tc_order_by_id( $next_order_id );
			}
			if ( empty( $next_order ) ) {
				continue;
			}

			$orders[] = $next_order;
		}

		return ! empty( $orders ) ? $orders : [ [ 'id' => $no_valid_code ] ];
	}

	/**
	 * Modifies a request argument marking it as not required.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param array<string|mixed> $arg An array of arguements.
	 */
	protected function unrequire_arg( array &$arg ) {
		$arg['required'] = false;
	}
}
