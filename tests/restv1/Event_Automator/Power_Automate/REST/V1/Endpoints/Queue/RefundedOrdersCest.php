<?php

namespace TEC\Event_Automator\Power_Automate\REST\V1\Endpoints\Queue;

use TEC\Event_Automator\Tests\Testcases\REST\V1\BaseRestPowerAutomateCest;
use Restv1Tester;
use tad\Codeception\SnapshotAssertions\SnapshotAssertions;
use TEC\Event_Automator\Tests\Traits\Create_Events;
use TEC\Event_Automator\Tests\Traits\Create_Attendees;

class RefundedOrdersCest extends BaseRestPowerAutomateCest {

	use SnapshotAssertions;
	use Create_Events;
	use Create_Attendees;

	/**
	 * @inheritdoc
	 */
	protected static $current_test_url = 'refunded-orders';

	/**
	 * @test
	 */
	public function it_should_return_error_when_missing_token_parameters( Restv1Tester $I ) {
		$I->sendGET( $this->refunded_orders_url );
		$I->seeResponseCodeIs( 401 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function it_should_return_invalid_when_access_token_contains_unverified_consumer_id_and_secret( Restv1Tester $I ) {
		$I->sendGET( $this->refunded_orders_url, [ 'access_token' => static::$invalid_access_token ] );
		$I->seeResponseCodeIs( 401 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function it_should_return_valid_when_access_token_contains_verified_consumer_id_and_secret_but_no_event_msg( Restv1Tester $I ) {
		$this->setup_api_key_pair( $I );
		$I->sendGET( $this->refunded_orders_url, [ 'access_token' => static::$valid_access_token ] );
		$I->seeResponseCodeIs( 200 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function it_should_return_valid_with_access_token_contains_verified_api_key_pair_and_last_access_is_updated( Restv1Tester $I ) {
		$this->setup_api_key_pair( $I );
		$I->haveHttpHeader( 'eva-app-name', 'integration-event-tickets' );
		$I->sendGET( $this->refunded_orders_url, [ 'access_token' => static::$valid_access_token ] );
		$I->seeResponseCodeIs( 200 );
		$I->seeResponseIsJson();

		// Check Last Access is Updated.
		$api_key_data = get_option( 'tec_power_automate_connection_6a8dc385e71764bac6b22ba6ccac07ba17e3904509f6d60f712e00ba080befd8' );
		$I->test_last_access( $api_key_data);

		// Check Last Access is Updated for Endpoint.
		$endpoint_details = get_option( '_tec_power_automate_endpoint_details_refunded_orders' );
		$I->test_last_access( $endpoint_details);
	}

	/**
	 * @test
	 */
	public function it_should_return_valid_with_access_token_but_with_invalid_order_id( Restv1Tester $I ) {
		$invalid_id = [ 'post_id' ];
		$this->setup_refunded_orders_queue( $I, $invalid_id );
		$this->setup_api_key_pair( $I );
		$I->sendGET( $this->refunded_orders_url, [ 'access_token' => static::$valid_access_token ] );
		$I->seeResponseCodeIs( 200 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function it_should_return_valid_with_access_token_but_with_invalid_post_type( Restv1Tester $I ) {
		$I->haveManyPostsInDatabase( 1 );
		$postsTable        = $I->grabPostsTableName();
		$last              = $I->grabLatestEntryByFromDatabase( $postsTable, 'ID' );
		$invalid_post_type = [ $last ];
		$this->setup_refunded_orders_queue( $I, $invalid_post_type );

		$this->setup_api_key_pair( $I );
		$I->sendGET( $this->refunded_orders_url, [ 'access_token' => static::$valid_access_token ] );
		$response = json_decode( $I->grabResponse(), true );
		$I->seeResponseCodeIs( 200 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 * @skip TC cart does not clear between tests causing, "Trying to access array offset on value of type null".
	 */
	public function it_should_process_tc_refunded_orders_queue( Restv1Tester $I ) {
		$event      = $this->generate_event( $this->mock_date_value );
		$order_id_1 = $this->generate_tc_order_and_refund_it( $event );
		$order_id_2 = $this->generate_tc_order_and_refund_it( $event );
		$order_ids  = [ $order_id_1, $order_id_2 ];
		$this->setup_refunded_orders_queue( $I, $order_ids );
		$this->setup_api_key_pair( $I );

		$order_ids[] = 'no-new-refunded-orders';
		foreach ( $order_ids as $order_id ) {
			$I->sendGET( $this->refunded_orders_url, [ 'access_token' => static::$valid_access_token ] );
			$I->seeResponseCodeIs( 200 );
			$I->seeResponseIsJson();
			$response = json_decode( $I->grabResponse(), true );
			if ( $order_id === 'no-new-refunded-orders' ) {
				$I->assertArrayHasKey( 'id', $response['orders'] );
				$I->assertEquals( $order_id, $response['orders']['id'] );
			} else {
				$I->assertArrayHasKey( 'id', $response['orders'][0] );
				$I->assertEquals( $order_id, $response['orders'][0]['order_id'] );
			}
		}
	}

	/**
	 * @test
	 */
	public function it_should_process_edd_refunded_orders_queue( Restv1Tester $I ) {
		$event      = $this->generate_event( $this->mock_date_value );
		$order_id_1 = $this->generate_edd_order_and_refund_it( $event );
		$order_id_2 = $this->generate_edd_order_and_refund_it( $event );
		$order_ids  = [ $order_id_1, $order_id_2 ];
		$this->setup_refunded_orders_queue( $I, $order_ids );
		$this->setup_api_key_pair( $I );

		$order_ids[] = 'no-new-refunded-orders';
		foreach ( $order_ids as $order_id ) {
			$I->sendGET( $this->refunded_orders_url, [ 'access_token' => static::$valid_access_token ] );
			$I->seeResponseCodeIs( 200 );
			$I->seeResponseIsJson();
			$response = json_decode( $I->grabResponse(), true );
			if ( $order_id === 'no-new-refunded-orders' ) {
				$I->assertArrayHasKey( 'id', $response['orders'] );
				$I->assertEquals( $order_id, $response['orders']['id'] );
			} else {
				$I->assertArrayHasKey( 'id', $response['orders'][0] );
				$I->assertEquals( $order_id, $response['orders'][0]['order_id'] );
			}
		}
	}

	/**
	 * @test
	 */
	public function it_should_process_woo_refunded_orders_queue( Restv1Tester $I ) {
		$event      = $this->generate_event( $this->mock_date_value );
		$order_id_1 = $this->generate_woo_order( $event );
		$order_id_2 = $this->generate_woo_order( $event );
		$order_ids  = [ $order_id_1, $order_id_2 ];
		$this->setup_refunded_orders_queue( $I, $order_ids );
		$this->setup_api_key_pair( $I );

		$order_ids[] = 'no-new-refunded-orders';
		foreach ( $order_ids as $order_id ) {
			$I->sendGET( $this->refunded_orders_url, [ 'access_token' => static::$valid_access_token ] );
			$I->seeResponseCodeIs( 200 );
			$I->seeResponseIsJson();
			$response = json_decode( $I->grabResponse(), true );
			if ( $order_id === 'no-new-refunded-orders' ) {
				$I->assertArrayHasKey( 'id', $response['orders'] );
				$I->assertEquals( $order_id, $response['orders']['id'] );
			} else {
				$I->assertArrayHasKey( 'id', $response['orders'][0] );
				$I->assertEquals( $order_id, $response['orders'][0]['order_id'] );
			}
		}
	}

	/**
	 * @test
	 * @skip TC cart does not clear between tests causing, "Trying to access array offset on value of type null".
	 */
	public function it_should_process_all_provider_refunded_orders_queue( Restv1Tester $I ) {
		$event      = $this->generate_event( $this->mock_date_value );
		$order_id_1 = $this->generate_woo_order_and_refund_it( $event );
		$order_id_2 = $this->generate_tc_order_and_refund_it( $event );
		$order_id_3 = $this->generate_edd_order_and_refund_it( $event );
		$order_ids  = [ $order_id_1, $order_id_2, $order_id_3 ];
		$this->setup_refunded_orders_queue( $I, $order_ids );
		$this->setup_api_key_pair( $I );

		$order_ids[] = 'no-new-refunded-orders';
		foreach ( $order_ids as $order_id ) {
			$I->sendGET( $this->refunded_orders_url, [ 'access_token' => static::$valid_access_token ] );
			$I->seeResponseCodeIs( 200 );
			$I->seeResponseIsJson();
			$response = json_decode( $I->grabResponse(), true );
			if ( $order_id === 'no-new-refunded-orders' ) {
				$I->assertArrayHasKey( 'id', $response['orders'] );
				$I->assertEquals( $order_id, $response['orders']['id'] );
			} else {
				$I->assertArrayHasKey( 'id', $response['orders'][0] );
				$I->assertEquals( $order_id, $response['orders'][0]['order_id'] );
			}
		}
	}

	/**
	 * @test
	 */
	public function it_should_return_404_when_endpoint_disabled( Restv1Tester $I ) {
		$endpoint = [
			'id'           => 'refunded_orders',
			'display_name' => 'Refunded Orders',
			'type'         => 'queue',
			'last_access'  => '',
			'count'        => 0,
			'enabled'      => false,
		];
		$this->disable_endpoint( $I, '_tec_power_automate_endpoint_details_refunded_orders', $endpoint );
		$this->setup_api_key_pair( $I );
		$I->sendGET( $this->refunded_orders_url, [ 'access_token' => static::$valid_access_token ] );
		$I->seeResponseCodeIs( 404 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}
}
