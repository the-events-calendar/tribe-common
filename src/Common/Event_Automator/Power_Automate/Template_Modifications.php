<?php
/**
 * Power Automate template modifications class.
 *
 * @since 1.4.0
 *
 * @package TEC\Common\Event_Automator\Power_Automate
 */

namespace TEC\Common\Event_Automator\Power_Automate;

use TEC\Common\Event_Automator\Integrations\Connections\Integration_Template_Modifications;
use TEC\Common\Event_Automator\Templates\Admin_Template;

/**
 * Class Template_Modifications
 *
 * @since 1.4.0
 *
 * @package TEC\Common\Event_Automator\Power_Automate
 */
class Template_Modifications extends Integration_Template_Modifications {

	/**
	 * @inerhitDoc
	 */
	public static string $api_id = 'power-automate';

	/**
	 * Template_Modifications constructor.
	 *
	 * @since 1.4.0
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
