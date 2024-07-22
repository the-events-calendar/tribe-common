<?php
/**
 * Class to manage Power Automate settings.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Power_Automate
 */

namespace TEC\Event_Automator\Power_Automate;

use TEC\Event_Automator\Integrations\Connections\Integration_Settings;

/**
 * Class Settings
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Power_Automate
 */
class Settings extends Integration_Settings {

	/**
	 * @inehritDoc
	 */
	public static string $option_prefix = 'tec_power_automate_';

	/**
	 * @inehritDoc
	 */
	public static string $api_id = 'power-automate';

	/**
	 * Settings constructor.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param Api                    $api                    An instance of the API handler.
	 * @param Template_Modifications $template_modifications An instance of the Template_Modifications handler.
	 * @param Url                    $url                    An instance of the URL handler.
	 */
	public function __construct( Api $api, Template_Modifications $template_modifications, Url $url ) {
		$this->api                    = $api;
		$this->template_modifications = $template_modifications;
		$this->url                    = $url;
	}
}
