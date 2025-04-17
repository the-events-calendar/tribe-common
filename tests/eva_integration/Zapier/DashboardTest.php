<?php

namespace Tribe\tests\eva_integration\Zapier;

use tad\Codeception\SnapshotAssertions\SnapshotAssertions;
use TEC\Event_Automator\Zapier\Admin\Dashboard;
use Tribe\Tests\Traits\With_Uopz;
use Tribe\Tickets\Plus\Integrations\Event_Automator\Zapier_Provider as Zapier_Tickets_Plus_Provider;
use Tribe\Events\Pro\Integrations\Event_Automator\Zapier_Provider as Zapier_Pro_Provider;

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
		tribe( Zapier_Tickets_Plus_Provider::class )->add_endpoints_to_dashboard();
		$fields   = $dashboard->add_fields( [] );
		add_filter( 'tec_event_automator_zapier_endpoints', function( $endpoints ) {
			$endpoints['TEC\Event_Automator\Zapier\REST\V1\Endpoints\New_Events']['count']      = 1;
			$endpoints['TEC\Event_Automator\Zapier\REST\V1\Endpoints\Attendees']['count']       = 1;
			$endpoints['TEC\Event_Automator\Zapier\REST\V1\Endpoints\Checkin']['count']         = 1;

			foreach ( $endpoints as $class => &$data ) {
				if ( 0 === strpos( $class, 'TEC\Event_Automator\Zapier\REST\V1\Endpoints\\' ) ) {
					$data['count'] = 1;
				}
			}
			unset( $data );

			return $endpoints;
		});

		$this->assertMatchesJsonSnapshot( json_encode( $fields, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function should_correctly_render_pro_dashboard_fields_initial_state() {
		$this->set_fn_return( 'wp_create_nonce', '123123' );
		$dashboard = tribe( Dashboard::class );
		tribe( Zapier_Pro_Provider::class )->add_endpoints_to_dashboard();
		$fields   = $dashboard->add_fields( [] );
		add_filter( 'tec_event_automator_zapier_endpoints', function( $endpoints ) {
			$endpoints['TEC\Event_Automator\Zapier\REST\V1\Endpoints\New_Events']['count']      = 0;
			$endpoints['TEC\Event_Automator\Zapier\REST\V1\Endpoints\Attendees']['count']       = 0;
			$endpoints['TEC\Event_Automator\Zapier\REST\V1\Endpoints\Checkin']['count']         = 0;

			foreach ( $endpoints as $class => &$data ) {
				if ( 0 === strpos( $class, 'TEC\Event_Automator\Zapier\REST\V1\Endpoints\\' ) ) {
					$data['count'] = 1;
				}
			}
			unset( $data );

			return $endpoints;
		});

		$this->assertMatchesJsonSnapshot( json_encode( $fields, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function should_correctly_render_all_dashboard_fields_initial_state() {
		$this->set_fn_return( 'wp_create_nonce', '123123' );
		$dashboard = tribe( Dashboard::class );
		tribe( Zapier_Tickets_Plus_Provider::class )->add_endpoints_to_dashboard();
		tribe( Zapier_Pro_Provider::class )->add_endpoints_to_dashboard();
		add_filter( 'tec_event_automator_zapier_endpoints', function( $endpoints ) {
			$endpoints['TEC\Event_Automator\Zapier\REST\V1\Endpoints\New_Events']['enabled'] = false;
			$endpoints['TEC\Event_Automator\Zapier\REST\V1\Endpoints\Attendees']['enabled']  = false;
			$endpoints['TEC\Event_Automator\Zapier\REST\V1\Endpoints\Checkin']['enabled']    = false;
			foreach ( $endpoints as $class => &$data ) {
				if ( 0 === strpos( $class, 'TEC\Event_Automator\Zapier\REST\V1\Endpoints\\' ) ) {
					$data['count'] = 1;
				}
			}
			unset( $data );

			return $endpoints;
		});
		$fields   = $dashboard->add_fields( [] );

		$this->assertMatchesJsonSnapshot( json_encode( $fields, JSON_PRETTY_PRINT ) );
	}


	/**
	 * @test
	 */
	public function should_correctly_render_dashboard_fields_disabled() {
		$this->set_fn_return( 'wp_create_nonce', '123123' );
		$dashboard = tribe( Dashboard::class );
		tribe( Zapier_Tickets_Plus_Provider::class )->add_endpoints_to_dashboard();
		tribe( Zapier_Pro_Provider::class )->add_endpoints_to_dashboard();
		add_filter(
			'tec_event_automator_zapier_endpoints',
			function ( $endpoints ) {
				$endpoints['TEC\Event_Automator\Zapier\REST\V1\Endpoints\New_Events']['enabled'] = false;
				$endpoints['TEC\Event_Automator\Zapier\REST\V1\Endpoints\Attendees']['enabled']  = false;
				$endpoints['TEC\Event_Automator\Zapier\REST\V1\Endpoints\Checkin']['enabled']    = false;

				foreach ( $endpoints as $class => &$data ) {
					if ( 0 === strpos( $class, 'TEC\Event_Automator\Zapier\REST\V1\Endpoints\\' ) ) {
						$data['count'] = 1;
					}
				}
				unset( $data );

				return $endpoints;
			},
			999
		);

		$fields   = $dashboard->add_fields( [] );

		$this->assertMatchesJsonSnapshot( json_encode( $fields, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function should_correctly_render_dashboard_fields_queue_counts() {
		$this->set_fn_return( 'wp_create_nonce', '123123' );
		$dashboard = tribe( Dashboard::class );
		tribe( Zapier_Tickets_Plus_Provider::class )->add_endpoints_to_dashboard();
		tribe( Zapier_Pro_Provider::class )->add_endpoints_to_dashboard();
		add_filter( 'tec_event_automator_zapier_endpoints', function( $endpoints ) {
			foreach ( $endpoints as $class => &$data ) {
				if ( 0 === strpos( $class, 'TEC\Event_Automator\Zapier\REST\V1\Endpoints\\' ) ) {
					$data['count'] = 1;
				}
			}
			unset( $data );
			$endpoints['TEC\Event_Automator\Zapier\REST\V1\Endpoints\Canceled_Events']['count'] = 4646;
			$endpoints['TEC\Event_Automator\Zapier\REST\V1\Endpoints\Updated_Events']['count']  = 588;
			$endpoints['TEC\Event_Automator\Zapier\REST\V1\Endpoints\Orders']['count']          = 1252;

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
		tribe( Zapier_Tickets_Plus_Provider::class )->add_endpoints_to_dashboard();
		tribe( Zapier_Pro_Provider::class )->add_endpoints_to_dashboard();
		$endpoints = $dashboard->get_endpoints();

		$this->assertMatchesJsonSnapshot( json_encode( $endpoints, JSON_PRETTY_PRINT ) );
	}
}
