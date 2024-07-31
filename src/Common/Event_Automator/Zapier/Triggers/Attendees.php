<?php
/**
 * The Zapier New Event Triggers.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 * @package TEC\Event_Automator\Zapier\Triggers;
 */

namespace TEC\Event_Automator\Zapier\Triggers;

use TEC\Event_Automator\Zapier\Trigger_Queue\Abstract_Trigger_Queue;
use Tribe__Tickets__Tickets;

/**
 * Class New_Events
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Zapier\Triggers
 */
class Attendees extends Abstract_Trigger_Queue {

	/**
	 * @inheritdoc
	 */
	protected static $queue_name = 'attendees';

	/**
	 * @inheritdoc
	 */
	protected static $added_to_queue_meta_field = 'attendee_run_once';

	/**
	 * @inheritdoc
	 */
	protected function validate_for_trigger( $post_id, $data ) {
		if ( empty( $post_id ) ) {
			return false;
		}

		$provider = tribe_tickets_get_ticket_provider( $post_id );
		if ( ! $provider instanceof Tribe__Tickets__Tickets ) {
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
