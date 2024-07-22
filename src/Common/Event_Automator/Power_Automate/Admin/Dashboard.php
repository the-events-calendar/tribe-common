<?php
/**
 * Class to manage Power Automate Endpoint Dashboard.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Power_Automate
 */

namespace TEC\Event_Automator\Power_Automate\Admin;

use TEC\Event_Automator\Integrations\Admin\Abstract_Dashboard;
use TEC\Event_Automator\Power_Automate\Template_Modifications;
use TEC\Event_Automator\Power_Automate\Url;

/**
 * Class Dashboard
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Power_Automate
 */
class Dashboard extends Abstract_Dashboard {

	/**
	 * @inheritDoc
	 */
	public static $option_prefix = 'tec_power_automate_endpoints_';

	/**
	 * @inheritDoc
	 */
	public static $api_id = 'power-automate';

	/**
	 * Dashboard constructor.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param Endpoints_Manager      $manager                An instance of the Endpoints_Manager handler.
	 * @param Template_Modifications $template_modifications An instance of the Template_Modifications handler.
	 * @param Url                    $url                    An instance of the URL handler.
	 */
	public function __construct( Endpoints_Manager $manager, Template_Modifications $template_modifications, Url $url ) {
		$this->manager                = $manager;
		$this->template_modifications = $template_modifications;
		$this->url                    = $url;
	}
}
