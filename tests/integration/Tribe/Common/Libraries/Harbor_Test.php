<?php

namespace TEC\Common\Libraries;

use Codeception\TestCase\WPTestCase;
use Tribe\Tests\Traits\With_Harbor_State;

/**
 * Exercises the TEC Harbor wrapper's public API and its `lw-harbor/legacy_licenses`
 * filter bridge.
 *
 * Harbor is the single seam every TEC hook uses to answer "is this product
 * licensed under the unified key?". Its three getters and its TEC→Harbor slug
 * map must behave identically to what the PUE / EventAggregator filters expect.
 */
class Harbor_Test extends WPTestCase {
	use With_Harbor_State;

	/**
	 * @before
	 */
	public function ensure_harbor_registered(): void {
		tribe( Harbor::class );
	}

	/**
	 * @test
	 * @dataProvider tec_to_harbor_slug_provider
	 */
	public function it_should_translate_tec_slug_to_harbor_slug( string $tec_slug, string $expected ): void {
		$this->assertSame( $expected, tribe( Harbor::class )->get_harbor_product_slug( $tec_slug ) );
	}

	public function tec_to_harbor_slug_provider(): array {
		return [
			'tec stays as-is'              => [ 'the-events-calendar', 'the-events-calendar' ],
			'ECP stays as-is'              => [ 'events-calendar-pro', 'events-calendar-pro' ],
			'ET stays as-is'               => [ 'event-tickets', 'event-tickets' ],
			'ETP stays as-is'              => [ 'event-tickets-plus', 'event-tickets-plus' ],
			'promoter translates'          => [ 'promoter', 'events-promoter' ],
			'tec-seating translates'       => [ 'tec-seating', 'seating' ],
			'unknown slug passes through'  => [ 'some-third-party-plugin', 'some-third-party-plugin' ],
			'empty string passes through'  => [ '', '' ],
		];
	}

	/**
	 * @test
	 */
	public function it_should_return_null_when_no_key_is_stored(): void {
		$this->assertNull( tribe( Harbor::class )->get_unified_license_key() );
	}

	/**
	 * @test
	 */
	public function it_should_return_stored_unified_key(): void {
		$key = $this->seed_unified_license_key( 'LWSW-CUSTOMER-AAAA-BBBB' );

		$this->assertSame( $key, tribe( Harbor::class )->get_unified_license_key() );
	}

	/**
	 * @test
	 */
	public function it_should_report_product_licensed_when_in_unified_catalog(): void {
		$this->seed_unified_license_key();
		$this->seed_harbor_catalog_for_tec( [ 'events-calendar-pro' ] );

		$this->assertTrue( tribe( Harbor::class )->is_product_licensed( 'events-calendar-pro' ) );
	}

	/**
	 * @test
	 */
	public function it_should_report_product_unlicensed_when_not_in_catalog(): void {
		$this->seed_unified_license_key();
		$this->seed_harbor_catalog_for_tec( [ 'events-calendar-pro' ] );

		$this->assertFalse( tribe( Harbor::class )->is_product_licensed( 'tribe-filterbar' ) );
	}

	/**
	 * @test
	 */
	public function it_should_report_product_unlicensed_without_unified_key(): void {
		// Catalog is seeded but no key is stored — feature resolution returns WP_Error.
		$this->seed_harbor_catalog_for_tec( [ 'events-calendar-pro' ] );

		$this->assertFalse( tribe( Harbor::class )->is_product_licensed( 'events-calendar-pro' ) );
	}

	/**
	 * @test
	 */
	public function it_should_return_null_from_if_feature_enabled_when_no_key_stored(): void {
		$this->seed_harbor_catalog_for_tec( [ 'events-calendar-pro' ] );

		$this->assertNull( tribe( Harbor::class )->get_unified_license_key_if_feature_enabled( 'events-calendar-pro' ) );
	}

	/**
	 * @test
	 */
	public function it_should_return_null_from_if_feature_enabled_when_feature_not_in_catalog(): void {
		$this->seed_unified_license_key();
		$this->seed_harbor_catalog_for_tec( [ 'event-tickets' ] );

		$this->assertNull( tribe( Harbor::class )->get_unified_license_key_if_feature_enabled( 'events-calendar-pro' ) );
	}

	/**
	 * @test
	 */
	public function it_should_return_unified_key_from_if_feature_enabled_when_both_present(): void {
		$key = $this->seed_unified_license_key();
		$this->seed_harbor_catalog_for_tec( [ 'events-calendar-pro' ] );

		$this->assertSame( $key, tribe( Harbor::class )->get_unified_license_key_if_feature_enabled( 'events-calendar-pro' ) );
	}

	/**
	 * @test
	 */
	public function it_should_return_null_from_if_feature_available_when_no_key_stored(): void {
		$this->seed_harbor_catalog_for_tec( [ 'events-calendar-pro' ] );

		$this->assertNull( tribe( Harbor::class )->get_unified_license_key_if_feature_available( 'events-calendar-pro' ) );
	}

	/**
	 * @test
	 */
	public function it_should_return_unified_key_from_if_feature_available_when_both_present(): void {
		$key = $this->seed_unified_license_key();
		$this->seed_harbor_catalog_for_tec( [ 'events-calendar-pro' ] );

		$this->assertSame( $key, tribe( Harbor::class )->get_unified_license_key_if_feature_available( 'events-calendar-pro' ) );
	}

	/**
	 * The Harbor controller subscribes to `lw-harbor/legacy_licenses`; firing that
	 * filter should yield an array of license entries keyed by `slug` (Harbor's
	 * slug, not TEC's), each with the canonical shape Harbor requires.
	 *
	 * @test
	 */
	public function it_should_add_legacy_license_entries_via_harbor_filter(): void {
		$output = apply_filters( 'lw-harbor/legacy_licenses', [] );

		$this->assertIsArray( $output );
		foreach ( $output as $entry ) {
			$this->assertArrayHasKey( 'key', $entry );
			$this->assertArrayHasKey( 'slug', $entry );
			$this->assertArrayHasKey( 'name', $entry );
			$this->assertArrayHasKey( 'product', $entry );
			$this->assertArrayHasKey( 'is_active', $entry );
			$this->assertArrayHasKey( 'page_url', $entry );
			$this->assertArrayHasKey( 'expires_at', $entry );

			// Entries are filtered to non-empty keys before being returned.
			$this->assertNotEmpty( $entry['key'] );

			// Every entry is anchored to the TEC product for Harbor grouping.
			$this->assertSame( 'the-events-calendar', $entry['product'] );
		}
	}

	/**
	 * Regression guard: the filter must preserve caller-provided entries and
	 * only append its own.
	 *
	 * @test
	 */
	public function it_should_preserve_incoming_entries_in_legacy_license_filter(): void {
		$external_entry = [
			'key'        => 'external-vendor-key',
			'slug'       => 'some-other-vendor',
			'name'       => 'Third Party Plugin',
			'product'    => 'some-other-vendor',
			'is_active'  => true,
			'page_url'   => 'https://example.com',
			'expires_at' => '',
		];

		$output = apply_filters( 'lw-harbor/legacy_licenses', [ $external_entry ] );

		$this->assertContains( $external_entry, $output );
	}
}
