<?php
/**
 * Admin Template.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Templates
 */

namespace TEC\Event_Automator\Templates;

use TEC\Event_Automator\Plugin;
use Tribe__Main;
use Tribe__Template;

/**
 * Class Admin_Template
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Templates
 */
class Admin_Template extends Tribe__Template {

	/**
	 * Template constructor.
	 *
	 * Sets the correct paths for templates for event status.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 */
	public function __construct() {
		$this->set_template_origin( tribe( Plugin::class ) );
		$this->set_template_folder( 'src/admin-views' );

		// We specifically don't want to look up template files here.
		$this->set_template_folder_lookup( false );

		// Configures this templating class extract variables.
		$this->set_template_context_extract( true );
	}
}
