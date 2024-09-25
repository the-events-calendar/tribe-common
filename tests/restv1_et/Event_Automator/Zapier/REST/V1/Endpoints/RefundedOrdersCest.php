<?php

namespace TEC\Event_Automator\Zapier\REST\V1\Endpoints;

use TEC\Event_Automator\Tests\Testcases\REST\V1\BaseRestETCest;
use Restv1_etTester;
use tad\Codeception\SnapshotAssertions\SnapshotAssertions;
use TEC\Event_Automator\Tests\Traits\Create_events;
use TEC\Event_Automator\Tests\Traits\Create_attendees;

class RefundedOrdersCest extends BaseRestETCest {

	use SnapshotAssertions;
	use Create_events;
	use Create_attendees;

	/**
	 * @inheritdoc
	 */
	protected static $current_test_url = 'refunded-orders';

	/**
	 * @test
	 */
	public function it_should_return_error_when_missing_token_parameters( Restv1_etTester $I ) {
		$I->sendGET( static::$current_test_url );
		$I->seeResponseCodeIs( 400 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function it_should_return_invalid_when_access_token_contains_unverified_consumer_id_and_secret( Restv1_etTester $I ) {
		$I->sendGET( static::$current_test_url, [ 'access_token' => static::$invalid_access_token ] );
		$I->seeResponseCodeIs( 400 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function it_should_return_valid_when_access_token_contains_verified_consumer_id_and_secret_but_no_event_msg( Restv1_etTester $I ) {
		$this->setup_api_key_pair( $I );
		$I->sendGET( static::$current_test_url, [ 'access_token' => static::$valid_access_token ] );
		$I->seeResponseCodeIs( 200 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function it_should_return_valid_with_access_token_contains_verified_api_key_pair_and_last_access_is_updated( Restv1_etTester $I ) {
		$this->setup_api_key_pair( $I );
		$I->sendGET( static::$current_test_url, [ 'access_token' => static::$valid_access_token ] );
		$I->seeResponseCodeIs( 200 );
		$I->seeResponseIsJson();

		// Check Last Access is Updated.
		$api_key_data = get_option( 'tec_zapier_api_key_4689db48b24f0ac42f3f0d8fe027b8f28f63f262b9fc2f73736dfa91b4045425' );
		$I->test_et_last_access( $api_key_data);

		// Check Last Access is Updated for Endpoint.
		$endpoint_details = get_option( '_tec_zapier_endpoint_details_refunded_orders' );
		$I->test_et_last_access( $endpoint_details);
	}

	/**
	 * @test
	 */
	public function it_should_return_valid_with_access_token_but_with_invalid_order_id( Restv1_etTester $I ) {
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
	public function it_should_return_valid_with_access_token_but_with_invalid_post_type( Restv1_etTester $I ) {
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
	public function it_should_process_tc_refunded_orders_queue( Restv1_etTester $I ) {
		$event      = $this->generate_event( $this->mock_date_value );
		$order_id_1 = $this->generate_tc_order_and_refund_it( $event );
		$order_id_2 = $this->generate_tc_order_and_refund_it( $event );
		$order_ids  = [ $order_id_1, $order_id_2 ];
		$this->setup_refunded_orders_queue( $I, $order_ids );
		$this->setup_api_key_pair( $I );

		$I->sendGET( $this->refunded_orders_url, [ 'access_token' => static::$valid_access_token ] );
		$I->seeResponseCodeIs( 200 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		foreach ( $order_ids as $order_id ) {
			$id_found = false;

			// Loop through the response array to find the attendee ID
			foreach ( $response as $order ) {
				if ( isset( $order['order_id'] ) && $order['order_id'] === $order_id ) {
					$id_found = true;
					break;
				}
			}

			// Assert that the ID was found
			$I->assertTrue( $id_found, "Order ID $order_id was not found in the response" );
		}
	}

	/**
	 * @test
	 * @skip Strauss does not update FakerPHP causing 'Psr\Container\ContainerInterface' not found
	 */
	public function it_should_process_edd_refunded_orders_queue( Restv1_etTester $I ) {
		$event      = $this->generate_event( $this->mock_date_value );
		$order_id_1 = $this->generate_edd_order_and_refund_it( $event );
		$order_id_2 = $this->generate_edd_order_and_refund_it( $event );
		$order_ids  = [ $order_id_1, $order_id_2 ];
		$this->setup_refunded_orders_queue( $I, $order_ids );
		$this->setup_api_key_pair( $I );

		$I->sendGET( $this->refunded_orders_url, [ 'access_token' => static::$valid_access_token ] );
		$I->seeResponseCodeIs( 200 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		foreach ( $order_ids as $order_id ) {
			$id_found = false;

			// Loop through the response array to find the attendee ID
			foreach ( $response as $order ) {
				if ( isset( $order['order_id'] ) && $order['order_id'] === (string) $order_id ) {
					$id_found = true;
					break;
				}
			}

			// Assert that the ID was found
			$I->assertTrue( $id_found, "Order ID $order_id was not found in the response" );
		}
	}

	/**
	 * @test
	 * @skip Strauss does not update FakerPHP causing 'Psr\Container\ContainerInterface' not found
	 */
	public function it_should_process_woo_refunded_orders_queue( Restv1_etTester $I ) {
		$event      = $this->generate_event( $this->mock_date_value );
		$order_id_1 = $this->generate_woo_order( $event );
		$order_id_2 = $this->generate_woo_order( $event );
		$order_ids  = [ $order_id_1, $order_id_2 ];
		$this->setup_refunded_orders_queue( $I, $order_ids );
		$this->setup_api_key_pair( $I );

		$I->sendGET( $this->refunded_orders_url, [ 'access_token' => static::$valid_access_token ] );
		$I->seeResponseCodeIs( 200 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		foreach ( $order_ids as $order_id ) {
			$id_found = false;

			// Loop through the response array to find the attendee ID
			foreach ( $response as $order ) {
				if ( isset( $order['order_id'] ) && $order['order_id'] === (string) $order_id ) {
					$id_found = true;
					break;
				}
			}

			// Assert that the ID was found
			$I->assertTrue( $id_found, "Order ID $order_id was not found in the response" );
		}
	}

	/**
	 * @test
	 * @skip TC cart does not clear between tests causing, "Trying to access array offset on value of type null".
	 */
	public function it_should_process_all_provider_refunded_orders_queue( Restv1_etTester $I ) {
		$event      = $this->generate_event( $this->mock_date_value );
		$order_id_1 = $this->generate_woo_order_and_refund_it( $event );
		$order_id_2 = $this->generate_tc_order_and_refund_it( $event );
		$order_id_3 = $this->generate_edd_order_and_refund_it( $event );
		$order_ids  = [ $order_id_1, $order_id_2, $order_id_3 ];
		$this->setup_refunded_orders_queue( $I, $order_ids );
		$this->setup_api_key_pair( $I );

		$I->sendGET( $this->refunded_orders_url, [ 'access_token' => static::$valid_access_token ] );
		$I->seeResponseCodeIs( 200 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		foreach ( $order_ids as $order_id ) {
			$id_found = false;

			// Loop through the response array to find the attendee ID
			foreach ( $response as $order ) {
				if ( isset( $order['order_id'] ) && $order['order_id'] === $order_id ) {
					$id_found = true;
					break;
				}
			}

			// Assert that the ID was found
			$I->assertTrue( $id_found, "Order ID $order_id was not found in the response" );
		}
	}

	/**
	 * @test
	 */
	public function it_should_return_404_when_endpoint_disabled( Restv1_etTester $I ) {
		$endpoint = [
			'id'           => 'refunded_orders',
			'display_name' => 'refunded orders',
			'type'         => 'queue',
			'last_access'  => '',
			'count'        => 0,
			'enabled'      => false,
		];
		$this->disable_endpoint( $I, '_tec_zapier_endpoint_details_refunded_orders', $endpoint );
		$this->setup_api_key_pair( $I );
		$I->sendGET( $this->refunded_orders_url, [ 'access_token' => static::$valid_access_token ] );
		$I->seeResponseCodeIs( 404 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}
}
