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
	 * Simulate Uplink's license-field AJAX so Harbor rewrites the validation result.
	 *
	 * @param callable $callback Callback to run with the AJAX action set.
	 *
	 * @return mixed
	 */
	private function with_uplink_license_ajax_request( callable $callback ) {
		$previous           = $_REQUEST['action'] ?? null;
		$_REQUEST['action'] = 'pue-validate-key-uplink-tec';

		try {
			return $callback();
		} finally {
			if ( null === $previous ) {
				unset( $_REQUEST['action'] );
			} else {
				$_REQUEST['action'] = $previous;
			}
		}
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
	 * @dataProvider unified_license_key_provider
	 */
	public function it_should_detect_unified_license_keys( string $key, bool $expected ): void {
		$this->assertSame( $expected, tribe( Harbor::class )->is_unified_license_key( $key ) );
	}

	public function unified_license_key_provider(): array {
		return [
			'prefixed key'              => [ 'LWSW-CUSTOMER-AAAA-BBBB', true ],
			'prefixed key with spaces'  => [ '  LWSW-CUSTOMER-AAAA-BBBB  ', true ],
			'legacy product key'        => [ 'abcd-1234-efgh-5678', false ],
			'empty string'              => [ '', false ],
			'prefix only substring'     => [ 'my-LWSW-not-a-key', false ],
		];
	}

	/**
	 * When Harbor never fully boots (no premium plugin), unified keys must still
	 * be rejected from free-plugin PUE fields instead of hitting remote validation.
	 *
	 * @test
	 */
	public function it_should_reject_unified_key_via_pre_validate_when_harbor_is_not_loaded(): void {
		global $wp_actions;

		$previous_loaded = $wp_actions['lw_harbor/loaded'] ?? null;
		unset( $wp_actions['lw_harbor/loaded'] );

		$checker = new \Tribe__PUE__Checker(
			'deprecated',
			'the-events-calendar',
			[],
			'the-events-calendar/the-events-calendar.php'
		);

		$result = apply_filters( 'tec_common_pue_pre_validate_key', null, 'LWSW-PASTED-WITHOUT-PREMIUM', $checker );

		if ( null !== $previous_loaded ) {
			$wp_actions['lw_harbor/loaded'] = $previous_loaded;
		}

		$this->assertIsArray( $result );
		$this->assertSame( 0, $result['status'] );
		$this->assertStringContainsString( 'unified license key', strtolower( $result['message'] ) );
		$this->assertStringContainsString( 'The Events Calendar Pro', $result['message'] );
		$this->assertStringContainsString( 'Event Tickets Plus', $result['message'] );
		$this->assertStringContainsString( 'Unified License Manager', $result['message'] );
	}

	/**
	 * @test
	 */
	public function it_should_pass_through_legacy_key_via_pre_validate_when_harbor_is_not_loaded(): void {
		global $wp_actions;

		$previous_loaded = $wp_actions['lw_harbor/loaded'] ?? null;
		unset( $wp_actions['lw_harbor/loaded'] );

		$checker = new \Tribe__PUE__Checker(
			'deprecated',
			'the-events-calendar',
			[],
			'the-events-calendar/the-events-calendar.php'
		);

		$result = tribe( Harbor::class )->filter_tec_common_pue_pre_validate_key( null, 'legacy-product-key', $checker );

		if ( null !== $previous_loaded ) {
			$wp_actions['lw_harbor/loaded'] = $previous_loaded;
		}

		$this->assertNull( $result );
	}

	/**
	 * Once Harbor is loaded, the PUE integration owns unified-key messaging;
	 * this callback must not short-circuit.
	 *
	 * @test
	 */
	public function it_should_pass_through_unified_key_via_pre_validate_when_harbor_is_loaded(): void {
		global $wp_actions;

		$previous_loaded = $wp_actions['lw_harbor/loaded'] ?? null;
		$wp_actions['lw_harbor/loaded'] = 1;

		$checker = new \Tribe__PUE__Checker(
			'deprecated',
			'the-events-calendar',
			[],
			'the-events-calendar/the-events-calendar.php'
		);

		$result = tribe( Harbor::class )->filter_tec_common_pue_pre_validate_key( null, 'LWSW-SHOULD-PASS-THROUGH', $checker );

		if ( null !== $previous_loaded ) {
			$wp_actions['lw_harbor/loaded'] = $previous_loaded;
		} else {
			unset( $wp_actions['lw_harbor/loaded'] );
		}

		$this->assertNull( $result );
	}

	/**
	 * Uplink AJAX never hits tec_common_pue_pre_validate_key; without a premium plugin
	 * Harbor still must reject unified keys on Uplink's client_validate_license filter.
	 *
	 * @test
	 */
	public function it_should_reject_unified_key_via_uplink_when_harbor_is_not_loaded(): void {
		global $wp_actions;

		$previous_loaded = $wp_actions['lw_harbor/loaded'] ?? null;
		unset( $wp_actions['lw_harbor/loaded'] );

		$resource = new \TEC\Common\StellarWP\Uplink\Resources\Plugin(
			'tec-seating',
			'Seating',
			'1.0.0',
			'event-tickets/event-tickets.php',
			\stdClass::class
		);

		$results = new \TEC\Common\StellarWP\Uplink\API\Validation_Response(
			'LWSW-PASTED-WITHOUT-PREMIUM',
			'local',
			(object) [ 'api_message' => 'remote' ],
			$resource
		);

		$filtered = $this->with_uplink_license_ajax_request(
			static function () use ( $results ) {
				return tribe( Harbor::class )->filter_stellarwp_uplink_tec_client_validate_license(
					$results,
					[
						'plugin' => 'tec-seating',
						'key'    => 'LWSW-PASTED-WITHOUT-PREMIUM',
					]
				);
			}
		);

		if ( null !== $previous_loaded ) {
			$wp_actions['lw_harbor/loaded'] = $previous_loaded;
		}

		$this->assertNotSame( $results, $filtered );
		$this->assertFalse( $filtered->is_valid() );
		$message = $filtered->get_message()->get();
		$this->assertStringContainsString( 'unified license key', strtolower( wp_strip_all_tags( $message ) ) );
		$this->assertStringContainsString( 'The Events Calendar Pro', $message );
		$this->assertStringContainsString( 'Event Tickets Plus', $message );
	}

	/**
	 * @test
	 */
	public function it_should_pass_legacy_key_via_uplink_when_harbor_is_not_loaded(): void {
		global $wp_actions;

		$previous_loaded = $wp_actions['lw_harbor/loaded'] ?? null;
		unset( $wp_actions['lw_harbor/loaded'] );

		$resource = new \TEC\Common\StellarWP\Uplink\Resources\Plugin(
			'tec-seating',
			'Seating',
			'1.0.0',
			'event-tickets/event-tickets.php',
			\stdClass::class
		);

		$results = new \TEC\Common\StellarWP\Uplink\API\Validation_Response(
			'legacy-product-key',
			'local',
			(object) [ 'api_message' => 'remote' ],
			$resource
		);

		$filtered = $this->with_uplink_license_ajax_request(
			static function () use ( $results ) {
				return tribe( Harbor::class )->filter_stellarwp_uplink_tec_client_validate_license(
					$results,
					[
						'plugin' => 'tec-seating',
						'key'    => 'legacy-product-key',
					]
				);
			}
		);

		if ( null !== $previous_loaded ) {
			$wp_actions['lw_harbor/loaded'] = $previous_loaded;
		}

		$this->assertSame( $results, $filtered );
	}

	/**
	 * Once Harbor is loaded, unmanaged Uplink fields still reject unified keys
	 * with a link to the Unified License Manager — not a remote invalid message.
	 *
	 * @test
	 */
	public function it_should_reject_unified_key_via_uplink_when_harbor_is_loaded_and_product_is_not_managed(): void {
		$this->seed_unified_license_key();
		$this->seed_harbor_catalog_for_tec( [ 'events-calendar-pro' ] );

		$resource = new \TEC\Common\StellarWP\Uplink\Resources\Plugin(
			'tec-seating',
			'Seating',
			'1.0.0',
			'event-tickets/event-tickets.php',
			\stdClass::class
		);

		$results = new \TEC\Common\StellarWP\Uplink\API\Validation_Response(
			'LWSW-PASTED-INTO-UPLINK-FIELD',
			'local',
			(object) [ 'api_message' => 'would-have-been-remote' ],
			$resource
		);

		$filtered = $this->with_uplink_license_ajax_request(
			static function () use ( $results ) {
				return tribe( Harbor::class )->filter_stellarwp_uplink_tec_client_validate_license(
					$results,
					[
						'plugin' => 'tec-seating',
						'key'    => 'LWSW-PASTED-INTO-UPLINK-FIELD',
					]
				);
			}
		);

		$this->assertNotSame( $results, $filtered );
		$this->assertFalse( $filtered->is_valid() );
		$message = $filtered->get_message()->get();
		$this->assertStringContainsString( 'unified license key', strtolower( wp_strip_all_tags( $message ) ) );
		$this->assertStringContainsString( 'Unified License Manager', $message );
		$this->assertStringNotContainsString( 'The Events Calendar Pro', $message );
	}

	/**
	 * Harbor-managed Uplink fields must not surface the remote invalid-key message.
	 *
	 * @test
	 */
	public function it_should_mark_uplink_valid_when_product_is_harbor_managed(): void {
		$this->seed_unified_license_key();
		$this->seed_harbor_catalog_for_tec( [ 'event-tickets-plus' ] );

		$resource = new \TEC\Common\StellarWP\Uplink\Resources\Plugin(
			'event-tickets-plus',
			'Event Tickets Plus',
			'1.0.0',
			'event-tickets-plus/event-tickets-plus.php',
			\stdClass::class
		);

		$results = new \TEC\Common\StellarWP\Uplink\API\Validation_Response(
			'LWSW-PASTED-INTO-UPLINK-FIELD',
			'local',
			(object) [ 'api_invalid' => 1, 'api_inline_invalid_message' => 'would-have-been-remote' ],
			$resource
		);

		$filtered = $this->with_uplink_license_ajax_request(
			static function () use ( $results ) {
				return tribe( Harbor::class )->filter_stellarwp_uplink_tec_client_validate_license(
					$results,
					[
						'plugin' => 'event-tickets-plus',
						'key'    => 'LWSW-PASTED-INTO-UPLINK-FIELD',
					]
				);
			}
		);

		$this->assertNotSame( $results, $filtered );
		$this->assertTrue( $filtered->is_valid() );
		$this->assertStringContainsString(
			'Unified License Manager',
			wp_strip_all_tags( $filtered->get_message()->get() )
		);
	}

	/**
	 * Uplink update checks share Client::validate_license(). Rewriting that
	 * result would drop version/download_url and hide automatic updates.
	 *
	 * @test
	 */
	public function it_should_not_rewrite_uplink_validation_during_update_checks(): void {
		$this->seed_unified_license_key();
		$this->seed_harbor_catalog_for_tec( [ 'event-tickets-plus' ] );

		$resource = new \TEC\Common\StellarWP\Uplink\Resources\Plugin(
			'event-tickets-plus',
			'Event Tickets Plus',
			'1.0.0',
			'event-tickets-plus/event-tickets-plus.php',
			\stdClass::class
		);

		$results = new \TEC\Common\StellarWP\Uplink\API\Validation_Response(
			'LWSW-UPDATE-CHECK',
			'local',
			(object) [
				'version'      => '6.0.0',
				'download_url' => 'https://herald.example/event-tickets-plus.zip',
			],
			$resource
		);

		$filtered = tribe( Harbor::class )->filter_stellarwp_uplink_tec_client_validate_license(
			$results,
			[
				'plugin' => 'event-tickets-plus',
				'key'    => 'LWSW-UPDATE-CHECK',
			]
		);

		$this->assertSame( $results, $filtered );
		$this->assertSame( '6.0.0', $filtered->get_raw_response()->version );
		$this->assertSame(
			'https://herald.example/event-tickets-plus.zip',
			$filtered->get_raw_response()->download_url
		);
	}

	/**
	 * @test
	 */
	public function it_should_report_license_field_managed_when_product_is_harbor_licensed(): void {
		$this->seed_unified_license_key();
		$this->seed_harbor_catalog_for_tec( [ 'events-calendar-pro' ] );

		$this->assertTrue(
			tribe( Harbor::class )->is_license_field_managed_by_harbor( 'events-calendar-pro' )
		);
	}

	/**
	 * @test
	 */
	public function it_should_translate_tec_slug_when_checking_license_field_management(): void {
		$this->seed_unified_license_key();
		$this->seed_harbor_catalog_for_tec( [ 'events-promoter' ] );

		$this->assertTrue(
			tribe( Harbor::class )->is_license_field_managed_by_harbor( 'promoter' )
		);
	}

	/**
	 * @test
	 */
	public function it_should_report_license_field_not_managed_when_product_is_unlicensed(): void {
		$this->seed_unified_license_key();
		$this->seed_harbor_catalog_for_tec( [ 'events-calendar-pro' ] );

		$this->assertFalse(
			tribe( Harbor::class )->is_license_field_managed_by_harbor( 'tribe-filterbar' )
		);
	}

	/**
	 * @test
	 */
	public function it_should_include_license_manager_link_in_unified_key_entry_error_message(): void {
		$message = tribe( Harbor::class )->get_unified_license_key_entry_error_message();

		$this->assertStringContainsString( 'unified license key', strtolower( $message ) );
		$this->assertStringContainsString( '<a href="', $message );
		$this->assertStringContainsString( 'Unified License Manager', $message );
	}

	/**
	 * @test
	 */
	public function it_should_include_license_manager_link_in_harbor_managed_license_message(): void {
		$message = tribe( Harbor::class )->get_harbor_managed_license_message();

		$this->assertStringContainsString( '<a href="', $message );
		$this->assertStringContainsString( 'Unified License Manager', $message );
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
