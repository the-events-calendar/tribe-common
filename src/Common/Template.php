<?php
/**
 * TEC Common Template - Can be used with ALL Common Views.
 *
 * @since 6.5.4
 *
 * @package TEC\Common;
 */

namespace TEC\Common;

use Tribe__Template as Base_Template;
use Tribe__Main as Common_Plugin;

/**
 * Class Template
 *
 * @since 6.5.4
 */
class Template extends Base_Template {

	/**
	 * Template constructor.
	 */
	public function __construct() {
		$this->set_template_origin( Common_Plugin::instance() );
		$this->set_template_folder( 'src/views' );
		$this->set_template_context_extract( true );
		$this->set_template_folder_lookup( true );
	}
}
