<?php
/**
 * The Power Automate Orders Triggers.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 * @package TEC\Event_Automator\Power_Automate\Triggers;
 */

namespace TEC\Event_Automator\Power_Automate\Triggers;

use TEC\Event_Automator\Power_Automate\Trigger_Queue\Abstract_Trigger_Queue;
use Tribe__Tickets__Tickets;

/**
 * Class Orders
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Power_Automate\Triggers
 */
class Orders extends Abstract_Trigger_Queue {

	/**
	 * @inheritdoc
	 */
	protected static $queue_name = 'orders';

	/**
	 * @inheritdoc
	 */
	protected static $added_to_queue_meta_field = 'power_automate_order_run_once';

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

		$run_once = get_post_meta( $post_id, $this->get_meta_field_name() );
		if ( $data['provider']->orm_provider === 'edd' ) {
			$run_once = edd_get_order_meta( $post_id, $this->get_meta_field_name(), true );
		}

		if ( $run_once ) {
			return false;
		}

		// Set meta field so this event is added only once to the queue.
		if ( $data['provider']->orm_provider === 'edd' ) {
			edd_update_order_meta( $post_id, $this->get_meta_field_name(), true );
		} else {
			update_post_meta( $post_id, $this->get_meta_field_name(), true );
		}

		return true;
	}
}
