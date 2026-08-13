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
use TEC\Common\StellarWP\Uplink\API\Validation_Response;
use TEC\Common\StellarWP\Uplink\API\V3\Auth\Contracts\Auth_Url;
use TEC\Common\StellarWP\Uplink\Resources\Plugin as Uplink_Plugin;
use TEC\Common\StellarWP\Uplink\Resources\Resource as Uplink_Resource;
use TEC\Common\Integrations\Uplink\Auth_URL_Decorator;
use Tribe__Dependency as Dependency;
use Tribe__Main as Common;
use function TEC\Common\StellarWP\Uplink\get_plugins;
use function TEC\Common\StellarWP\Uplink\get_resource;
use function lw_harbor_has_unified_license_key;
use function lw_harbor_get_unified_license_key;
use function lw_harbor_is_feature_enabled;
use function lw_harbor_is_feature_available;

/**
 * Controller for setting up the Harbor library.
 *
 * Unified license keys (LWSW-) are handled on two independent validation pipelines.
 * This controller is always registered, even when Harbor does not fully load
 * (no premium plugin). When Harbor is loaded, Integrations\Harbor\PUE takes over
 * the PUE pipeline; this class still owns the Uplink pipeline.
 *
 * 1. PUE - Tribe__PUE__Checker::validate_key()
 *    Products such as Events Calendar Pro, Filter Bar, Promoter, Community.
 *    AJAX action: pue-validate-key_{slug}.
 *    Filter: tec_common_pue_pre_validate_key (before the remote request).
 *    This class only handles the Harbor-not-loaded case.
 *
 * 2. Uplink - StellarWP Uplink Client::validate_license()
 *    Products: Seating, Event Tickets Plus.
 *    AJAX action: pue-validate-key-uplink-tec.
 *    Filter: stellarwp/uplink/tec/client_validate_license (after the remote request).
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
	private const UNIFIED_LICENSE_KEY_PREFIX = 'LWSW-';

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

		// PUE pipeline: Tribe__PUE__Checker. Runs even when Harbor does not fully load
		// so a unified key pasted into a free-plugin PUE field still gets a guidance message.
		// When Harbor is loaded this callback bails; Integrations\Harbor\PUE owns the filter then.
		add_filter( 'tec_common_pue_pre_validate_key', [ $this, 'filter_tec_common_pue_pre_validate_key' ], 10, 3 );

		// Uplink pipeline: Client::validate_license() for Seating / Event Tickets Plus.
		// Those products never enter Tribe__PUE__Checker. This class always owns this filter.
		add_filter( 'stellarwp/uplink/tec/client_validate_license', [ $this, 'filter_stellarwp_uplink_tec_client_validate_license' ], 10, 2 );

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
		remove_filter( 'tec_common_pue_pre_validate_key', [ $this, 'filter_tec_common_pue_pre_validate_key' ] );
		remove_filter( 'stellarwp/uplink/tec/client_validate_license', [ $this, 'filter_stellarwp_uplink_tec_client_validate_license' ] );
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
	 * PUE pipeline: reject unified license keys when Harbor is not loaded.
	 *
	 * Hook: `tec_common_pue_pre_validate_key` (before Tribe__PUE__Checker hits the remote API).
	 *
	 * Without a premium plugin, Harbor never fires `lw_harbor/loaded` and
	 * Integrations\Harbor\PUE is not registered — so this callback only
	 * prevents a unified key from being sent through legacy PUE validation.
	 *
	 * @since TBD
	 *
	 * @param array|null                $response Early response, or null to continue.
	 * @param string                    $key      The license key being validated.
	 * @param \Tribe__PUE__Checker|null $checker  The PUE checker instance.
	 *
	 * @return array|null
	 */
	public function filter_tec_common_pue_pre_validate_key( ?array $response, string $key, $checker ): ?array {
		if ( null !== $response ) {
			return $response;
		}

		// Bail out if harbor is loaded.
		if ( did_action( 'lw_harbor/loaded' ) ) {
			return $response;
		}

		if ( ! $this->is_unified_license_key( $key ) ) {
			return $response;
		}

		return [
			'status'  => 0,
			'message' => $this->get_unified_license_key_requires_premium_message(),
		];
	}

	/**
	 * Uplink pipeline: handle unified license keys validated through StellarWP Uplink.
	 *
	 * Hook: `stellarwp/uplink/tec/client_validate_license` (after Client::validate_license()).
	 *
	 * Seating and Event Tickets Plus use `pue-validate-key-uplink-tec` and never
	 * enter Tribe__PUE__Checker::validate_key().
	 *
	 * Only the Licenses UI AJAX is rewritten. Uplink also uses this method for
	 * plugin update checks, which must keep the catalog/Herald payload.
	 *
	 * @since TBD
	 *
	 * @param Validation_Response $results License validation results.
	 * @param array               $args    License validation arguments.
	 *
	 * @return Validation_Response
	 */
	public function filter_stellarwp_uplink_tec_client_validate_license( $results, array $args ) {
		if ( ! $results instanceof Validation_Response ) {
			return $results;
		}

		// Uplink also calls validate_license() during plugin update checks.
		// Only rewrite the Licenses UI AJAX so catalog/Herald update payloads stay intact.
		if ( ! $this->is_uplink_license_field_validation_request() ) {
			return $results;
		}

		$plugin = $args['plugin'] ?? '';
		if ( ! is_string( $plugin ) || '' === $plugin ) {
			return $results;
		}

		$key = $args['key'] ?? '';
		$key = is_string( $key ) ? $key : '';

		if ( $this->is_license_field_managed_by_harbor( $plugin ) ) {
			return $this->make_uplink_validation_response(
				$key,
				$plugin,
				true,
				$this->get_harbor_managed_license_message()
			);
		}

		if ( ! $this->is_unified_license_key( $key ) ) {
			return $results;
		}

		$message = did_action( 'lw_harbor/loaded' )
			? $this->get_unified_license_key_entry_error_message()
			: $this->get_unified_license_key_requires_premium_message();

		return $this->make_uplink_validation_response( $key, $plugin, false, $message );
	}

	/**
	 * Uplink pipeline: whether the current request is license-field AJAX validation.
	 *
	 * Distinguishes Licenses UI (`pue-validate-key-uplink-*`) from plugin update checks,
	 * which share Client::validate_license() and must not be rewritten.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	private function is_uplink_license_field_validation_request(): bool {
		$action = isset( $_REQUEST['action'] ) ? sanitize_key( wp_unslash( $_REQUEST['action'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		return str_starts_with( $action, 'pue-validate-key-uplink-' );
	}

	/**
	 * Uplink pipeline: build a synthetic validation response for the Licenses UI.
	 *
	 * Uses the real Uplink resource when it is registered so a valid result is
	 * not persisted as a "new" per-product key. Falls back to a stub resource
	 * for message rendering when the collection is unavailable.
	 *
	 * @since TBD
	 *
	 * @param string $key      License key being validated.
	 * @param string $plugin   Uplink plugin slug.
	 * @param bool   $is_valid Whether the synthetic result is valid.
	 * @param string $message  Message shown in the license field (may include HTML).
	 *
	 * @return Validation_Response
	 */
	private function make_uplink_validation_response( string $key, string $plugin, bool $is_valid, string $message ): Validation_Response {
		$resource = $this->get_uplink_resource_for_validation( $plugin );

		if ( $is_valid ) {
			$stored_key = $resource->get_license_key();
			if ( is_string( $stored_key ) && '' !== $stored_key ) {
				$key = $stored_key;
			}
		}

		$payload = [
			'plugin' => $plugin,
			'slug'   => $plugin,
		];

		if ( $is_valid ) {
			$payload['api_message'] = $message;
		} else {
			$payload['api_invalid']                = 1;
			$payload['api_inline_invalid_message'] = $message;
		}

		return new Validation_Response( $key, 'local', (object) $payload, $resource );
	}

	/**
	 * Uplink pipeline: get the resource used to render a synthetic validation message.
	 *
	 * @since TBD
	 *
	 * @param string $plugin Uplink plugin slug.
	 *
	 * @return Uplink_Resource
	 */
	private function get_uplink_resource_for_validation( string $plugin ): Uplink_Resource {
		try {
			if ( function_exists( '\TEC\Common\StellarWP\Uplink\get_resource' ) ) {
				$resource = get_resource( $plugin );
				if ( $resource instanceof Uplink_Resource ) {
					return $resource;
				}
			}
		} catch ( \Throwable $exception ) {
			// Uplink collection is not ready; a stub is enough for message rendering.
			unset( $exception );
		}

		return new Uplink_Plugin(
			$plugin,
			$plugin,
			'0.0.0',
			$plugin . '.php',
			\stdClass::class
		);
	}

	/**
	 * Guidance shown when a unified key is entered but Harbor cannot load yet.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_unified_license_key_requires_premium_message(): string {
		return sprintf(
			/* translators: %s: My account page link. */
			__(
				'This is a unified license key. To activate it, install The Events Calendar Pro or Event Tickets Plus, then add your license in the Unified License Manager. You can download the plugin from <a href="%s" target="_blank">your account</a>.',
				'tribe-common'
			),
			esc_url( $this->get_portal_url() )
		);
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

		return lw_harbor_is_feature_enabled(
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
			__( 'This is a unified license key. Please %1$sclick here%2$s to enter it in the Unified License Manager.', 'tribe-common' ),
			'<a href="' . esc_url( lw_harbor_get_license_page_url() ) . '" target=_blank>',
			'</a>'
		);
	}

	/**
	 * Success message shown on Harbor-managed per-product license fields.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_harbor_managed_license_message(): string {
		return sprintf(
			/* translators: URL to the Liquid Web License Manager */
			__( 'Licensed via <a href="%s" target="_blank">Unified License Manager</a>', 'tribe-common' ),
			esc_url( lw_harbor_get_license_page_url() )
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
