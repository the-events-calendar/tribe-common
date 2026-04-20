<?php
/**
 * The Controller to set up the Harbor library.
 */

namespace TEC\Common\Libraries;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;
use TEC\Common\LiquidWeb\Harbor\Config;
use TEC\Common\LiquidWeb\Harbor\Harbor as Harbor_Provider;
use TEC\Common\Integrations\Harbor\EventAggregator;
use TEC\Common\Integrations\Harbor\PUE;
use InvalidArgumentException;
use function TEC\Common\StellarWP\Uplink\get_plugins;
use function lw_harbor_has_unified_license_key;
use function lw_harbor_get_unified_license_key;
use function lw_harbor_is_feature_enabled;
use function lw_harbor_is_feature_available;

/**
 * Controller for setting up the Harbor library.
 *
 * @since TBD
 *
 * @package TEC\Common\Libraries\Harbor
 */
class Harbor extends Controller_Contract {
	private const TEC_PRODUCT_SLUG_TO_HARBOR_PRODUCT_SLUG_MAP = [
		'the-events-calendar'    => 'the-events-calendar',
		'events-calendar-pro'    => 'events-calendar-pro',
		'event-tickets'          => 'event-tickets',
		'event-tickets-plus'     => 'event-tickets-plus',
		'tribe-filterbar'        => 'tribe-filterbar',
		'events-community'       => 'events-community',
		'tribe-eventbrite'       => 'tribe-eventbriter',
		'event-schedule-manager' => 'event-schedule-manager',
		'promoter'               => 'events-promoter',
		'tec-seating'            => 'assigned-seating',
		'event-aggregator'       => 'event-aggregator',
	];

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

		$this->container->register( PUE::class );
		$this->container->register( EventAggregator::class );
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

	/**
	 * Check if the product is licensed.
	 *
	 * @since TBD
	 *
	 * @param string $product The product slug.
	 *
	 * @return bool
	 */
	public function is_product_licensed( string $product ): bool {
		if ( ! lw_harbor_has_unified_license_key() ) {
			return false;
		}

		return lw_harbor_is_feature_enabled( $product );
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

	/**
	 * Get the Harbor product slug for a TEC product slug.
	 *
	 * @since TBD
	 *
	 * @param string $tec_product_slug The TEC product slug.
	 *
	 * @return string The Harbor product slug.
	 *
	 * @throws InvalidArgumentException If the TEC product slug is invalid.
	 */
	public function get_harbor_product_slug( string $tec_product_slug ): ?string {
		if ( ! isset( self::TEC_PRODUCT_SLUG_TO_HARBOR_PRODUCT_SLUG_MAP[$tec_product_slug] ) ) {
			throw new InvalidArgumentException( sprintf( 'Invalid TEC product slug: %s', $tec_product_slug ) );
		}

		return self::TEC_PRODUCT_SLUG_TO_HARBOR_PRODUCT_SLUG_MAP[$tec_product_slug];
	}
}
