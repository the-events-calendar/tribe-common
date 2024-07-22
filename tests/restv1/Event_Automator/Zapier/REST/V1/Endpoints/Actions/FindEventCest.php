<?php
/**
 * Zapier's find events endpoint utilizes the Event Archive Endpoint to find events with a different authentication.
 * These tests cover the parameters for this endpoint: the-events-calendar/tests/restv1/EventArchiveCest.php
 *
 * @since 6.0.0
 *
 * @package TEC\Event_Automator\Zapier\REST\V1\Endpoints\Actions
 */
namespace TEC\Event_Automator\Zapier\REST\V1\Endpoints\Actions;

use TEC\Event_Automator\Tests\Testcases\REST\V1\BaseRestCest;
use Restv1Tester;

class FindEventCest extends BaseRestCest {

	/**
	 * @test
	 */
	public function it_should_return_error_when_using_get_request( Restv1Tester $I ) {
		$I->sendGET( $this->find_events_url );
		$I->seeResponseCodeIs( 400 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function it_should_return_error_when_missing_parameters( Restv1Tester $I ) {
		$I->sendGET( $this->find_events_url );
		$I->seeResponseCodeIs( 400 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function it_should_return_invalid_when_access_token_contains_unverified_consumer_id_and_secret( Restv1Tester $I ) {
		$search_parameters = [
			'access_token' => static::$invalid_access_token,
			'start_date'   => '2023-04-21 00:00:00',
			'end_date'     => '2023-04-21 23:00:00',
		];
		$I->sendGET( $this->find_events_url, $search_parameters );
		$I->seeResponseCodeIs( 400 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}

	/**
	 * It should mark bad request if required param is missing or bad
	 *
	 * @test
	 *
	 * @example ["title", false]
	 * @example ["title", ""]
	 * @example ["start_date", false]
	 * @example ["start_date", ""]
	 * @example ["start_date", "not a strtotime parsable string"]
	 * @example ["end_date", false]
	 * @example ["end_date", ""]
	 * @example ["end_date", "not a strtotime parsable string"]
	 */
	public function it_should_mark_bad_request_if_required_param_is_missing_or_bad( Restv1Tester $I, \Codeception\Example $data ) {
		$params = [
			'access_token' => static::$valid_access_token,
			'start_date'   => 'tomorrow 9am',
			'end_date'     => 'tomorrow 11am',
		];

		if ( false === $data[1] ) {
			unset( $params[ $data[0] ] );
		} else {
			$params[ $data[0] ] = $data[1];
		}

		$I->sendGET( $this->find_events_url, $params );

		$I->seeResponseCodeIs( 400 );
	}

	/**
	 * @test
	 */
	public function it_should_return_created_event( Restv1Tester $I ) {
		$events1    = $I->haveManyEventsInDatabase( 3, [ 'when' => '2023-04-21 00:01:00' ] );
		$events2    = $I->haveManyEventsInDatabase( 2 );

		// Resave events so they appear in the Find Events Endpoint. This is necessary because the Find Events Endpoint was not picking them up otherwise.
		$this->setup_api_key_pair( $I );
		foreach ( $events1 as $event ) {
			$params = [
				'access_token' => static::$valid_access_token,
				'id'           => $event,
				'title'        => 'Zapier Actions Mark ' . $event,
				'start_date'   => '2023-04-21 08:00:00',
				'end_date'     => '2023-04-21 09:00:00',
			];

			$I->sendPOST( $this->update_events_url, $params );
		}

		foreach ( $events2 as $event ) {
			$params = [
				'access_token' => static::$valid_access_token,
				'id'           => $event,
				'title'        => 'Zapier Actions Mark ' . $event,
				'start_date'   => '+1 day',
				'end_date'     => '+1 day',
			];

			$I->sendPOST( $this->update_events_url, $params );
		}

		$params = [
			'access_token' => static::$valid_access_token,
			'start_date'   => '2023-04-21 00:00:00',
			'end_date'     => '2023-04-21 23:00:00',
		];
		$I->sendGET( $this->find_events_url, $params );
		$I->seeResponseCodeIs( 200 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );
		$I->assertNotEmpty( $response[0]['events'], 'Events should not be empty!' );
		$I->assertCount( 3, $response[0]['events'], 'Event count should be 3, ' . count( $response[0]['events'] ) . ' found!' );

		// Get Future Events.
		$params = [
			'access_token' => static::$valid_access_token,
		];
		$I->sendGET( $this->find_events_url, $params );
		$I->seeResponseCodeIs( 200 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );
		$I->assertNotEmpty( $response[0]['events'], 'Events should not be empty!' );
		$I->assertCount( 2, $response[0]['events'], 'Event count should be 3, ' . count( $response[0]['events'] ) . ' found!' );
	}

	/**
	 * @test
	 */
	public function it_should_return_valid_with_access_token_contains_verified_api_key_pair_and_last_access_is_updated( Restv1Tester $I ) {
		$I->haveManyEventsInDatabase( 3, [ 'when' => '2023-04-21' ]  );
		$params = [
			'access_token' => static::$valid_access_token,
			'start_date'   => '2023-04-21 00:00:00',
			'end_date'     => '2023-04-21 23:00:00',
		];

		$this->setup_api_key_pair( $I );
		$I->sendGET( $this->find_events_url, $params );
		$I->seeResponseCodeIs( 200 );
		$I->seeResponseIsJson();

		// Check Last Access is Updated.
		$api_key_data = get_option( 'tec_zapier_api_key_4689db48b24f0ac42f3f0d8fe027b8f28f63f262b9fc2f73736dfa91b4045425' );
		$I->test_last_access( $api_key_data);

		// Check Last Access is Updated for Endpoint.
		$endpoint_details = get_option( '_tec_zapier_endpoint_details_find_events' );
		$I->test_last_access( $endpoint_details);
	}

	/**
	 * @test
	 */
	public function it_should_return_404_when_endpoint_disabled( Restv1Tester $I ) {
		$endpoint = [
			'id'           => 'find_events',
			'display_name' => 'Find Events',
			'type'         => 'action',
			'last_access'  => '',
			'count'        => 0,
			'enabled'      => false,
		];
		$this->disable_endpoint( $I, '_tec_zapier_endpoint_details_find_events', $endpoint );
		$this->setup_api_key_pair( $I );
		$I->sendGET( $this->find_events_url, [ 'access_token' => static::$valid_access_token ] );
		$I->seeResponseCodeIs( 404 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}
}
