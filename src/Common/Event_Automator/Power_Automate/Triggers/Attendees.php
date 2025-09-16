<?php
/**
 * The Power Automate Attendees Queue.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Power_Automate\Triggers
 */

namespace TEC\Event_Automator\Power_Automate\Triggers;

use TEC\Event_Automator\Power_Automate\Trigger_Queue\Abstract_Trigger_Queue;
use Tribe__Tickets__Tickets as Tribe__Tickets__TicketsAlias;

/**
 * Class Attendees
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Power_Automate\Triggers
 */
class Attendees extends Abstract_Trigger_Queue {
	/**
	 * @inheritdoc
	 */
	protected static $queue_name = 'attendees';

	/**
	 * @inheritdoc
	 */
	protected static $added_to_queue_meta_field = 'attendees_run_once';

	/**
	 * @inheritdoc
	 */
	protected function validate_for_trigger( $post_id, $data ) {
		if ( empty( $post_id ) ) {
			return false;
		}

		$provider = tribe_tickets_get_ticket_provider( $post_id );
		if ( ! $provider instanceof Tribe__Tickets__TicketsAlias ) {
			return false;
		}

		$run_once = get_post_meta( $post_id, $this->get_meta_field_name() );
		if ( $run_once ) {
			return false;
		}

		// Set meta field so this event is added only once to the queue.
		update_post_meta( $post_id, $this->get_meta_field_name(), true );

		return true;
	}
}
