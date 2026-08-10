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
use TEC\Common\Integrations\Harbor\PUE_Resolver;
use TEC\Common\StellarWP\Uplink\API\V3\Auth\Contracts\Auth_Url;
use TEC\Common\Integrations\Uplink\Auth_URL_Decorator;
use Tribe__Dependency as Dependency;
use Tribe__Main as Common;
use function TEC\Common\StellarWP\Uplink\get_plugins;
use function lw_harbor_has_unified_license_key;
use function lw_harbor_get_unified_license_key;
use function lw_harbor_is_feature_enabled;
use function lw_harbor_is_feature_available;
use function lw_harbor_is_capability_license_active;

/**
 * Controller for setting up the Harbor library.
 *
 * @since 6.11.0
 *
 * @package TEC\Common\Libraries\Harbor
 */
class Harbor extends Controller_Contract {
	/**
	 * Prefix for Liquid Web unified license keys.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public const UNIFIED_LICENSE_KEY_PREFIX = 'LWSW-';

	/**
	 * The TEC product slug to Harbor product slug map.
	 *
	 * @since 6.11.0
	 *
	 * @var array<string, string>
	 */
	private const TEC_PRODUCT_SLUG_TO_HARBOR_PRODUCT_SLUG_MAP = [
		'the-events-calendar'    => 'the-events-calendar',
		'events-calendar-pro'    => 'events-calendar-pro',
		'event-tickets'          => 'event-tickets',
		'event-tickets-plus'     => 'event-tickets-plus',
		'tribe-filterbar'        => 'tribe-filterbar',
		'events-community'       => 'events-community',
		'tribe-eventbrite'       => 'tribe-eventbrite',
		'event-schedule-manager' => 'event-schedule-manager',
		'promoter'               => 'events-promoter',
		'tec-seating'            => 'seating',
		'event-aggregator'       => 'event-aggregator',
	];

	/**
	 * Register the controller.
	 *
	 * @since 6.11.0
	 */
	public function do_register(): void {
		if ( defined( 'WP_SANDBOX_SCRAPING' ) && WP_SANDBOX_SCRAPING ) {
			return;
		}

		if ( did_action( 'activate_plugin' ) ) {
			return;
		}

		$common = Common::instance();

		Config::set_container( $this->container );
		Config::set_plugin_basename( plugin_basename( $common->get_parent_plugin_file_path() ) );

		/**
		 * Allow plugins to hook in before Harbor is initialized.
		 *
		 * Useful for setting the licensing and portal base URLs
		 * to other than the default values.
		 *
		 * @since 6.11.0
		 */
		do_action( 'tec_common_harbor_pre_init' );

		add_filter( 'lw-harbor/legacy_licenses', [ $this,'register_legacy_licenses' ] );
		add_filter( 'lw_harbor/premium_plugin_exists', [ $this, 'register_premium_plugin_exists' ] );

		Harbor_Provider::init();

		// Uplink is being initialized in init with prio 8 - so we want to decorate it with our own decorator later.
		add_action( 'init', [ $this, 'decorate_uplinks_auth_url' ] );

		if ( ! did_action( 'lw_harbor/loaded' ) ) {
			return;
		}

		$this->container->register( PUE::class );
		$this->container->register( EventAggregator::class );
	}

	/**
	 * Unregister the controller.
	 *
	 * @since 6.11.0
	 *
	 * @return void
	 */
	public function unregister(): void {
		remove_filter( 'lw-harbor/legacy_licenses', [ $this,'register_legacy_licenses' ] );
		remove_filter( 'lw_harbor/premium_plugin_exists', [ $this, 'register_premium_plugin_exists' ] );
		remove_action( 'init', [ $this, 'decorate_uplinks_auth_url' ] );
	}

	/**
	 * Decorate the uplinks auth URL.
	 *
	 * @since 6.11.0
	 *
	 * @return void
	 */
	public function decorate_uplinks_auth_url(): void {
		$this->container->bind( Auth_Url::class, Auth_URL_Decorator::class );
	}

	/**
	 * Get the premium plugin existence callbacks.
	 *
	 * @since 6.11.2
	 *
	 * @param bool $exists Whether a premium plugin exists.
	 *
	 * @return bool
	 */
	public function register_premium_plugin_exists( bool $exists ): bool {
		if ( $exists ) {
			// It already exists.
			return true;
		}

		$premium_constants = [
			'EVENTS_CALENDAR_PRO_FILE',
			'EVENT_TICKETS_PLUS_FILE',
			'EVENTS_COMMUNITY_FILE',
			'EVENTBRITE_PLUGIN_FILE',
			'TRIBE_EVENTS_FILTERBAR_FILE',
		];

		foreach ( $premium_constants as $premium_constant ) {
			if ( ! defined( $premium_constant ) ) {
				continue;
			}

			return true;
		}

		return false;
	}

	/**
	 * Register the legacy licenses.
	 *
	 * @since 6.11.0
	 *
	 * @param array $licenses The licenses.
	 *
	 * @return array
	 */
	public function register_legacy_licenses( array $licenses ): array {
		// The imported get_plugins() is Uplink's, only declared once Uplink::init() requires its functions.php on `init`.
		// This filter can fire earlier (e.g. a Promoter PUE license lookup on `plugins_loaded`), so bail until it is loaded.
		if ( ! function_exists( '\TEC\Common\StellarWP\Uplink\get_plugins' ) ) {
			return $licenses;
		}

		$plugins = get_plugins();

		$filters_removed = false;

		$pue = tribe( PUE::class );

		if ( has_filter( 'pre_option', [ $pue, 'filter_pre_get_option' ] ) ) {
			remove_filter( 'pre_option', [ $pue, 'filter_pre_get_option' ], 10 );
			remove_filter( 'stellarwp/uplink/tec/license_get_key', [ $pue, 'filter_stellarwp_uplink_tec_license_get_key' ], 10 );
			$filters_removed = true;
		}

		$slugs_added = [];

		foreach ( $plugins as $plugin ) {
			$license_object = $plugin->get_license_object();
			$licenses[]     = [
				'key'        => $license_object->get_key(),
				'slug'       => $this->get_harbor_product_slug( $plugin->get_slug() ),
				'name'       => $plugin->get_name(),
				'product'    => 'the-events-calendar',
				'is_active'  => $license_object->is_valid(),
				'page_url'   => 'https://my.theeventscalendar.com/my-account/',
				'expires_at' => '',
			];

			$slugs_added[] = $plugin->get_slug();
		}

		/** @var Dependency $dependencies */
		$dependencies   = tribe( Dependency::class );
		$active_plugins = $dependencies->get_active_plugins();

		foreach ( array_keys( $active_plugins ) as $active_plugin_class ) {
			$pue_checker = tribe( PUE_Resolver::class )->get_pue_from_class( $active_plugin_class );

			$pue_plugin_slug = $pue_checker ? $pue_checker->get_slug() : '';

			if ( ! $pue_checker || in_array( $pue_plugin_slug, $slugs_added, true ) ) {
				continue;
			}

			$licenses[] = [
				'key'        => $pue_checker->get_key(),
				'slug'       => $this->get_harbor_product_slug( $pue_plugin_slug ),
				'name'       => $pue_checker->get_plugin_name(),
				'product'    => 'the-events-calendar',
				'is_active'  => method_exists( $pue_checker, 'is_key_valid' ) ? $pue_checker->is_key_valid() : false,
				'page_url'   => 'https://my.theeventscalendar.com/my-account/',
				'expires_at' => '',
			];

			$slugs_added[] = $pue_plugin_slug;
		}

		if ( $filters_removed ) {
			add_filter( 'pre_option', [ $pue, 'filter_pre_get_option' ], 10, 3 );
			add_filter( 'stellarwp/uplink/tec/license_get_key', [ $pue, 'filter_stellarwp_uplink_tec_license_get_key' ], 10, 2 );
		}

		return array_values(
			array_filter(
				$licenses,
				fn( array $license ): bool => ! empty( $license['key'] ) && ! $this->is_unified_license_key( $license['key'] )
			)
		);
	}

	/**
	 * Get the unified license key.
	 *
	 * @since 6.11.0
	 *
	 * @return string|null The unified license key, or null if no key is found.
	 */
	public function get_unified_license_key(): ?string {
		return lw_harbor_get_unified_license_key();
	}

	/**
	 * Check if the product is licensed.
	 *
	 * @since 6.11.0
	 *
	 * @param string $product The product slug.
	 *
	 * @return bool
	 */
	public function is_product_licensed( string $product ): bool {
		if ( ! lw_harbor_has_unified_license_key() ) {
			return false;
		}

		return lw_harbor_is_feature_available( $product );
	}

	/**
	 * Whether a license key uses the unified Liquid Web format.
	 *
	 * @since TBD
	 *
	 * @param string $key The license key.
	 *
	 * @return bool
	 */
	public function is_unified_license_key( string $key ): bool {
		return str_starts_with( trim( $key ), self::UNIFIED_LICENSE_KEY_PREFIX );
	}

	/**
	 * Whether a TEC product license field is managed by Harbor.
	 *
	 * When true, the unified license key should be shown read-only and should not
	 * be validated through legacy PUE per-product fields.
	 *
	 * Checks that the product's license is actually active/valid for this site, not
	 * merely that the customer's tier entitles them to it — a customer can be entitled
	 * to a product without having activated it here, in which case a legacy per-product
	 * key should still be accepted.
	 *
	 * @since TBD
	 *
	 * @param string $tec_product_slug The TEC product slug.
	 *
	 * @return bool
	 */
	public function is_license_field_managed_by_harbor( string $tec_product_slug ): bool {
		if ( ! lw_harbor_has_unified_license_key() ) {
			return false;
		}

		return lw_harbor_is_capability_license_active(
			$this->get_harbor_product_slug( $tec_product_slug )
		);
	}

	/**
	 * Error message shown when a unified license key is entered in a per-product field.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_unified_license_key_entry_error_message(): string {
		return sprintf(
			/* translators: %1$s: opening anchor tag, %2$s: closing anchor tag. */
			__( 'It seems to be a unified license key. Please %1$sclick here%2$s to enter it in the LW License Manager.', 'tribe-common' ),
			'<a href="' . esc_url( lw_harbor_get_license_page_url() ) . '" target=_blank>',
			'</a>'
		);
	}

	/**
	 * Get the unified license key if the feature is enabled.
	 *
	 * @since 6.11.0
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
	 * @since 6.11.0
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
	 * @since 6.11.0
	 *
	 * @param string $tec_product_slug The TEC product slug.
	 *
	 * @return string The Harbor product slug.
	 */
	public function get_harbor_product_slug( string $tec_product_slug ): string {
		if ( ! isset( self::TEC_PRODUCT_SLUG_TO_HARBOR_PRODUCT_SLUG_MAP[ $tec_product_slug ] ) ) {
			return $tec_product_slug;
		}

		return self::TEC_PRODUCT_SLUG_TO_HARBOR_PRODUCT_SLUG_MAP[ $tec_product_slug ];
	}

	/**
	 * Get the portal URL.
	 *
	 * @since 6.11.0
	 *
	 * @param string $path The path.
	 *
	 * @return string The portal URL.
	 */
	public function get_portal_url( string $path = '' ): string {
		return trailingslashit( trailingslashit( Config::get_portal_base_url() ) . ( $path ? ltrim( $path, '/' ) : '' ) );
	}
}
