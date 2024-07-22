<?php
/**
 * Provides methods to format Tickets Commerce data.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Traits\Maps\Commerce;
 */

namespace TEC\Event_Automator\Traits\Maps\Commerce;

use WP_Post;

/**
 * Trait With_AJAX
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Traits\Maps\Commerce;
 */
trait Tickets_Commerce {

	/**
	 * Get the Tickets Commerce Order
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 * @since 6.0.0 Migrated to Common from Event Automator - Add encode arrays.
	 *
	 * @param int    $order_id   The Tickets Commerce order id.
	 * @param string $service_id The service id used to modify the mapped event details.
	 *
	 * @return array<string|mixed> An array of orders details or false if not a post object.
	 */
	protected function get_tc_order_by_id( int $order_id, string $service_id = '' ) {
		if ( ! function_exists( 'tec_tc_get_order' ) ) {
			return [];
		}

		$order = tec_tc_get_order( $order_id );
		if ( ! $order instanceof WP_Post ) {
			return [ 'id' => 'no-tc-order' ];
		}

		$next_order = [
			'id'                 => 'tc-' . $order->ID,
			'order_id'           => strval( $order->order_id ),
			'order_number'       => strval( $order->order_id ),
			'order_date'         => date( 'Y-m-d\TH:i:s\Z', strtotime( $order->purchase_time ) ),
			'status'             => $order->status,
			'order_total'        => floatval( $order->total ),
			'order_currency'     => $order->currency,
			'payment_method'     => $order->gateway,
			'customer_id'        => intval( $order->purchaser['user_id'] ),
			'customer_user'      => intval( $order->purchaser['user_id'] ),
			'customer_email'     => $order->purchaser_email,
			'billing_first_name' => $order->purchaser['first_name'],
			'billing_last_name'  => $order->purchaser['last_name'],
		];

		// Add order items.
		foreach ( $order->items as $item ) {

			$meta = $this->get_tc_ticket_meta( $item );

			$next_order['items'][] = [
				'ticket_id'   => $item['ticket_id'],
				'ticket_name' => get_the_title( $item['ticket_id'] ),
				'price'       => floatval( $item['price'] ),
				'quantity'    => (int) $item['quantity'],
				'subtotal'    => floatval( $item['sub_total'] ),
				'meta'        => $meta,
			];
		}

		/**
		 * Filters the order information for Tickets Commerce that is sent to Zapier.
		 *
		 * @since 6.0.0 Migrated to Common from Event Automator
		 * @since 6.0.0 Migrated to Common from Event Automator - Add Service ID.
		 *
		 * @param array<string|mixed> $next_order An array of Tickets Commerce order details.
		 * @param WP_Post             $order      An instance of the Tickets Commerce order object.
		 * @param string              $service_id The service id used to modify the mapped event details.
		 */
		$next_order = apply_filters( 'tec_automator_map_tickets_commerce_order_details', $next_order, $order, $service_id );
		// Zapier only requires an id field, if that is empty send a generic invalid message.
		if ( empty( $next_order['id'] ) ) {
			return [ 'id' => 'invalid-order-id.' ];
		}

		return $next_order;
	}

	/**
	 * Get Tickets Commerce Ticket Meta.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param array<string|mixed> $item An array ot Tickets Commerce order item.
	 *
	 * @return array<string|mixed> $meta_array  Formatted meta array of ticket meta.
	 */
	public function get_tc_ticket_meta( $item ) {
		$meta = $item['extra'];
		if ( empty( $meta ) ) {
			return [];
		}

		return [];

		$meta_array = [];
		foreach ( $meta as $meta_item ) {
			$data = $meta_item->get_data();
			if ( empty( $data ) ) {
				continue;
			}
			$value = $data['value'];

			// If value is an array, convert each item to a string and remove the keys.
			if ( is_array( $value ) ) {
				$value = array_values( array_map( 'strval', $value ) );
			} else {
				$value = [ (string) $value ];
			}

			$meta_array[] = [
				'ticket_meta_id'    => $data['id'],
				'ticket_meta_name'  => $data['name'],
				'ticket_meta_value' => $value,
			];
		}

		return $meta_array;
	}
}
