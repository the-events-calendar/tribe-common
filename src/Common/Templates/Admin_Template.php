<?php
/**
 * Common Admin Template.
 *
 * @since   TBD
 *
 * @package TEC\Common\Templates
 */

namespace TEC\Common\Templates;

use Tribe__Main;
use Tribe__Template;

/**
 * Class Admin_Template
 *
 * @since   TBD
 *
 * @package TEC\Common\Templates
 */
class Admin_Template extends Tribe__Template {

	/**
	 * Template constructor.
	 *
	 * Sets the correct paths for templates for event status.
	 *
	 * @since TBD
	 */
	public function __construct() {
		$this->set_template_origin( Tribe__Main::instance() );
		$this->set_template_folder( 'src/admin-views' );

		// We specifically don't want to look up template files here.
		$this->set_template_folder_lookup( false );

		// Configures this templating class extract variables.
		$this->set_template_context_extract( true );
	}
}
