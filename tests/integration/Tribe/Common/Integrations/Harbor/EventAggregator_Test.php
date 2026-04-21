<?php

namespace TEC\Common\Integrations\Harbor;

use Codeception\TestCase\WPTestCase;
use TEC\Common\Libraries\Harbor;
use Tribe\Tests\Traits\With_Harbor_State;

/**
 * Exercises the `tec_events_aggregator_harbor_took_over` filter across the
 * three states Event Aggregator cares about:
 *   - no unified license key
 *   - unified key stored but `event-aggregator` feature isn't enabled
 *   - unified key stored and `event-aggregator` feature is enabled
 *
 * "Took over" controls whether the unified licensing flow supersedes the legacy
 * Event Aggregator service licensing; a false positive here would silently
 * change the update/licensing path for every EA customer.
 */
class EventAggregator_Test extends WPTestCase {
	use With_Harbor_State;

	/**
	 * @test
	 */
	public function it_should_not_take_over_when_no_unified_license_key_is_stored(): void {
		// Catalog has event-aggregator, but no key is stored → cannot be "taken over".
		$this->seed_harbor_catalog_for_tec( [ 'event-aggregator' ] );

		$took_over = (bool) apply_filters( 'tec_events_aggregator_harbor_took_over', false );

		$this->assertFalse( $took_over );
	}

	/**
	 * @test
	 */
	public function it_should_not_take_over_when_feature_is_not_in_catalog(): void {
		$this->seed_unified_license_key();
		// Unified key covers other products, but EA is not in the catalog → not enabled.
		$this->seed_harbor_catalog_for_tec( [ 'events-calendar-pro', 'event-tickets' ] );

		$took_over = (bool) apply_filters( 'tec_events_aggregator_harbor_took_over', false );

		$this->assertFalse( $took_over );
	}

	/**
	 * @test
	 */
	public function it_should_take_over_when_unified_key_and_feature_are_both_present(): void {
		$this->seed_unified_license_key();
		$this->seed_harbor_catalog_for_tec( [ 'event-aggregator' ] );

		$took_over = (bool) apply_filters( 'tec_events_aggregator_harbor_took_over', false );

		$this->assertTrue( $took_over );
	}

	/**
	 * Matrix sanity: a customer with [ECP, ET, EA] authorized by the unified
	 * key should see EA took-over regardless of what else is in the catalog.
	 *
	 * @test
	 */
	public function it_should_take_over_when_feature_is_one_of_many_in_catalog(): void {
		$this->seed_unified_license_key();
		$this->seed_harbor_catalog_for_tec( [ 'events-calendar-pro', 'event-tickets', 'event-aggregator' ] );

		$took_over = (bool) apply_filters( 'tec_events_aggregator_harbor_took_over', false );

		$this->assertTrue( $took_over );
	}
}
