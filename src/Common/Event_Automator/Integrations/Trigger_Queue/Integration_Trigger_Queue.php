<?php
/**
 * Integrations Abstract Trigger Queue.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 * @package TEC\Event_Automator\Integrations\Triggers;
 */

namespace TEC\Event_Automator\Integrations\Trigger_Queue;

/**
 * Class Integration_Trigger_Queue
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Integrations\Trigger_Queue
 */
abstract class Integration_Trigger_Queue {

	/**
	 * The API ID for the Trigger Queue.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	protected static $api_id;

	/**
	 * Queue name prefix.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	protected static $queue_prefix;

	/**
	 * Queue name.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	protected static $queue_name = '';

	/**
	 * Meta field name when a custom post is added to a trigger queue to prevent duplicate.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	protected static $added_to_queue_meta_field = '';

	/**
	 * Get the queue name for the trigger.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return string The queue name with prefix added.
	 */
	public function get_queue_name() {
		return static::$queue_prefix . static::$queue_name;
	}

	/**
	 * Get the meta field name saved when an item is added to the queue.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return string The meta field name with prefix added.
	 */
	public function get_meta_field_name() {
		return static::$queue_prefix . static::$added_to_queue_meta_field;
	}

	/**
	 * Get the array of custom post ids in the queue.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return array<string> An array of custom post ids.
	 */
	public function get_queue() {
		$endpoint_queue = (array) get_transient( $this->get_queue_name() );

		return array_filter( $endpoint_queue );
	}

	/**
	 * Set the queue transient.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param array<string|integer> $queue An array of
	 *
	 * @return bool
	 */
	public function set_queue( array $queue ) {
		return set_transient( $this->get_queue_name(), $queue );
	}

	/**
	 * Validate the hook to determine if the custom post should be added to a trigger queue.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param integer            $post_id A WordPress custom post id.
	 * @param array<mixed|mixed> $data    An array of data specific to the trigger and used for validation.
	 *
	 * @return boolean Whether the hooked action is valid for this trigger.
	 */
	abstract protected function validate_for_trigger( $post_id, $data );

	/**
	 * Add a custom post id to a trigger queue.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param integer            $post_id A WordPress custom post id.
	 * @param array<mixed|mixed> $data    An array of data specific to the trigger and used for validation.
	 */
	public function add_to_queue( $post_id, $data ) {
		$api_id = static::$api_id;
		$queue_name = static::$queue_name;
		/**
		 * Allows filtering whether items should be added to queues.
		 *
		 * @since 6.0.0 Migrated to Common from Event Automator
		 *
		 * @param boolean $enable_add_to_queue Whether to add items to queues, default to false, enabled by each service.
		 */
		$enable_add_to_queue = apply_filters( "tec_event_automator_{$api_id}_enable_add_to_queues", false );
		if ( ! $enable_add_to_queue ) {
			return;
		}

		$process = $this->validate_for_trigger( $post_id, $data );

		/**
		 * Filters whether a post id should be added to a trigger queue.
		 *
		 * @since 6.0.0 Migrated to Common from Event Automator
		 *
		 * @param boolean            $process Whether to add post id to a integration trigger queue.
		 * @param integer            $post_id A WordPress custom post id.
		 * @param array<mixed|mixed> $data    An array of data specific to the trigger and used for validation.
		 */
		$process = (bool) apply_filters( "tec_event_automator_{$api_id}_add_to_queue", $process, $post_id, $data );
		if ( ! $process ) {
			return;
		}

		$current_queue = $this->get_queue();

		if ( empty( $current_queue ) ) {
			$this->set_queue( [ $post_id ] );

			return;
		}

		// Remove duplicates.
		$current_queue = array_diff( $current_queue, [ $post_id ] );

		// Add $post_id to the beginning of the queue.
		array_unshift( $current_queue, $post_id );

		/**
		 * Filters the maximum number of items to keep in the current queue.
		 *
		 * @since 6.0.0 Migrated to Common from Event Automator
		 *
		 * @param int $max_items The maximum number of items to keep in the queue, default to 15.
		 */
		$max_items = apply_filters( "tec_event_automator_{$api_id}_max_queue_items", 15 );

		/**
		 * Filters the maximum number of items to keep in the current queue.
		 *
		 * @since 6.0.0 Migrated to Common from Event Automator
		 *
		 * @param int $max_items The maximum number of items to keep in the queue, default to 15.
		 */
		$max_items = apply_filters( "tec_event_automator_{$api_id}_max_queue_items_{$queue_name}", $max_items );

		// Remove items from the end of the queue if over the max.
		if ( count( $current_queue ) > $max_items ) {
			$current_queue = array_slice( $current_queue, 0, $max_items );
		}

		$this->set_queue( $current_queue );
	}
}
