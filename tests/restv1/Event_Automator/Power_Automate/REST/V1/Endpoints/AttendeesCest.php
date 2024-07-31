<?php

namespace TEC\Event_Automator\Power_Automate\REST\V1\Endpoints;

use TEC\Event_Automator\Tests\Testcases\REST\V1\BaseRestPowerAutomateCest;
use Restv1Tester;
use tad\Codeception\SnapshotAssertions\SnapshotAssertions;
use TEC\Event_Automator\Tests\Traits\Create_events;
use TEC\Event_Automator\Tests\Traits\Create_attendees;

class AttendeesCest extends BaseRestPowerAutomateCest {

	use SnapshotAssertions;
	use Create_events;
	use Create_attendees;

	/**
	 * @inheritdoc
	 */
	protected static $current_test_url = 'attendeess';

	/**
	 * @test
	 */
	public function it_should_return_error_when_missing_token_parameters( Restv1Tester $I ) {
		$I->sendGET( $this->attendees_url );
		$I->seeResponseCodeIs( 401 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function it_should_return_invalid_when_access_token_contains_unverified_consumer_id_and_secret( Restv1Tester $I ) {
		$I->sendGET( $this->attendees_url, [ 'access_token' => static::$invalid_access_token ] );
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
		$I->sendGET( $this->attendees_url, [ 'access_token' => static::$valid_access_token ] );
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
		$I->sendGET( $this->attendees_url, [ 'access_token' => static::$valid_access_token ] );
		$I->seeResponseCodeIs( 200 );
		$I->seeResponseIsJson();

		// Check Last Access is Updated.
		$api_key_data = get_option( 'tec_power_automate_connection_6a8dc385e71764bac6b22ba6ccac07ba17e3904509f6d60f712e00ba080befd8' );
		$I->test_last_access( $api_key_data);

		// Check Last Access is Updated for Endpoint.
		$endpoint_details = get_option( '_tec_power_automate_endpoint_details_attendees' );
		$I->test_last_access( $endpoint_details);
	}

	/**
	 * @test
	 */
	public function it_should_return_valid_with_access_token_but_with_invalid_attendee_id( Restv1Tester $I ) {
		$invalid_id = [ 'post_id' ];
		$this->setup_attendees_queue( $I, $invalid_id );
		$this->setup_api_key_pair( $I );
		$I->sendGET( $this->attendees_url, [ 'access_token' => static::$valid_access_token ] );
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
		$this->setup_attendees_queue( $I, $invalid_post_type );

		$this->setup_api_key_pair( $I );
		$I->sendGET( $this->attendees_url, [ 'access_token' => static::$valid_access_token ] );
		$I->seeResponseCodeIs( 200 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function it_should_process_attendees_queue( Restv1Tester $I ) {
		$event             = $this->generate_event( $this->mock_date_value );
		$created_attendees = $this->generate_multiple_rsvp_attendees( $event );
		$attendee_ids      = array_map( function ( $attendee ) {
			return (int) $attendee->ID;
		}, $created_attendees );
		$this->setup_attendees_queue( $I, $attendee_ids );
		$this->setup_api_key_pair( $I );

		$attendee_ids[] = 'no-new-attendees';
		foreach ( $attendee_ids as $attendee_id ) {
			$I->sendGET( $this->attendees_url, [ 'access_token' => static::$valid_access_token ] );
			$I->seeResponseCodeIs( 200 );
			$I->seeResponseIsJson();
			$response = json_decode( $I->grabResponse(), true );
			if ( $attendee_id === 'no-new-attendees' ) {
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
	public function it_should_return_404_when_endpoint_disabled( Restv1Tester $I ) {
		$endpoint = [
			'id'           => 'attendees',
			'display_name' => 'Attendees',
			'type'         => 'queue',
			'last_access'  => '',
			'count'        => 0,
			'enabled'      => false,
		];
		$this->disable_endpoint( $I, '_tec_power_automate_endpoint_details_attendees', $endpoint );
		$this->setup_api_key_pair( $I );
		$I->sendGET( $this->attendees_url, [ 'access_token' => static::$valid_access_token ] );
		$I->seeResponseCodeIs( 404 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}
}
