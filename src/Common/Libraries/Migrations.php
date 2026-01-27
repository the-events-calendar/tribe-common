<?php
/**
 * The Controller to set up the Migrations library.
 */

namespace TEC\Common\Libraries;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;
use TEC\Common\Libraries\Provider as Libraries_Provider;
use TEC\Common\StellarWP\Migrations\Provider as Migrations_Provider;
use TEC\Common\StellarWP\Migrations\Config;
use Tribe__Main as Common;
use Tribe__Template as Template;

/**
 * Controller for setting up the Migrations library.
 *
 * @since TBD
 *
 * @package TEC\Common\Libraries\Migrations
 */
class Migrations extends Controller_Contract {
	/**
	 * Register the controller.
	 *
	 * @since TBD
	 */
	public function do_register(): void {
		$hook_prefix = tribe( Libraries_Provider::class )->get_hook_prefix();

		$common = Common::instance();
		$template = new Template();

		$template->set_template_origin( $common );
		$template->set_template_folder( 'vendor/vendor-prefixed/stellarwp/migrations/src/views' );
		$template->set_template_folder_lookup();
		$template->set_template_context_extract( true );

		Config::set_container( $this->container );
		Config::set_hook_prefix( $hook_prefix );
		Config::set_template_engine( $template );
		Config::set_assets_url( $common->plugin_url . 'vendor/vendor-prefixed/stellarwp/migrations/assets/' );

		$this->container->register( Migrations_Provider::class );
	}

	/**
	 * Unregister the controller.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function unregister(): void {
		// Nothing to do here.
	}
}
