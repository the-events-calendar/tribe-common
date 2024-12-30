<?php
/**
 * Zapier Abstract Trigger Queue.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 * @package TEC\Event_Automator\Zapier\Triggers;
 */

namespace TEC\Event_Automator\Zapier\Trigger_Queue;

use TEC\Event_Automator\Integrations\Trigger_Queue\Integration_Trigger_Queue;

/**
 * Class Abstract_Trigger_Queue
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 * @since 6.0.0 Migrated to Common from Event Automator - Utilize Integration_Trigger_Queue to share coding among integrations.
 *
 * @package TEC\Event_Automator\Zapier\Trigger_Queue
 */
abstract class Abstract_Trigger_Queue extends Integration_Trigger_Queue {

	/**
	 * @inheritDoc
	 */
	protected static $api_id = 'zapier';

	/**
	 * @inheritDoc
	 */
	protected static $queue_prefix = '_tec_zapier_queue_';
}
