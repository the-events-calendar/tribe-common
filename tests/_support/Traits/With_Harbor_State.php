<?php

namespace Tribe\Tests\Traits;

use TEC\Common\LiquidWeb\Harbor\Config;
use TEC\Common\LiquidWeb\Harbor\Features\Feature_Repository;
use TEC\Common\LiquidWeb\Harbor\Licensing\Product_Collection;
use TEC\Common\LiquidWeb\Harbor\Licensing\Repositories\License_Repository;
use TEC\Common\LiquidWeb\Harbor\Licensing\Results\Product_Entry;
use TEC\Common\LiquidWeb\Harbor\Portal\Catalog_Collection;
use TEC\Common\LiquidWeb\Harbor\Portal\Catalog_Repository;
use TEC\Common\LiquidWeb\Harbor\Portal\Results\Catalog_Feature;
use TEC\Common\LiquidWeb\Harbor\Portal\Results\Product_Catalog;
use TEC\Common\LiquidWeb\Harbor\Portal\Results\Tier_Collection;

/**
 * Seeds the Harbor unified-licensing subsystem into known states for
 * integration tests.
 *
 * Every seed method writes through Harbor's real repositories (which are
 * option-backed) so the lw_harbor_* global functions — and by extension
 * every TEC hook that depends on them — read their answers from the same
 * storage a real site would. No mocking of Harbor internals.
 *
 * The @after teardown restores the option table so tests are isolated.
 */
trait With_Harbor_State {

	/**
	 * Features registered as TYPE_SERVICE so is_enabled() == is_available()
	 * (Plugin_Strategy needs a real active plugin; Service_Strategy doesn't).
	 *
	 * @var array<string, true>
	 */
	private array $harbor_seeded_service_features = [];

	/**
	 * @after
	 */
	public function tear_down_harbor_state(): void {
		delete_option( License_Repository::KEY_OPTION_NAME );
		delete_option( License_Repository::PRODUCTS_STATE_OPTION_NAME );
		delete_option( License_Repository::PRODUCTS_LAST_ACTIVE_DATES_OPTION_NAME );
		delete_option( Catalog_Repository::CATALOG_STATE_OPTION_NAME );

		// Drop any in-memory Harbor caches that outlive the option reset.
		$container = Config::get_container();
		$container->get( Catalog_Repository::class )->delete_catalog();
		$container->get( Feature_Repository::class )->refresh();

		$this->harbor_seeded_service_features = [];
	}

	/**
	 * Store a unified license key as if the customer had entered it.
	 */
	protected function seed_unified_license_key( string $key = 'LWSW-TEST-UNIFIED-KEY' ): string {
		Config::get_container()->get( License_Repository::class )->store_key( $key );

		return $key;
	}

	/**
	 * Seed the Harbor catalog + product-license state for `the-events-calendar`
	 * with a given set of features.
	 *
	 * Each entry in $feature_slugs becomes a Catalog_Feature in the Product_Catalog
	 * AND a capability on the Product_Entry, which is what makes
	 * lw_harbor_is_feature_available() return true for that slug.
	 *
	 * All features are registered as TYPE_SERVICE so is_enabled() collapses to
	 * is_available() — sufficient for exercising the TEC filter chain without
	 * needing a real active plugin installation.
	 *
	 * @param string[] $feature_slugs Slugs that the unified license unlocks.
	 */
	protected function seed_harbor_catalog_for_tec( array $feature_slugs ): void {
		$catalog_features = [];
		foreach ( $feature_slugs as $slug ) {
			$catalog_features[] = new Catalog_Feature(
				[
					'slug'              => $slug,
					'kind'              => 'service',
					'minimum_tier'      => 'elite',
					'name'              => ucwords( str_replace( '-', ' ', $slug ) ),
					'description'       => '',
					'category'          => '',
					'authors'           => [ 'The Events Calendar' ],
					'documentation_url' => 'https://docs.theeventscalendar.com',
					'homepage'          => 'https://theeventscalendar.com',
					'version'           => '1.0.0',
					'release_date'      => '2026-01-01',
				]
			);

			$this->harbor_seeded_service_features[ $slug ] = true;
		}

		$product_catalog = new Product_Catalog(
			'tec',
			'the-events-calendar',
			'The Events Calendar',
			new Tier_Collection(),
			$catalog_features
		);

		$catalog_collection = new Catalog_Collection();
		$catalog_collection->add( $product_catalog );

		Config::get_container()->get( Catalog_Repository::class )->set_catalog( $catalog_collection );

		$product_entry = Product_Entry::from_array(
			[
				'product_slug'      => 'the-events-calendar',
				'tier'              => 'elite',
				'status'            => 'active',
				'expires'           => '2027-12-31 23:59:59',
				'activations'       => [
					'site_limit'   => 10,
					'active_count' => 1,
					'domains'      => [ 'customer-site.example.com' ],
				],
				'activated_here'    => true,
				'validation_status' => 'valid',
				'capabilities'      => $feature_slugs,
			]
		);

		$product_collection = new Product_Collection();
		$product_collection->add( $product_entry );

		Config::get_container()->get( License_Repository::class )->set_products( $product_collection );

		// Force the in-memory feature cache to rebuild against the new catalog.
		Config::get_container()->get( Feature_Repository::class )->refresh();
	}
}
