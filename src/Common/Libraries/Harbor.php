<?php
/**
 * The Controller to set up the Harbor library.
 */

namespace TEC\Common\Libraries;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;
use TEC\Common\LiquidWeb\Harbor\Config;
use TEC\Common\LiquidWeb\Harbor\Harbor as Harbor_Provider;
use TEC\Common\Integrations\Harbor\EA;
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

		/**
		 * Allow plugins to hook in before Harbor is initialized.
		 *
		 * Useful for setting the licensing and portal base URLs
		 * to other than the default values.
		 *
		 * @since TBD
		 */
		do_action( 'tec_common_harbor_pre_init' );

		Harbor_Provider::init();

		add_filter( 'lw-harbor/legacy_licenses', [ $this,'register_legacy_licenses' ] );

		$this->container->register( EA::class );
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

	/**
	 * Get the unified license key.
	 *
	 * @since TBD
	 *
	 * @return string|null The unified license key, or null if no key is found.
	 */
	public function get_unified_license_key(): ?string {
		if ( ! lw_harbor_has_unified_license_key() ) {
			return null;
		}

		return lw_harbor_get_unified_license_key();
	}

	public function get_activated_unified_license_tier(): ?string {
		if ( ! lw_harbor_has_unified_license_key() ) {
			return null;
		}

		return lw_harbor_get_unified_license_tier();
	}

	/**
	 * Get the unified license key if the feature is enabled.
	 *
	 * @since TBD
	 *
	 * @param string $feature The feature slug.
	 *
	 * @return string|null The unified license key, or null if no key is found.
	 */
	public function get_unified_license_key_if_feature_enabled( string $feature ): ?string {
		$key = $this->get_unified_license_key();
		if ( ! $key ) {
			return null;
		}

		if ( ! lw_harbor_is_feature_enabled( $feature ) ) {
			return null;
		}

		return $key;
	}

	/**
	 * Get the unified license key if the feature is available.
	 *
	 * @since TBD
	 *
	 * @param string $feature The feature slug.
	 *
	 * @return string|null The unified license key, or null if no key is found.
	 */
	public function get_unified_license_key_if_feature_available( string $feature ): ?string {
		$key = $this->get_unified_license_key();
		if ( ! $key ) {
			return null;
		}

		if ( ! lw_harbor_is_feature_available( $feature ) ) {
			return null;
		}

		return $key;
	}
}
