<?php
/**
 * The Zapier Updated Event Triggers.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 * @package TEC\Event_Automator\Zapier\Triggers;
 */

namespace TEC\Event_Automator\Zapier\Triggers;

use TEC\Event_Automator\Zapier\Trigger_Queue\Abstract_Trigger_Queue;
use Tribe__Events__Main as TEC;

/**
 * Class Updated_Events
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Zapier\Triggers
 */
class Updated_Events extends Abstract_Trigger_Queue {

	/**
	 * @inheritdoc
	 */
	protected static $queue_name = 'updated_events';

	/**
	 * @inheritdoc
	 */
	protected function validate_for_trigger( $post_id, $data ) {
		if ( empty( $data['post'] ) ) {
			return false;
		}
		$post = $data['post'];

		if ( $post->post_type !== TEC::POSTTYPE ) {
			return false;
		}

		if ( $post->post_status !== 'publish' ) {
			return false;
		}

		// On new events this will be empty and the new event trigger will handle the event.
		$event_start = get_post_meta( $post_id, '_EventStartDate', true );
		if ( ! $event_start ) {
			return false;
		}

		return true;
	}
}
