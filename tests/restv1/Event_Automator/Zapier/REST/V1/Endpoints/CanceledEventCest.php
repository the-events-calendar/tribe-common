<?php

namespace TEC\Event_Automator\Zapier\REST\V1\Endpoints;

use TEC\Event_Automator\Tests\Testcases\REST\V1\BaseRestCest;
use Restv1Tester;
use Tribe__Events__Main as TEC;

class CanceledEventCest extends BaseRestCest {

	/**
	 * @inheritdoc
	 */
	protected static $current_test_url = 'canceled-events';

	/**
	 * @test
	 */
	public function it_should_return_error_when_missing_token_parameters( Restv1Tester $I ) {
		$I->sendGET( static::$current_test_url );
		$I->seeResponseCodeIs( 400 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function it_should_return_invalid_when_access_token_contains_unverified_consumer_id_and_secret( Restv1Tester $I ) {
		$I->sendGET( static::$current_test_url, [ 'access_token' => static::$invalid_access_token ] );
		$I->seeResponseCodeIs( 400 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function it_should_return_valid_when_access_token_contains_verified_consumer_id_and_secret_but_no_event_msg( Restv1Tester $I ) {
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
	public function it_should_return_valid_with_access_token_contains_verified_api_key_pair_and_last_access_is_updated( Restv1Tester $I ) {
		$this->setup_api_key_pair( $I );
		$I->sendGET( static::$current_test_url, [ 'access_token' => static::$valid_access_token ] );
		$I->seeResponseCodeIs( 200 );
		$I->seeResponseIsJson();

		// Check Last Access is Updated.
		$api_key_data = get_option( 'tec_zapier_api_key_4689db48b24f0ac42f3f0d8fe027b8f28f63f262b9fc2f73736dfa91b4045425' );
		$I->test_tec_last_access( $api_key_data);

		// Check Last Access is Updated for Endpoint.
		$endpoint_details = get_option( '_tec_zapier_endpoint_details_canceled_events' );
		$I->test_tec_last_access( $endpoint_details);
	}

	/**
	 * @test
	 */
	public function it_should_return_valid_with_access_token_but_with_invalid_event_id( Restv1Tester $I ) {
		$invalid_id = [ 'post_id' ];
		$this->setup_canceled_event_queue( $I, $invalid_id );
		$this->setup_api_key_pair( $I );
		$I->sendGET( $this->canceled_events_url, [ 'access_token' => static::$valid_access_token ] );
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
		$this->setup_canceled_event_queue( $I, $invalid_post_type );

		$this->setup_api_key_pair( $I );
		$I->sendGET( $this->canceled_events_url, [ 'access_token' => static::$valid_access_token ] );
		$I->seeResponseCodeIs( 200 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function it_should_process_event_queue( Restv1Tester $I ) {
		$I->haveManyEventsInDatabase( 2 );
		$postsTable = $I->grabPostsTableName();
		$events     = $I->grabAllFromDatabase( $postsTable, 'ID', [ 'post_type' => TEC::POSTTYPE ] );
		$event_ids  = array_map( function ( $event ) {
			return (int) $event['ID'];
		}, $events );
		$this->setup_canceled_event_queue( $I, $event_ids );
		$this->setup_api_key_pair( $I );

/*		$event_ids[] = 'no-canceled-events';
		foreach ( $event_ids as $event_id ) {
			$I->sendGET( $this->canceled_events_url, [ 'access_token' => static::$valid_access_token ] );
			$I->seeResponseCodeIs( 200 );
			$I->seeResponseIsJson();
			$response = json_decode( $I->grabResponse(), true );
			$I->assertArrayHasKey( 'id', $response[0] );
			$id_arr = explode( '|', $response[0]['id'] );
			$I->assertEquals( $event_id, $id_arr[0] );
		}*/

		$I->sendGET( $this->canceled_events_url, [ 'access_token' => static::$valid_access_token ] );
		$I->seeResponseCodeIs( 200 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		foreach ( $event_ids as $event_id ) {
			$id_found = false;

			// Loop through the response array to find the event ID
			foreach ( $response as $event ) {
				if ( ! isset( $event['id'] ) ) {
					continue;
				}

				$id_arr = explode( '|', $event['id'] );
				if ( $id_arr[0] === (string) $event_id ) {
					$id_found = true;
					break;
				}
			}

			// Assert that the ID was found
			$I->assertTrue( $id_found, "Event ID $event_id was not found in the response" );
		}
	}

	/**
	 * @test
	 */
	public function it_should_return_404_when_endpoint_disabled( Restv1Tester $I ) {
		$endpoint = [
			'id'           => 'canceled_events',
			'display_name' => 'Canceled Events',
			'type'         => 'queue',
			'last_access'  => '',
			'count'        => 0,
			'enabled'      => false,
		];
		$this->disable_endpoint( $I, '_tec_zapier_endpoint_details_canceled_events', $endpoint );
		$this->setup_api_key_pair( $I );
		$I->sendGET( $this->canceled_events_url, [ 'access_token' => static::$valid_access_token ] );
		$I->seeResponseCodeIs( 404 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}
}
