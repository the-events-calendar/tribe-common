<?php
/**
 * Provider for the Help Hub.
 *
 * @since   TBD
 * @package TEC\Common\Help_Hub
 */

namespace TEC\Common\Help_Hub;

use TEC\Common\Configuration\Configuration;
use TEC\Common\Contracts\Service_Provider;

/**
 * Class Provider
 *
 * Registers the Help Hub logic and dependencies.
 *
 * @since   TBD
 *
 * @package TEC\Common\Help_Hub
 */
class Provider extends Service_Provider {

	/**
	 * @var Hub
	 */
	protected $hub;

	/**
	 * @var Configuration
	 */
	protected $config;

	/**
	 * Provider constructor.
	 *
	 * @since TBD
	 */
	public function __construct() {
		// Resolve dependencies via the container.
		$this->hub    = tribe( Hub::class );
		$this->config = tribe( Configuration::class );
	}

	/**
	 * Register the functionality related to this module.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function register(): void {
		// Register the Hub as a singleton in the DI container.
		tribe()->singleton(
			Hub::class,
			function () {
				return new Hub();
			}
		);

		// Register hooks and filters.
		$this->register_hooks();
	}

	/**
	 * Register the hooks and filters needed for the Help Hub.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	protected function register_hooks(): void {
		add_action( 'admin_init', [ $this->hub, 'generate_iframe_content' ] );
		add_action( 'admin_enqueue_scripts', [ $this->hub, 'load_assets' ], 1 );
		add_filter( 'admin_body_class', [ $this->hub, 'add_help_page_body_class' ] );
	}
}
