<?php
/**
 * Provides methods to format WooCommerce data.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Traits\Maps\Commerce;
 */

namespace TEC\Event_Automator\Traits\Maps\Commerce;

use WC_Order;

/**
 * Trait With_AJAX
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Traits\Maps\Commerce;
 */
trait WooCommerce {

	/**
	 * Get the WooCommerce Order
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 * @since 6.0.0 Migrated to Common from Event Automator - Add encode arrays.
	 *
	 * @param int    $order_id   The Woocommerce order id.
	 * @param string $service_id The service id used to modify the mapped event details.
	 *
	 * @return array<string|mixed> An array of orders details or false if not a post object.
	 */
	protected function get_woo_order_by_id( int $order_id, string $service_id = '' ) {
		if ( ! tribe_tickets_is_woocommerce_active() ) {
			return [];
		}

		$order = wc_get_order( $order_id );
		if ( ! $order instanceof WC_Order ) {
			return [ 'id' => 'no-woocommerce-order' ];
		}

		$next_order = [
			'id'                   => 'woo-' . $order->get_id(),
			'order_id'             => strval( $order->get_id() ),
			'order_number'         => $order->get_order_number(),
			'order_date'           => date( 'Y-m-d\TH:i:s\Z', strtotime( get_post( $order->get_id() )->post_date ) ),
			'status'               => $order->get_status(),
			'shipping_total'       => $order->get_shipping_total(),
			'shipping_tax_total'   => wc_format_decimal( $order->get_shipping_tax(), 2 ),
			'tax_total'            => floatval( wc_format_decimal( $order->get_total_tax(), 2 ) ),
			'discount_total'       => floatval( wc_format_decimal( $order->get_total_discount(), 2 ) ),
			'order_total'          => floatval( wc_format_decimal( $order->get_total(), 2 ) ),
			'order_currency'       => $order->get_currency(),
			'payment_method'       => $order->get_payment_method(),
			'shipping_method'      => $order->get_shipping_method(),
			'customer_id'          => intval( $order->get_user_id() ),
			'customer_user'        => intval( $order->get_user_id() ),
			'customer_email'       => $order->get_billing_email(),
			'billing_first_name'   => $order->get_billing_first_name(),
			'billing_last_name'    => $order->get_billing_last_name(),
			'billing_company'      => $order->get_billing_company(),
			'billing_email'        => $order->get_billing_email(),
			'billing_phone'        => $order->get_billing_phone(),
			'billing_address_1'    => $order->get_billing_address_1(),
			'billing_address_2'    => $order->get_billing_address_2(),
			'billing_postcode'     => $order->get_billing_postcode(),
			'billing_city'         => $order->get_billing_city(),
			'billing_state'        => $order->get_billing_state(),
			'billing_country'      => $order->get_billing_country(),
			'shipping_first_name'  => $order->get_shipping_first_name(),
			'shipping_last_name'   => $order->get_shipping_last_name(),
			'shipping_company'     => $order->get_shipping_company(),
			'shipping_address_1'   => $order->get_shipping_address_1(),
			'shipping_address_2'   => $order->get_shipping_address_2(),
			'shipping_postcode'    => $order->get_shipping_postcode(),
			'shipping_city'        => $order->get_shipping_city(),
			'shipping_state'       => $order->get_shipping_state(),
			'shipping_country'     => $order->get_shipping_country(),
			'customer_note'        => $this->get_woo_customer_notes( $order ),
		];

		// Get and Loop Over Order Items.
		foreach ( $order->get_items() as $item_id => $item ) {
			$product = wc_get_product( $item->get_product_id() );

			$meta = $this->get_woo_ticket_meta( $item );

			$next_order['items'][] = [
				'ticket_id'    => $item->get_product_id(),
				'ticket_name'  => $item->get_name(),
				'price'        => floatval( $product->get_price() ),
				'quantity'     => (int) $item->get_quantity(),
				'subtotal'     => floatval( $item->get_subtotal() ),
				'total'        => floatval( $item->get_total() ),
				'meta'         => $meta,
				'variation_id' => $item->get_variation_id(),
				'tax'          => floatval( $item->get_subtotal_tax() ),
				'tax_class'    => $item->get_tax_class(),
				'tax_status'   => $item->get_tax_status(),
			];
		}

		/**
		 * Filters the order information for WooCommerce that is sent to Zapier.
		 *
		 * @since 6.0.0 Migrated to Common from Event Automator
		 * @since 6.0.0 Migrated to Common from Event Automator - Add Service ID.
		 *
		 * @param array<string|mixed> $next_order An array of WooCommerce order details.
		 * @param WC_Order            $order      An instance of the WooCommerce order object.
		 * @param string              $service_id The service id used to modify the mapped event details.
		 */
		$next_order = apply_filters( 'tec_automator_map_woo_order_details', $next_order, $order, $service_id );
		// Zapier only requires an id field, if that is empty send a generic invalid message.
		if ( empty( $next_order['id'] ) ) {
			return [ 'id' => 'invalid-order-id.' ];
		}

		return $next_order;
	}

	/**
	 * Get WooCommerce Ticket Meta.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param WC_Order_Item_Product $item An instance of the WooCommerce order item object.
	 *
	 * @return array<string|mixed> $meta_array  Formatted meta array of ticket meta.
	 */
	public function get_woo_ticket_meta( $item ) {
		$meta = $item->get_meta_data();
		if ( empty( $meta ) ) {
			return [];
		}

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
				'ticket_meta_name'  => $data['key'],
				'ticket_meta_value' => $value,
			];
		}

		return $meta_array;
	}

	/**
	 * Get WooCommerce Customer Order Notes.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param WC_Order $order An instance of the WooCommerce order object.
	 *
	 * @return array<string|mixed> $notes_array An array of customer order notes.
	 */
	public function get_woo_customer_notes( $order ) {
		$notes = $order->get_customer_order_notes();
		if ( empty( $notes ) ) {
			return [];
		}

		$notes_array = [];
		foreach ( $notes as $item ) {
			$notes_array[] = [
				'order_note_content'      => $item->comment_content,
				'order_note_object_type'  => $item->comment_type,
				'order_note_date_created' => $item->comment_date,
			];
		}

		return $notes_array;
	}
}
