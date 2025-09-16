<?php

namespace TEC\Event_Automator\Zapier\REST\V1\Endpoints;

use TEC\Event_Automator\Tests\Testcases\REST\V1\BaseRestETCest;
use Restv1_etTester;
use tad\Codeception\SnapshotAssertions\SnapshotAssertions;
use TEC\Event_Automator\Tests\Traits\Create_Events;
use TEC\Event_Automator\Tests\Traits\Create_Attendees;

class CheckinCest extends BaseRestETCest {

	use SnapshotAssertions;
	use Create_Events;
	use Create_Attendees;

	/**
	 * @inheritdoc
	 */
	protected static $current_test_url = 'checkin';

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
		$endpoint_details = get_option( '_tec_zapier_endpoint_details_checkin' );
		$I->test_et_last_access( $endpoint_details);
	}

	/**
	 * @test
	 */
	public function it_should_return_valid_with_access_token_but_with_invalid_attendee_id( Restv1_etTester $I ) {
		$invalid_id = [ 'post_id' ];
		$this->setup_checkin_queue( $I, $invalid_id );
		$this->setup_api_key_pair( $I );
		$I->sendGET( $this->checkin_url, [ 'access_token' => static::$valid_access_token ] );
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
		$this->setup_checkin_queue( $I, $invalid_post_type );

		$this->setup_api_key_pair( $I );
		$I->sendGET( $this->checkin_url, [ 'access_token' => static::$valid_access_token ] );
		$I->seeResponseCodeIs( 200 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function it_should_process_attendees_queue( Restv1_etTester $I ) {
		$event             = $this->generate_event( $this->mock_date_value );
		$created_attendees = $this->generate_multiple_rsvp_attendees( $event );
		$attendee_ids      = array_map( function ( $attendee ) {
			tribe( 'tickets.rsvp' )->checkin( $attendee->ID, true );
			return (int) $attendee->ID;
		}, $created_attendees );
		$this->setup_checkin_queue( $I, $attendee_ids );
		$this->setup_api_key_pair( $I );

		$I->sendGET( $this->checkin_url, [ 'access_token' => static::$valid_access_token ] );
		$I->seeResponseCodeIs( 200 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		foreach ( $attendee_ids as $attendee_id ) {
			$id_found = false;

			// Loop through the response array to find the attendee ID
			foreach ( $response as $attendee ) {
				if ( ! isset( $attendee['id'] ) ) {
					continue;
				}

				$id_arr = explode( '|', $attendee['id'] );
				if ( $id_arr[0] === (string) $attendee_id ) {
					$id_found = true;
					break;
				}
			}

			// Assert that the ID was found
			$I->assertTrue( $id_found, "Attendee ID $attendee_id was not found in the response" );
		}
	}

	/**
	 * @test
	 */
	public function it_should_return_404_when_endpoint_disabled( Restv1_etTester $I ) {
		$endpoint = [
			'id'           => 'checkin',
			'display_name' => 'Checkin',
			'type'         => 'queue',
			'last_access'  => '',
			'count'        => 0,
			'enabled'      => false,
		];
		$this->disable_endpoint( $I, '_tec_zapier_endpoint_details_checkin', $endpoint );
		$this->setup_api_key_pair( $I );
		$I->sendGET( $this->checkin_url, [ 'access_token' => static::$valid_access_token ] );
		$I->seeResponseCodeIs( 404 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}
}
