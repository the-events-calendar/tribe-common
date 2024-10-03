<?php
/**
 * Zapier's find attendees endpoint utilizes the Attendee Archive Endpoint to find attendees with a different authentication.
 * These tests cover the parameters for this endpoint: event-tickets/tests/restv1/TicketArchiveByAttendeeCest.php
 *
 * @since 6.0.0
 *
 * @package TEC\Event_Automator\Zapier\REST\V1\Endpoints\Actions
 */
namespace TEC\Event_Automator\Zapier\REST\V1\Endpoints\Actions;

use TEC\Event_Automator\Tests\Testcases\REST\V1\BaseRestETCest;
use Restv1_etTester;
use TEC\Event_Automator\Tests\Traits\Create_attendees;
use TEC\Event_Automator\Tests\Traits\Create_events;

class FindAttendeesCest extends BaseRestETCest {
	use Create_events;
	use Create_attendees;
	/**
	 * @test
	 */
	public function it_should_return_error_when_using_get_request( Restv1_etTester $I ) {
		$I->sendGET( $this->find_attendees_url );
		$I->seeResponseCodeIs( 401 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function it_should_return_error_when_missing_parameters( Restv1_etTester $I ) {
		$I->sendGET( $this->find_attendees_url );
		$I->seeResponseCodeIs( 401 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function it_should_return_invalid_when_access_token_contains_unverified_consumer_id_and_secret( Restv1_etTester $I ) {
		$search_parameters = [
			'access_token' => static::$invalid_access_token,
		];
		$I->sendGET( $this->find_attendees_url, $search_parameters );
		$I->seeResponseCodeIs( 401 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}

	/**
	 * It should mark bad request if required param is missing or bad
	 *
	 * @test
	 *
	 * @example ["title", false, 401]
	 * @example ["title", "", 401]
	 * @example ["after", false, 401]
	 * @example ["after", "", 400]
	 * @example ["after", "not a strtotime parsable string", 400]
	 * @example ["before", false, 401]
	 * @example ["before", "", 400]
	 * @example ["before", "not a strtotime parsable string", 400]
	 */
	public function it_should_mark_bad_request_if_required_param_is_missing_or_bad( Restv1_etTester $I, \Codeception\Example $data ) {
		$params = [
			'access_token' => static::$valid_access_token,
			'after'        => 'tomorrow 9am',
			'before'       => 'tomorrow 11am',
		];

		if ( false === $data[1] ) {
			unset( $params[ $data[0] ] );
		} else {
			$params[ $data[0] ] = $data[1];
		}

		$I->sendGET( $this->find_attendees_url, $params );

		$I->seeResponseCodeIs( $data[2] );
	}

	/**
	 * @test
	 */
	public function it_should_return_found_attendees( Restv1_etTester $I ) {
		$event             = $this->generate_event( $this->mock_date_value );
		$created_attendees = $this->generate_multiple_rsvp_attendees( $event );

		$this->setup_api_key_pair( $I );

		$params = [
			'access_token' => static::$valid_access_token,
			//'start_date'   => '2023-04-21 00:00:00',
			//'end_date'     => '2023-04-21 23:00:00',
		];
		$I->sendGET( $this->find_attendees_url, $params );
		$I->seeResponseCodeIs( 200 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );
		$I->assertNotEmpty( $response[0]['attendees'], 'Attendees should not be empty!' );
		$I->assertCount( 3, $response[0]['attendees'], 'Attendees count should be 3
		, ' . count( $response[0]['attendees'] ) . ' found!' );
	}

	/**
	 * @test
	 */
	public function it_should_return_checkedin_attendees( Restv1_etTester $I ) {
		$event             = $this->generate_event( $this->mock_date_value );
		$created_attendees = $this->generate_multiple_rsvp_attendees( $event );
		$checkedin_attendees = $this->generate_rsvp_attendee( $event, [ 'check_in' => 1 ] );

		$this->setup_api_key_pair( $I );

		$params = [
			'access_token' => static::$valid_access_token,
			'checkedin' => 1,
		];
		$I->sendGET( $this->find_attendees_url, $params );
		$I->seeResponseCodeIs( 200 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );
		$I->assertNotEmpty( $response[0]['attendees'], 'Attendees should not be empty!' );
		$I->assertCount( 1, $response[0]['attendees'], 'Attendees count should be 1
		, ' . count( $response[0]['attendees'] ) . ' found!' );
	}

	/**
	 * @test
	 */
	public function it_should_return_valid_with_access_token_contains_verified_api_key_pair_and_last_access_is_updated( Restv1_etTester $I ) {
		$event             = $this->generate_event( $this->mock_date_value );
		$created_attendees = $this->generate_multiple_rsvp_attendees( $event );

		$params = [
			'access_token' => static::$valid_access_token,
		];

		$this->setup_api_key_pair( $I );
		$I->sendGET( $this->find_attendees_url, $params );
		$I->seeResponseCodeIs( 200 );
		$I->seeResponseIsJson();

		// Check Last Access is Updated.
		$api_key_data = get_option( 'tec_zapier_api_key_4689db48b24f0ac42f3f0d8fe027b8f28f63f262b9fc2f73736dfa91b4045425' );
		$I->test_et_last_access( $api_key_data);

		// Check Last Access is Updated for Endpoint.
		$endpoint_details = get_option( '_tec_zapier_endpoint_details_find_attendees' );
		$I->test_et_last_access( $endpoint_details);
	}

	/**
	 * @test
	 */
	public function it_should_return_404_when_endpoint_disabled( Restv1_etTester $I ) {
		$endpoint = [
			'id'           => 'find_attendees',
			'display_name' => 'Find Attendees',
			'type'         => 'action',
			'last_access'  => '',
			'count'        => 0,
			'enabled'      => false,
		];
		$this->disable_endpoint( $I, '_tec_zapier_endpoint_details_find_attendees', $endpoint );
		$this->setup_api_key_pair( $I );
		$I->sendGET( $this->find_attendees_url, [ 'access_token' => static::$valid_access_token ] );
		$I->seeResponseCodeIs( 404 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}
}
