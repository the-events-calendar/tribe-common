<?php
/**
 * Zapier Abstract Trigger Queue.
 *
 * @since   TBD
 * @package TEC\Events\Zapier\Triggers;
 */

namespace TEC\Common\Zapier\Trigger_Queue;

use WP_Post;

/**
 * Class Abstract_Trigger_Queue
 *
 * @since   TBD
 *
 * @package TEC\Common\Zapier\Trigger_Queue
 */
abstract class Abstract_Trigger_Queue {

	/**
	 * Queue name prefix.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected static $queue_prefix = 'tec_zapier_queue_';

	/**
	 * Queue name.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected static $queue_name = '';

	/**
	 * Meta field name when a custom post is added to a trigger queue to prevent duplicate.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected static $added_to_queue_meta_field = '';

	/**
	 * Get the queue name for the trigger.
	 *
	 * @since TBD
	 *
	 * @return string The queue name with prefix added.
	 */
	public function get_queue_name() {
		return static::$queue_prefix . static::$queue_name;
	}

	/**
	 * Get the meta field name saved when an item is added to the queue.
	 *
	 * @since TBD
	 *
	 * @return string The meta field name with prefix added.
	 */
	public function get_meta_field_name() {
		return static::$queue_prefix . static::$added_to_queue_meta_field;
	}

	/**
	 *
	 *
	 * @since TBD
	 *
	 * @return mixed
	 */
	public function get_queue() {
		return get_transient( $this->get_queue_name() );
	}

	/**
	 * Set the queue transient.
	 *
	 * @since TBD
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
	 * @since TBD
	 *
	 * @param integer $post_id A WordPress custom post id.
	 * @param WP_Post $post    A WordPress custom post object.
	 *
	 * @return boolean Whether the hooked action is valid for this trigger.
	 */
	abstract protected function validate_for_trigger( $post_id, $post );

	/**
	 * Add a custom post id to a trigger queue.
	 *
	 * @since TBD
	 *
	 * @param integer $post_id A WordPress custom post id.
	 * @param WP_Post $post    A WordPress custom post object.
	 * @param boolean $update  Whether this is an update to a custom post or new. Unreliable and not used.
	 */
	public function add_to_queue( $post_id, $post, $update ) {
		$process = $this->validate_for_trigger( $post_id, $post );

		if ( ! $process ) {
			return;
		}

		$current_queue = $this->get_queue();

		if ( empty( $current_queue ) ) {
			$this->set_queue( [ $post_id ] );

			return;
		}

		$current_queue[] = $post_id;
		$this->set_queue( $current_queue );
	}
}
