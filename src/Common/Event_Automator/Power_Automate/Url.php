<?php
/**
 * Manages the Power Automate URLs for the plugin.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Power_Automate
 */

namespace TEC\Event_Automator\Power_Automate;

use TEC\Event_Automator\Integrations\Connections\Integration_Url;

/**
 * Class Url
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Power_Automate
 */
class Url extends Integration_Url {

	/**
	 * @inerhitDoc
	 */
	public static string $api_id = 'power-automate';

	/**
	 * Url constructor.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param Actions $actions An instance of the Power_Automate Actions handler.
	 */
	public function __construct( Actions $actions ) {
		$this->actions = $actions;
	}
}
