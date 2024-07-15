<?php

namespace TEC\Event_Automator\Power_Automate\REST\V1\Endpoints\Queue;

use TEC\Event_Automator\Tests\Testcases\REST\V1\BaseRestPowerAutomateCest;
use Restv1Tester;
use Tribe__Events__Main as TEC;

class UpdatedEventCest extends BaseRestPowerAutomateCest {

	/**
	 * @test
	 */
	public function it_should_return_error_when_missing_token_parameters( Restv1Tester $I ) {
		$I->sendGET( $this->updated_events_url );
		$I->seeResponseCodeIs( 401 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function it_should_return_invalid_when_access_token_contains_unverified_consumer_id_and_secret( Restv1Tester $I ) {
		$I->sendGET( $this->updated_events_url, [ 'access_token' => static::$invalid_access_token ] );
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
		$I->sendGET( $this->updated_events_url, [ 'access_token' => static::$valid_access_token ] );
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
		$I->haveHttpHeader( 'eva-app-name', 'integration-the-events-calendar' );
		$I->sendGET( $this->updated_events_url, [ 'access_token' => static::$valid_access_token ] );
		$I->seeResponseCodeIs( 200 );
		$I->seeResponseIsJson();

		// Check Last Access is Updated.
		$api_key_data = get_option( 'tec_power_automate_connection_6a8dc385e71764bac6b22ba6ccac07ba17e3904509f6d60f712e00ba080befd8' );
		$I->test_tec_last_access( $api_key_data);

		// Check Last Access is Updated for Endpoint.
		$endpoint_details = get_option( '_tec_power_automate_endpoint_details_updated_events' );
		$I->test_tec_last_access( $endpoint_details);
	}

	/**
	 * @test
	 */
	public function it_should_return_valid_with_access_token_but_with_invalid_event_id( Restv1Tester $I ) {
		$invalid_id = [ 'post_id' ];
		$this->setup_updated_event_queue( $I, $invalid_id );
		$this->setup_api_key_pair( $I );
		$I->sendGET( $this->updated_events_url, [ 'access_token' => static::$valid_access_token ] );
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
		$this->setup_updated_event_queue( $I, $invalid_post_type );

		$this->setup_api_key_pair( $I );
		$I->sendGET( $this->updated_events_url, [ 'access_token' => static::$valid_access_token ] );
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
		$this->setup_updated_event_queue( $I, $event_ids );
		$this->setup_api_key_pair( $I );

		$event_ids[] = 'no-updated-events';
		foreach ( $event_ids as $event_id ) {
			$I->sendGET( $this->updated_events_url, [ 'access_token' => static::$valid_access_token ] );
			$I->seeResponseCodeIs( 200 );
			$I->seeResponseIsJson();
			$response = json_decode( $I->grabResponse(), true );
			if ( $event_id === 'no-updated-events' ) {
				$I->assertArrayHasKey( 'id', $response['events'] );
				$I->assertEquals( $event_id, $response['events']['id'] );
			} else {
				$I->assertArrayHasKey( 'id', $response['events'][0] );
				$id_arr = explode( '|', $response['events'][0]['id'] );
				$I->assertEquals( $event_id, $id_arr[0] );
			}
		}
	}

	/**
	 * @test
	 */
	public function it_should_return_404_when_endpoint_disabled( Restv1Tester $I ) {
		$endpoint = [
			'id'           => 'updated_events',
			'display_name' => 'Updated Events',
			'type'         => 'queue',
			'last_access'  => '',
			'count'        => 0,
			'enabled'      => false,
		];
		$this->disable_endpoint( $I, '_tec_power_automate_endpoint_details_updated_events', $endpoint );
		$this->setup_api_key_pair( $I );
		$I->sendGET( $this->updated_events_url, [ 'access_token' => static::$valid_access_token ] );
		$I->seeResponseCodeIs( 404 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}
}
