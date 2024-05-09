<?php
/**
 * Manages the Power Automate URLs for the plugin.
 *
 * @since 1.4.0
 *
 * @package TEC\Common\Event_Automator\Power_Automate
 */

namespace TEC\Common\Event_Automator\Power_Automate;

use TEC\Common\Event_Automator\Integrations\Connections\Integration_Url;

/**
 * Class Url
 *
 * @since 1.4.0
 *
 * @package TEC\Common\Event_Automator\Power_Automate
 */
class Url extends Integration_Url {

	/**
	 * @inerhitDoc
	 */
	public static string $api_id = 'power-automate';

	/**
	 * Url constructor.
	 *
	 * @since 1.4.0
	 *
	 * @param Actions $actions An instance of the Power_Automate Actions handler.
	 */
	public function __construct( Actions $actions ) {
		$this->actions = $actions;
	}
}
