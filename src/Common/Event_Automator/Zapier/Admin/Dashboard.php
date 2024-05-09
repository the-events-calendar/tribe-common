<?php
/**
 * Class to manage Zapier Endpoint Dashboard.
 *
 * @since   1.4.0
 *
 * @package TEC\Common\Event_Automator\Zapier\Admin
 */

namespace TEC\Common\Event_Automator\Zapier\Admin;

use TEC\Common\Event_Automator\Integrations\Admin\Abstract_Dashboard;
use TEC\Common\Event_Automator\Zapier\Template_Modifications;
use TEC\Common\Event_Automator\Zapier\Url;

/**
 * Class Dashboard
 *
 * @since   1.4.0
 * @since   1.4.0 - Refactored to use Abstract_Dashboard.
 *
 * @package TEC\Common\Event_Automator\Zapier\Admin
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
	 * @since 1.4.0
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
