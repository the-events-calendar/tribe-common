<?php
/**
 * Power Automate template modifications class.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Power_Automate
 */

namespace TEC\Event_Automator\Power_Automate;

use TEC\Event_Automator\Integrations\Connections\Integration_Template_Modifications;
use TEC\Event_Automator\Templates\Admin_Template;

/**
 * Class Template_Modifications
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Power_Automate
 */
class Template_Modifications extends Integration_Template_Modifications {

	/**
	 * @inerhitDoc
	 */
	public static string $api_id = 'power-automate';

	/**
	 * Template_Modifications constructor.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param Admin_Template $template An instance of the backend template handler.
	 * @param Url            $Url      An instance of the URl handler.
	 */
	public function __construct( Admin_Template $admin_template, Url $url ) {
		$this->admin_template = $admin_template;
		$this->url            = $url;
		self::$option_prefix  = Settings::$option_prefix;
	}
}
