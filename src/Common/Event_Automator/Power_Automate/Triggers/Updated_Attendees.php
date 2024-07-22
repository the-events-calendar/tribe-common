<?php
/**
 * The Power Automate Updated Attendees Triggers.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Power_Automate\Triggers;
 */

namespace TEC\Event_Automator\Power_Automate\Triggers;

use TEC\Event_Automator\Power_Automate\Trigger_Queue\Abstract_Trigger_Queue;
use Tribe__Tickets__Tickets;

/**
 * Class Updated_Attendees
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Power_Automate\Triggers
 */
class Updated_Attendees extends Abstract_Trigger_Queue {

	/**
	 * @inheritdoc
	 */
	protected static $queue_name = 'updated_attendees';

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

		if ( $provider->attendee_object !== $data['post']->post_type ) {
			return false;
		}

		$current_time = strtotime( current_time( 'mysql' ) ) - 60;
		$created_time = strtotime($data['post']->post_date);
		// If created within the last minute do not add to updated attendee queue.
		if (  $created_time >= $current_time ) {
			return false;
		}

		return true;
	}
}
