<?php
/**
 * Power Automate Abstract Trigger Queue.
 *
 * @since 1.4.0
 * @package TEC\Common\Event_Automator\Power_Automate\Triggers;
 */

namespace TEC\Common\Event_Automator\Power_Automate\Trigger_Queue;

use TEC\Common\Event_Automator\Integrations\Trigger_Queue\Integration_Trigger_Queue;

/**
 * Class Abstract_Trigger_Queue
 *
 * @since 1.4.0
 *
 * @package TEC\Common\Event_Automator\Power_Automate\Trigger_Queue
 */
abstract class Abstract_Trigger_Queue extends Integration_Trigger_Queue {

	/**
	 * @inheritDoc
	 */
	protected static $api_id = 'power-automate';

	/**
	 * @inheritDoc
	 */
	protected static $queue_prefix = '_tec_power_automate_queue_';
}
