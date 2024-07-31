<?php
/**
 * Class to manage Zapier Endpoint Dashboard.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Zapier\Admin
 */

namespace TEC\Event_Automator\Zapier\Admin;

use TEC\Event_Automator\Integrations\Admin\Abstract_Dashboard;
use TEC\Event_Automator\Zapier\Template_Modifications;
use TEC\Event_Automator\Zapier\Url;

/**
 * Class Dashboard
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 * @since 6.0.0 Migrated to Common from Event Automator - Refactored to use Abstract_Dashboard.
 *
 * @package TEC\Event_Automator\Zapier\Admin
 */
class Dashboard extends Abstract_Dashboard {

	/**
	 * @inerhitDoc
	 */
	public static $option_prefix = 'tec_zapier_endpoints_';

	/**
	 * @inerhitDoc
	 */
	public static $api_id = 'zapier';

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
