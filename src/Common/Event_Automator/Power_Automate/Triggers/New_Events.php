<?php
/**
 * The Power Automate New Event Queue.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Power_Automate\Triggers
 */

namespace TEC\Event_Automator\Power_Automate\Triggers;

use TEC\Event_Automator\Power_Automate\Trigger_Queue\Abstract_Trigger_Queue;
use Tribe__Events__Main as TEC;
use WP_Post;
/**
 * Class New_Events
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Power_Automate\Triggers
 */
class New_Events extends Abstract_Trigger_Queue {
	/**
	 * @inheritdoc
	 */
	protected static $queue_name = 'new_events';

	/**
	 * @inheritdoc
	 */
	protected static $added_to_queue_meta_field = 'new_event_run_once';

	/**
	 * @inheritdoc
	 */
	protected function validate_for_trigger( $post_id, $data ) {
		if ( empty( $data['post'] ) || ! $data['post'] instanceof WP_Post ) {
			return false;
		}
		$post = $data['post'];

		if ( $post->post_type !== TEC::POSTTYPE ) {
			return false;
		}

		if ( $post->post_status !== 'publish' ) {
			return false;
		}

		$run_once = tribe_is_truthy( get_post_meta( $post_id, $this->get_meta_field_name(), true ) );
		if ( $run_once ) {
			return false;
		}

		// Set meta field so this event is added only once to the queue.
		update_post_meta( $post_id, $this->get_meta_field_name(), true );

		return true;
	}
}
