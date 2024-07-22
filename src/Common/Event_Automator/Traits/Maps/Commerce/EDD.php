<?php
/**
 * Provides methods to format EDD data.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Traits\Maps\Commerce;
 */

namespace TEC\Event_Automator\Traits\Maps\Commerce;

use EDD\Orders\Order as EDD_Order;

/**
 * Trait With_AJAX
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Traits\Maps\Commerce;
 */
trait EDD {

	/**
	 * Get the EDD Order
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 * @since 6.0.0 Migrated to Common from Event Automator - Add encode arrays.
	 *
	 * @param int    $order_id   The EDD order id.
	 * @param string $service_id The service id used to modify the mapped event details.
	 *
	 * @return array<string|mixed> An array of orders details or false if not a post object.
	 */
	protected function get_edd_order_by_id( int $order_id, string $service_id = '' ) {
		if ( ! tribe_tickets_is_woocommerce_active() ) {
			return [];
		}

		$order = edd_get_order( $order_id );
		if ( ! $order instanceof EDD_Order ) {
			return [ 'id' => 'no-edd-order' ];
		}

		$order_address = $order->get_address();
		$next_order = [
			'id'                   => 'edd-' . $order->__get( 'id' ),
			'order_id'             => strval( $order->get_number() ),
			'order_number'         => $order->get_number(),
			'order_date'           => date( 'Y-m-d\TH:i:s\Z', strtotime( $order->__get( 'date_created' ) ) ),
			'status'               => $order->status,
			'tax_total'            => floatval( $order->__get( 'tax' ) ),
			'discount_total'       => floatval( $order->__get( 'discount' ) ),
			'order_total'          => floatval( $order->__get( 'total' ) ),
			'order_currency'       => $order->__get( 'currency' ),
			'payment_method'       => $order->__get( 'gateway' ),
			'customer_id'          => intval( $order->__get( 'customer_id' ) ),
			'customer_user'        => intval( $order->__get( 'user_id' ) ),
			'customer_email'       => $order->__get( 'email' ),
			'billing_first_name'   => $order_address->first_name,
			'billing_last_name'    => $order_address->last_name,
			'billing_address_1'    => $order_address->address,
			'billing_address_2'    => $order_address->address2,
			'billing_postcode'     => $order_address->postal_code,
			'billing_city'         => $order_address->region,
			'billing_state'        => $order_address->address,
			'billing_country'      => $order_address->country,
			'customer_note'        => $this->get_edd_customer_notes( $order ),
		];


		// Add order items.
		foreach ( $order->get_items() as $item ) {
			$next_order['items'][] = [
				'ticket_id'   => (int) $item->__get( 'id' ),
				'ticket_name' => $item->get_order_item_name(),
				'price'       => floatval( edd_get_price_option_amount( $item->__get( 'id' ), $item->__get( 'price_id' ) ) ),
				'quantity'    => (int) $item->__get( 'quantity' ),
				'subtotal'    => floatval( $item->__get( 'subtotal' ) ),
				'total'       => floatval( $item->__get( 'total' ) ),
				'tax'         => floatval( $item->__get( 'tax' ) ),
			];
		}

		/**
		 * Filters the order information for EDD that is sent to Zapier.
		 *
		 * @since 6.0.0 Migrated to Common from Event Automator
		 * @since 6.0.0 Migrated to Common from Event Automator - Add Service ID.
		 *
		 * @param array<string|mixed> $next_order An array of EDD order details.
		 * @param EDD_Order           $order      An instance of the EDD order object.
		 * @param string              $service_id The service id used to modify the mapped event details.
		 */
		$next_order = apply_filters( 'tec_automator_map_edd_order_details', $next_order, $order, $service_id );
		// Zapier only requires an id field, if that is empty send a generic invalid message.
		if ( empty( $next_order['id'] ) ) {
			return [ 'id' => 'invalid-order-id.' ];
		}

		return $next_order;
	}

	/**
	 * Get EDD Customer Order Notes.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param EDD\Orders\Order $order An instance of the EDD order object.
	 *
	 * @return array<string|mixed> $notes_array An array of customer order notes.
	 */
	public function get_edd_customer_notes( $order ): array {
		$notes = $order->get_notes();
		if ( empty( $notes ) ) {
			return [];
		}

		$notes_array = [];
		foreach ( $notes as $item ) {
			$notes_array[] = [
				'order_note_content'      => $item->content,
				'order_note_object_type'  => $item->object_type,
				'order_note_date_created' => $item->date_created,
			];
		}

		return $notes_array;
	}
}
