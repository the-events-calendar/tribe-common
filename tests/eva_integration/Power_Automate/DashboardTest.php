<?php

namespace Tribe\tests\eva_integration\Power_Automate;

use tad\Codeception\SnapshotAssertions\SnapshotAssertions;
use TEC\Event_Automator\Power_Automate\Admin\Dashboard;
use Tribe\Tickets\Plus\Integrations\Event_Automator\Power_Automate_Provider as Power_Automate_Tickets_Plus_Provider;
use Tribe\Events\Pro\Integrations\Event_Automator\Power_Automate_Provider as Power_Automate_Pro_Provider;
use Tribe\Tests\Traits\With_Uopz;

class DashboardTest extends \Codeception\TestCase\WPTestCase {

	use SnapshotAssertions;
	use With_Uopz;

	public function setUp() {
		// before
		parent::setUp();

		// Clear settings between tests.
		tribe_unset_var( \Tribe__Settings_Manager::OPTION_CACHE_VAR_NAME );
	}

	/**
	 * @test
	 */
	public function should_correctly_render_tickets_plus_dashboard_fields_initial_state() {
		$this->set_fn_return( 'wp_create_nonce', '123123' );
		$dashboard = tribe( Dashboard::class );
		tribe( Power_Automate_Tickets_Plus_Provider::class )->add_endpoints_to_dashboard();
		$fields   = $dashboard->add_fields( [] );

		$this->assertMatchesJsonSnapshot( json_encode( $fields, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function should_correctly_render_pro_dashboard_fields_initial_state() {
		$this->set_fn_return( 'wp_create_nonce', '123123' );
		$dashboard = tribe( Dashboard::class );
		tribe( Power_Automate_Pro_Provider::class )->add_endpoints_to_dashboard();
		$fields   = $dashboard->add_fields( [] );

		$this->assertMatchesJsonSnapshot( json_encode( $fields, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function should_correctly_render_all_dashboard_fields_initial_state() {
		$this->set_fn_return( 'wp_create_nonce', '123123' );
		$dashboard = tribe( Dashboard::class );
		tribe( Power_Automate_Tickets_Plus_Provider::class )->add_endpoints_to_dashboard();
		tribe( Power_Automate_Pro_Provider::class )->add_endpoints_to_dashboard();
		$fields   = $dashboard->add_fields( [] );

		$this->assertMatchesJsonSnapshot( json_encode( $fields, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function should_correctly_render_dashboard_fields_disabled() {
		$this->set_fn_return( 'wp_create_nonce', '123123' );
		$dashboard = tribe( Dashboard::class );
		tribe( Power_Automate_Tickets_Plus_Provider::class )->add_endpoints_to_dashboard();
		tribe( Power_Automate_Pro_Provider::class )->add_endpoints_to_dashboard();
		add_filter( 'tec_event_automator_zapier_endpoints', function( $endpoints ) {
			$endpoints['TEC\Event_Automator\Zapier\REST\V1\Endpoints\Queue\New_Events']['enabled'] = false;
			$endpoints['TEC\Event_Automator\Zapier\REST\V1\Endpoints\Queue\Attendees']['enabled']  = false;

			return $endpoints;
		});

		$fields   = $dashboard->add_fields( [] );

		$this->assertMatchesJsonSnapshot( json_encode( $fields, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function should_correctly_render_dashboard_fields_queue_counts() {
		$this->set_fn_return( 'wp_create_nonce', '123123' );
		$dashboard = tribe( Dashboard::class );
		tribe( Power_Automate_Tickets_Plus_Provider::class )->add_endpoints_to_dashboard();
		tribe( Power_Automate_Pro_Provider::class )->add_endpoints_to_dashboard();
		add_filter( 'tec_event_automator_zapier_endpoints', function( $endpoints ) {
			$endpoints['TEC\Event_Automator\Zapier\REST\V1\Endpoints\Queue\New_Events']['count'] = 4646;
			$endpoints['TEC\Event_Automator\Zapier\REST\V1\Endpoints\Queue\Attendees']['count']  = 588;

			return $endpoints;
		});

		$fields   = $dashboard->add_fields( [] );

		$this->assertMatchesJsonSnapshot( json_encode( $fields, JSON_PRETTY_PRINT ) );
	}


	/**
	 * @test
	 */
	public function should_correctly_get_all_endpoint_details() {
		$dashboard = tribe( Dashboard::class );
		tribe( Power_Automate_Tickets_Plus_Provider::class )->add_endpoints_to_dashboard();
		tribe( Power_Automate_Pro_Provider::class )->add_endpoints_to_dashboard();
		$endpoints = $dashboard->get_endpoints();

		$this->assertMatchesJsonSnapshot( json_encode( $endpoints, JSON_PRETTY_PRINT ) );
	}
}
