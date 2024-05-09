<?php
/**
 * Zapier Abstract Trigger Queue.
 *
 * @since 1.0.0
 * @package TEC\Common\Event_Automator\Zapier\Triggers;
 */

namespace TEC\Common\Event_Automator\Zapier\Trigger_Queue;

use TEC\Common\Event_Automator\Integrations\Trigger_Queue\Integration_Trigger_Queue;

/**
 * Class Abstract_Trigger_Queue
 *
 * @since 1.0.0
 * @since 1.4.0 - Utilize Integration_Trigger_Queue to share coding among integrations.
 *
 * @package TEC\Common\Event_Automator\Zapier\Trigger_Queue
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
