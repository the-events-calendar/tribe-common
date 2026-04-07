<?php
/**
 * The Controller to set up the Harbor library.
 */

namespace TEC\Common\Libraries;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;
use TEC\Common\LiquidWeb\Harbor\Config;
use TEC\Common\LiquidWeb\Harbor\Harbor as Harbor_Provider;
use function TEC\Common\StellarWP\Uplink\get_plugins;

/**
 * Controller for setting up the Harbor library.
 *
 * @since TBD
 *
 * @package TEC\Common\Libraries\Harbor
 */
class Harbor extends Controller_Contract {
	/**
	 * Register the controller.
	 *
	 * @since TBD
	 */
	public function do_register(): void {
		Config::set_container( $this->container );
		// Config::set_api_base_url( 'https://licensing-dev.stellarwp.com' );
		Harbor_Provider::init();

		add_filter( 'lw-harbor/legacy_licenses', [ $this,'register_legacy_licenses' ] );
	}

	/**
	 * Unregister the controller.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function unregister(): void {
		remove_filter( 'lw-harbor/legacy_licenses', [ $this,'register_legacy_licenses' ] );
	}

	/**
	 * Register the legacy licenses.
	 *
	 * @since TBD
	 *
	 * @param array $licenses The licenses.
	 *
	 * @return array
	 */
	public function register_legacy_licenses( array $licenses ): array {
		$plugins = get_plugins();

		foreach ( $plugins as $plugin ) {
			$license_object = $plugin->get_license_object();
			$licenses[] = [
				'key'        => $license_object->get_key(),
				'slug'       => $plugin->get_slug(),
				'name'       => $plugin->get_name(),
				'product'    => 'the-events-calendar',
				'is_active'  => $license_object->is_valid(),
				'page_url'   => 'https://my.theeventscalendar.com/my-account/',
				'expires_at' => '',
			];
		}

		return $licenses;
	}
}
