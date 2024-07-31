<?php
/**
 * The Zapier Refunded_Orders Triggers.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 * @package TEC\Event_Automator\Zapier\Triggers;
 */

namespace TEC\Event_Automator\Zapier\Triggers;

use EDD\Orders\Order as EDD_Order;
use TEC\Event_Automator\Zapier\Trigger_Queue\Abstract_Trigger_Queue;
use Tribe__Tickets__Tickets;
use Tribe__Tickets_Plus__Commerce__EDD__Main;
use Tribe__Tickets_Plus__Commerce__WooCommerce__Main;
use TEC\Tickets\Commerce\Module as TC_Main;
use TEC\Tickets\Commerce\Status\Refunded as TC_Refund_Status;

/**
 * Class Refunded_Orders
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Zapier\Triggers
 */
class Refunded_Orders extends Abstract_Trigger_Queue {

	/**
	 * @inheritdoc
	 */
	protected static $queue_name = 'refunded_orders';

	/**
	 * @inheritdoc
	 */
	protected function validate_for_trigger( $post_id, $data ) {
		if ( empty( $post_id ) ) {
			return false;
		}

		if ( ! $data['provider'] instanceof Tribe__Tickets__Tickets ) {
			return false;
		}

		if ( empty( $data['order_id'] ) || empty( $data['new_status'] ) ) {
			return false;
		}

		// Validate TC Tickets Order.
		if ( $data['provider'] instanceof TC_Main  ) {
			if ( $data['new_status'] instanceof TC_Refund_Status ) {
				return true;
			}

			return false;
		}

		// Validate EDD Tickets Order.
		$has_edd_tickets = $this->edd_order_has_tickets( $post_id, $data );
		if ( $has_edd_tickets ) {
			return true;
		}

		// Validate Woo Tickets Order.
		$has_woo_tickets = $this->woo_order_has_tickets( $post_id, $data );
		if ( $has_woo_tickets ) {
			return true;
		}

		return false;
	}

	/**
	 * Checks if an EDD Order has Tickets.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param int                $order_id The ID number of the order.
	 * @param array<mixed|mixed> $data     An array of data specific to the trigger and used for validation.
	 *
	 * @return boolean Whether the order has tickets.
	 */
	protected function edd_order_has_tickets( int $order_id, array $data ): bool {
		$has_tickets = false;

		if ( ! $data['provider'] instanceof Tribe__Tickets_Plus__Commerce__EDD__Main ) {
			return $has_tickets;
		}

		if (
			$data['new_status'] !== 'refunded'
			|| empty( $order_id )
		) {
			return $has_tickets;
		}

		$order = edd_get_order( $data['order_id'] );
		if ( ! $order instanceof EDD_Order ) {
			return $has_tickets;
		}

		$order_items = $order->get_items();
		if ( empty( $order_items ) ) {
			return $has_tickets;
		}

		// Iterate over each download in the order.
		foreach ( (array) $order_items as $item ) {
			$download_id = $item->__get( 'product_id' );

			// Get the event this tickets is for.
			$post_id = get_post_meta( $download_id, $data['provider']->event_key, true );
			if ( ! empty( $post_id ) ) {
				$has_tickets = true;
				break;
			}
		}

		return $has_tickets;
	}

	/**
	 * Checks if a Woo Order has Tickets.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param int                $order_id The ID number of the order.
	 * @param array<mixed|mixed> $data     An array of data specific to the trigger and used for validation.
	 *
	 * @return boolean Whether the order has tickets.
	 */
	protected function woo_order_has_tickets( int $order_id, array $data ): bool {
		$has_tickets = false;

		if ( ! $data['provider'] instanceof Tribe__Tickets_Plus__Commerce__WooCommerce__Main ) {
			return $has_tickets;
		}

		if (
			$data['new_status'] !== 'refunded'
			|| empty( $order_id )
		) {
			return $has_tickets;
		}

		$order = wc_get_order( $order_id );
		if ( empty( $order ) ) {
			return $has_tickets;
		}

		$order_items = $order->get_items();

		// Bail if the order is empty
		if ( empty( $order_items ) ) {
			return $has_tickets;
		}

		// Iterate over each product in the order.
		foreach ( (array) $order_items as $item_id => $item ) {
			$product_id = isset( $item['product_id'] ) ? $item['product_id'] : $item['id'];

			// Get the event this tickets is for.
			$post_id = get_post_meta( $product_id, $data['provider']->event_key, true );
			if ( ! empty( $post_id ) ) {
				$has_tickets = true;
				break;
			}
		}

		return $has_tickets;
	}
}
