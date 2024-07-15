<?php
/**
 * Power_Automate's create events endpoint utilizes the Single Events Endpoint to create events with a different authentication.
 * These tests cover the parameters for this endpoint: the-events-calendar/tests/restv1/EventInsertionCest.php
 *
 * @since TBD
 *
 * @package TEC\Event_Automator\Power_Automate\REST\V1\Endpoints\Actions
 */
namespace TEC\Event_Automator\Power_Automate\REST\V1\Endpoints\Actions;

use TEC\Event_Automator\Tests\Testcases\REST\V1\BaseRestPowerAutomateCest;
use Restv1Tester;

class CreateEventCest extends BaseRestPowerAutomateCest {

	/**
	 * @inheritdoc
	 */
	protected static $current_test_url = 'create-events';

	/**
	 * @test
	 */
	public function it_should_return_error_when_using_get_request( Restv1Tester $I ) {
		$I->sendPOST( $this->create_events_url );
		$I->seeResponseCodeIs( 400 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function it_should_return_error_when_missing_parameters( Restv1Tester $I ) {
		$I->sendPOST( $this->create_events_url );
		$I->seeResponseCodeIs( 400 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function it_should_return_invalid_when_access_token_contains_unverified_consumer_id_and_secret( Restv1Tester $I ) {
		$post_parameters = [
			'access_token' => static::$invalid_access_token,
			'title'        => 'Power_Automate Actions Mark 001',
			'start_date'   => '2023-04-21 08:00:00',
			'end_date'     => '2023-04-21 09:00:00',
		];
		$I->sendPOST( $this->create_events_url, $post_parameters );
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
			'title'        => 'An event title',
			'start_date'   => 'tomorrow 9am',
			'end_date'     => 'tomorrow 11am',
		];

		if ( false === $data[1] ) {
			unset( $params[ $data[0] ] );
		} else {
			$params[ $data[0] ] = $data[1];
		}

		$I->sendPOST( $this->create_events_url, $params );

		$I->seeResponseCodeIs( 400 );
	}

	/**
	 * @test
	 */
	public function it_should_return_created_event( Restv1Tester $I ) {
		$params = [
			'access_token' => static::$valid_access_token,
			'title'        => 'Power Automate Actions Mark 001',
			'start_date'   => '2023-04-21 08:00:00',
			'end_date'     => '2023-04-21 09:00:00',
		];

		$this->setup_api_key_pair( $I );
		$I->sendPOST( $this->create_events_url, $params );
		$I->seeResponseCodeIs( 201 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$I->assertEquals( $params['title'], $response['title'], 'The created event title does not match the expected title.' );
		$I->assertEquals( $params['start_date'], $response['start_date'], 'The created event start date does not match the expected start date.' );
		$I->assertEquals( $params['end_date'], $response['end_date'], 'The created event end date does not match the expected end date.' );
	}

	/**
	 * @test
	 */
	public function it_should_return_valid_with_access_token_contains_verified_api_key_pair_and_last_access_is_updated( Restv1Tester $I ) {
		$params = [
			'access_token' => static::$valid_access_token,
			'title'        => 'Power Automate Actions Mark 001',
			'start_date'   => '2023-04-21 08:00:00',
			'end_date'     => '2023-04-21 09:00:00',
		];

		$this->setup_api_key_pair( $I );
		$I->haveHttpHeader( 'eva-app-name', 'integration-the-events-calendar' );
		$I->sendPOST( $this->create_events_url, $params );
		$I->seeResponseCodeIs( 201 );
		$I->seeResponseIsJson();

		// Check Last Access is Updated.
		$api_key_data = get_option( 'tec_power_automate_connection_6a8dc385e71764bac6b22ba6ccac07ba17e3904509f6d60f712e00ba080befd8' );
		$I->test_tec_last_access( $api_key_data);

		// Check Last Access is Updated for Endpoint.
		$endpoint_details = get_option( '_tec_power_Automate_endpoint_details_create_events' );
		$I->test_tec_last_access( $endpoint_details);
	}

	/**
	 * @test
	 */
	public function it_should_return_404_when_endpoint_disabled( Restv1Tester $I ) {
		$endpoint = [
			'id'           => 'create_events',
			'display_name' => 'Create Events',
			'type'         => 'action',
			'last_access'  => '',
			'count'        => 0,
			'enabled'      => false,
		];
		$this->disable_endpoint( $I, '_tec_power_automate_endpoint_details_create_events', $endpoint );
		$this->setup_api_key_pair( $I );
		$I->sendPOST( $this->create_events_url, [ 'access_token' => static::$valid_access_token ] );
		$I->seeResponseCodeIs( 404 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}
}
