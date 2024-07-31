<?php
/**
 * The Power Automate Checkin Triggers.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 * @package TEC\Event_Automator\Power_Automate\Triggers;
 */

namespace TEC\Event_Automator\Power_Automate\Triggers;

use TEC\Event_Automator\Power_Automate\Trigger_Queue\Abstract_Trigger_Queue;
use Tribe__Tickets__Tickets;

/**
 * Class Checkin
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Power_Automate\Triggers
 */
class Checkin extends Abstract_Trigger_Queue {

	/**
	 * @inheritdoc
	 */
	protected static $queue_name = 'checkin';

	/**
	 * @inheritdoc
	 */
	protected function validate_for_trigger( $post_id, $data ) {
		if ( empty( $post_id ) ) {
			return false;
		}

		/** @var Cache $cache */
		$cache = tribe( 'cache' );
		$cache_key = "eva-power-automate-checkin-{$post_id}";
		$processed_checkin = $cache->get( $cache_key );
		// Bulk checkins causes the checkin to be triggered twice, this prevents the second trigger from adding to the queue.
		if ( ! empty( $processed_checkin ) ) {
			return false;
		}

		$provider = tribe_tickets_get_ticket_provider( $post_id );
		if ( ! $provider instanceof Tribe__Tickets__Tickets ) {
			return false;
		}

		$cache->set( $cache_key, true, -1 );

		return true;
	}
}
