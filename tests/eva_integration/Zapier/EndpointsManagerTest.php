<?php

namespace Tribe\tests\eva_integration\Zapier;

use tad\Codeception\SnapshotAssertions\SnapshotAssertions;
use Tribe\Tests\Traits\With_Uopz;
use TEC\Event_Automator\Zapier\Actions;
use TEC\Event_Automator\Zapier\Admin\Endpoints_Manager;
use TEC\Event_Automator\Zapier\Template_Modifications;
use TEC\Event_Automator\Zapier\REST\V1\Endpoints\Abstract_REST_Endpoint;
use Tribe\Tickets\Plus\Integrations\Event_Automator\Zapier_Provider as Zapier_Tickets_Plus_Provider;
use Tribe\Events\Pro\Integrations\Event_Automator\Zapier_Provider as Zapier_Pro_Provider;

class EndpointsManagerTest extends \Codeception\TestCase\WPAjaxTestCase {

	use SnapshotAssertions;
	use With_Uopz;

	public function _tearDown() {
		parent::_tearDown();

		// Added to prevent "Test code or tested code did not (only) close its own output buffers" error.
		ob_start();
	}

	/**
	 * Setup AJAX Test.
	 *
	 * @since 6.0.0
	 */
	private function ajax_setup() {
		$this->set_fn_return( 'wp_create_nonce', 'c6f01bbbe9' );
		$this->set_fn_return( 'check_ajax_referer', true );
		$this->set_fn_return( 'wp_doing_ajax', true );
		$this->set_fn_return( 'wp_verify_nonce', true );
	}

	/**
	 * Generate IDs for the trigger queue.
	 *
	 * @since 6.0.0
	 *
	 * @param int $max The maximum number of ids to generate.
	 */
	private function generate_ids( int $max = 10 ) {
		$random = [];
		for ( $i = 0; $i < $max; $i ++ ) {
			$random[ $i ] = rand( 1000, 9999 );
		}

		return $random;
	}

	/**
	 * @test
	 */
	public function should_not_handle_ajax_clear_request_if_nonce_is_not_valid() {
		$api = new Endpoints_Manager( tribe( Actions::class ), tribe( Template_Modifications::class ) );

		try {
			$api->ajax_clear( 'foobar' );
		} catch ( \WPAjaxDieStopException $e ) {
			// Expected this, do nothing.
		}

		$this->assertTrue( isset( $e ) );
		$this->assertFalse( tribe_is_truthy( $e->getMessage() ) );
	}

	/**
	 * @test
	 */
	public function should_not_handle_ajax_disable_request_if_nonce_is_not_valid() {
		$api = new Endpoints_Manager( tribe( Actions::class ), tribe( Template_Modifications::class ) );

		try {
			$api->ajax_disable( 'foobar' );
		} catch ( \WPAjaxDieStopException $e ) {
			// Expected this, do nothing.
		}

		$this->assertTrue( isset( $e ) );
		$this->assertFalse( tribe_is_truthy( $e->getMessage() ) );
	}

	/**
	 * @test
	 */
	public function should_not_handle_ajax_enable_request_if_nonce_is_not_valid() {
		$api = new Endpoints_Manager( tribe( Actions::class ), tribe( Template_Modifications::class ) );

		try {
			$api->ajax_enable( 'foobar' );
		} catch ( \WPAjaxDieStopException $e ) {
			// Expected this, do nothing.
		}

		$this->assertTrue( isset( $e ) );
		$this->assertFalse( tribe_is_truthy( $e->getMessage() ) );
	}

	public function endpoint_data_provider() {
		return [
			'missing-endpoint-id'                 => [ '', 'authorize' ],
			'invalid-endpoint-id'                 => [ 'not-valid-endpoint', 'authorize' ],
			'valid-attendees-endpoint-id'         => [ 'attendees', 'attendee' ],
			'valid-updated-attendees-endpoint-id' => [ 'updated_attendees', 'updated_attendee' ],
			'valid-authorize-endpoint-id'         => [ 'authorize', 'authorize' ],
			'valid-canceled-events-endpoint-id'   => [ 'canceled_events', 'canceled_events' ],
			'valid-checkin-endpoint-id'           => [ 'checkin', 'checkin' ],
			'valid-new-events-endpoint-id'        => [ 'new_events', 'new_events' ],
			'valid-orders-endpoint-id'            => [ 'orders', 'orders' ],
			'valid-refunded-orders-endpoint-id'   => [ 'refunded_orders', 'refunded_orders' ],
			'valid-updated-events-endpoint-id'    => [ 'updated_events', 'updated_events' ],
		];
	}

	/**
	 * @test
	 * @dataProvider endpoint_data_provider
	 */
	public function should_correctly_handle_clearing_an_endpoint( $endpoint_id, $endpoint_details ) {
		$this->ajax_setup();

		tribe( Zapier_Tickets_Plus_Provider::class )->add_endpoints_to_dashboard();
		tribe( Zapier_Pro_Provider::class )->add_endpoints_to_dashboard();
		$_REQUEST['endpoint_id'] = $endpoint_id;
		$endpoints_manager       = new Endpoints_Manager( tribe( Actions::class ), tribe( Template_Modifications::class ) );
		$mock_api_key_data       = file_get_contents( codecept_data_dir( "Zapier/Endpoints/{$endpoint_details}.json" ) );
		$mock_endpoint_data      = json_decode( $mock_api_key_data, true );
		$endpoint                = $endpoints_manager->get_endpoint( $endpoint_id );

		// Test Endpoint counts.
		if ( $endpoint instanceof Abstract_REST_Endpoint && isset( $endpoint->trigger ) ) {
			$endpoint->set_endpoint_details( $mock_endpoint_data );
			$ids = $this->generate_ids( $mock_endpoint_data['count'] );
			$endpoint->trigger->set_queue( $ids );
			$endpoint_details = $endpoint->get_saved_details();
			$this->assertEquals( $mock_endpoint_data['count'], $endpoint_details['count'] );
		}

		try {
			$endpoints_manager->ajax_clear( wp_create_nonce( Actions::$clear_action ) );
		} catch ( \WPAjaxDieContinueException $e ) {
			// Expected this, do nothing.
		}

		//Check queue is cleared.
		$this->assertTrue( isset( $e ) );
		$html = $this->_last_response;

		// Test Endpoint counts after clear.
		if ( $endpoint instanceof Abstract_REST_Endpoint && isset( $endpoint->trigger ) ) {
			$queue = $endpoint->trigger->get_queue();
			$this->assertEquals( [], $queue );
		}

		$this->assertMatchesHtmlSnapshot( $html );
	}

	/**
	 * @test
	 * @dataProvider endpoint_data_provider
	 */
	public function should_correctly_handle_disabling_an_endpoint( $endpoint_id, $endpoint_details ) {
		$this->ajax_setup();

		tribe( Zapier_Tickets_Plus_Provider::class )->add_endpoints_to_dashboard();
		tribe( Zapier_Pro_Provider::class )->add_endpoints_to_dashboard();
		$_REQUEST['endpoint_id'] = $endpoint_id;
		$endpoints_manager       = new Endpoints_Manager( tribe( Actions::class ), tribe( Template_Modifications::class ) );
		$mock_api_key_data       = file_get_contents( codecept_data_dir( "Zapier/Endpoints/{$endpoint_details}.json" ) );
		$mock_endpoint_data      = json_decode( $mock_api_key_data, true );
		$endpoint                = $endpoints_manager->get_endpoint( $endpoint_id );

		// Test Endpoint counts.
		if ( $endpoint instanceof Abstract_REST_Endpoint && isset( $endpoint->trigger ) ) {
			$endpoint->set_endpoint_details( $mock_endpoint_data );
			$ids = $this->generate_ids( $mock_endpoint_data['count'] );
			$endpoint->trigger->set_queue( $ids );
			$endpoint_details = $endpoint->get_saved_details();
			$this->assertEquals( $mock_endpoint_data['count'], $endpoint_details['count'] );
		}

		try {
			$endpoints_manager->ajax_disable( wp_create_nonce( Actions::$disable_action ) );
		} catch ( \WPAjaxDieContinueException $e ) {
			// Expected this, do nothing.
		}

		//Check queue is cleared.
		$this->assertTrue( isset( $e ) );
		$html = $this->_last_response;

		// Test Endpoint counts after disable.
		if ( $endpoint instanceof Abstract_REST_Endpoint && isset( $endpoint->trigger ) ) {
			$queue = $endpoint->trigger->get_queue();
			$this->assertEquals( [], $queue );
		}

		$this->assertMatchesHtmlSnapshot( $html );
	}

	/**
	 * @test
	 * @dataProvider endpoint_data_provider
	 */
	public function should_correctly_handle_enabling_an_endpoint( $endpoint_id, $endpoint_details ) {
		$this->ajax_setup();

		tribe( Zapier_Tickets_Plus_Provider::class )->add_endpoints_to_dashboard();
		tribe( Zapier_Pro_Provider::class )->add_endpoints_to_dashboard();
		$_REQUEST['endpoint_id']       = $endpoint_id;
		$endpoints_manager             = new Endpoints_Manager( tribe( Actions::class ), tribe( Template_Modifications::class ) );
		$mock_api_key_data             = file_get_contents( codecept_data_dir( "Zapier/Endpoints/{$endpoint_details}.json" ) );
		$mock_endpoint_data            = json_decode( $mock_api_key_data, true );
		$mock_endpoint_data['count']   = 0;
		$mock_endpoint_data['enabled'] = false;
		$endpoint                      = $endpoints_manager->get_endpoint( $endpoint_id );

		// Test Endpoint counts.
		if ( $endpoint instanceof Abstract_REST_Endpoint && isset( $endpoint->trigger ) ) {
			$endpoint->set_endpoint_details( $mock_endpoint_data );
			$endpoint_details = $endpoint->get_saved_details();
			$this->assertEquals( $mock_endpoint_data['enabled'], $endpoint_details['enabled'] );
			$endpoint->trigger->set_queue( [] );
		}

		try {
			if ( $endpoint_id ==='orders' ) {
				//var_dump('hi');
			}
			$endpoints_manager->ajax_enable( wp_create_nonce( Actions::$enable_action ) );
		} catch ( \WPAjaxDieContinueException $e ) {
			// Expected this, do nothing.
		}

		//Check queue is cleared.
		$this->assertTrue( isset( $e ) );
		$html = $this->_last_response;

		if ( $endpoint_id ==='orders' ) {
			//var_dump($html);
		}

		// Test Endpoint counts after disable.
		if ( $endpoint instanceof Abstract_REST_Endpoint && isset( $endpoint->trigger ) ) {
			$updated_details = $endpoint->get_saved_details();
			$this->assertEquals( true, $updated_details['enabled'] );
		}

		$this->assertMatchesHtmlSnapshot( $html );
	}
}
