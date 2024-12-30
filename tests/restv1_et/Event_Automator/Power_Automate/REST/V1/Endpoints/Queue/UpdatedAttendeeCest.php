<?php

namespace TEC\Event_Automator\Power_Automate\REST\V1\Endpoints\Queue;

use tad\Codeception\SnapshotAssertions\SnapshotAssertions;
use Restv1_etTester;
use TEC\Event_Automator\Tests\Testcases\REST\V1\BaseRestETPowerAutomateCest;
use TEC\Event_Automator\Tests\Traits\Create_attendees;
use TEC\Event_Automator\Tests\Traits\Create_events;

class UpdatedAttendeeCest extends BaseRestETPowerAutomateCest {

	use SnapshotAssertions;
	use Create_Events;
	use Create_Attendees;

	/**
	 * @test
	 */
	public function it_should_return_error_when_missing_token_parameters( Restv1_etTester $I ) {
		$I->sendGET( $this->updated_attendees_url );
		$I->seeResponseCodeIs( 401 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function it_should_return_invalid_when_access_token_contains_unverified_consumer_id_and_secret( Restv1_etTester $I ) {
		$I->sendGET( $this->updated_attendees_url, [ 'access_token' => static::$invalid_access_token ] );
		$I->seeResponseCodeIs( 401 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function it_should_return_valid_when_access_token_contains_verified_consumer_id_and_secret_but_no_event_msg( Restv1_etTester $I ) {
		$this->setup_api_key_pair( $I );
		$I->sendGET( $this->updated_attendees_url, [ 'access_token' => static::$valid_access_token ] );
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
		$I->haveHttpHeader( 'eva-app-name', 'integration-event-tickets' );
		$I->sendGET( $this->updated_attendees_url, [ 'access_token' => static::$valid_access_token ] );
		$I->seeResponseCodeIs( 200 );
		$I->seeResponseIsJson();

		// Check Last Access is Updated.
		$api_key_data = get_option( 'tec_power_automate_connection_6a8dc385e71764bac6b22ba6ccac07ba17e3904509f6d60f712e00ba080befd8' );
		$I->test_et_last_access( $api_key_data);

		// Check Last Access is Updated for Endpoint.
		$endpoint_details = get_option( '_tec_power_automate_endpoint_details_updated_attendees' );
		$I->test_et_last_access( $endpoint_details);
	}

	/**
	 * @test
	 */
	public function it_should_return_valid_with_access_token_but_with_invalid_attendee_id( Restv1_etTester $I ) {
		$invalid_id = [ 'post_id' ];
		$this->setup_updated_attendees_queue( $I, $invalid_id );
		$this->setup_api_key_pair( $I );
		$I->sendGET( $this->updated_attendees_url, [ 'access_token' => static::$valid_access_token ] );
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
		$this->setup_updated_attendees_queue( $I, $invalid_post_type );

		$this->setup_api_key_pair( $I );
		$I->sendGET( $this->updated_attendees_url, [ 'access_token' => static::$valid_access_token ] );
		$I->seeResponseCodeIs( 200 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function it_should_process_updated_attendees_queue( Restv1_etTester $I ) {
		$event             = $this->generate_event( $this->mock_date_value );
		$created_attendees = $this->generate_rsvp_attendee_updated_it( $event );
		$attendee_ids      = array_map( function ( $attendee ) {
			return (int) $attendee->ID;
		}, [ $created_attendees ] );
		$this->setup_updated_attendees_queue( $I, $attendee_ids );
		$this->setup_api_key_pair( $I );

		$attendee_ids[] = 'no-updated-attendees';
		foreach ( $attendee_ids as $attendee_id ) {
			$I->sendGET( $this->updated_attendees_url, [ 'access_token' => static::$valid_access_token ] );
			$I->seeResponseCodeIs( 200 );
			$I->seeResponseIsJson();
			$response = json_decode( $I->grabResponse(), true );
			if ( $attendee_id === 'no-updated-attendees' ) {
				$I->assertArrayHasKey( 'id', $response['attendees'] );
				$I->assertEquals( $attendee_id, $response['attendees']['id'] );
			} else {
				$I->assertArrayHasKey( 'id', $response['attendees'][0] );
				if ( ! isset( $response['attendees'][0] ) ) {
					continue;
				}

				$id_arr = explode( '|', $response['attendees'][0]['id'] );
				$I->assertEquals( $attendee_id, $id_arr[0] );
			}
		}
	}

	/**
	 * @test
	 */
	public function it_should_return_404_when_endpoint_disabled( Restv1_etTester $I ) {
		$endpoint = [
			'id'           => 'updated_attendees',
			'display_name' => 'Updated Attendees',
			'type'         => 'queue',
			'last_access'  => '',
			'count'        => 0,
			'enabled'      => false,
		];
		$this->disable_endpoint( $I, '_tec_power_automate_endpoint_details_updated_attendees', $endpoint );
		$this->setup_api_key_pair( $I );
		$I->sendGET( $this->updated_attendees_url, [ 'access_token' => static::$valid_access_token ] );
		$I->seeResponseCodeIs( 404 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}
}
