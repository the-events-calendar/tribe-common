<?php
/**
 * Zapier's find tickets endpoint utilizes the Attendee Archive Endpoint to find tickets with a different authentication.
 * These tests cover the parameters for this endpoint: event-tickets/tests/restv1/TicketArchiveSearchCestx.php
 *
 * @since   6.0.0
 *
 * @package TEC\Event_Automator\Zapier\REST\V1\Endpoints\Actions
 */

namespace TEC\Event_Automator\Zapier\REST\V1\Endpoints\Actions;

use TEC\Event_Automator\Tests\Testcases\REST\V1\BaseRestETCest;
use Restv1_etTester;
use TEC\Event_Automator\Tests\Traits\Create_attendees;
use TEC\Event_Automator\Tests\Traits\Create_events;

class FindTicketsCest extends BaseRestETCest {

	use Create_events;
	use Create_attendees;

	/*
	 * Attendee meta values.
	 *
	 * @since 6.0.0
	 *
	 * @var array<string> The values to use for attendee meta.
	 */
	protected array $field_values = [
		'dropdown-for-tests'                              => '2nd Option',
		'text-field'                                      => 'Test',
		'radio-field'                                     => 'radio2',
		'checkbox-field_3f268a535d76578d48af2d98a9b5aed7' => 'check1',
		'checkbox-field_e5058a61e22656b980153c4e10b46fa6' => 'check3',
		'dropdown'                                        => 'drop1',
		'email-field'                                     => 'support@tec.com',
		'telephone'                                       => '2020222222',
		'url-field'                                       => 'https:tec.com',
		'birthday-field'                                  => '2013-02-15',
		'date-field'                                      => '2023-07-26',
	];

	/**
	 * @test
	 */
	public function it_should_return_error_when_using_get_request( Restv1_etTester $I ) {
		$I->sendGET( $this->find_tickets_url );
		$I->seeResponseCodeIs( 401 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function it_should_return_error_when_missing_parameters( Restv1_etTester $I ) {
		$I->sendGET( $this->find_tickets_url );
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
		$I->sendGET( $this->find_tickets_url, $search_parameters );
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

		$I->sendGET( $this->find_tickets_url, $params );

		$I->seeResponseCodeIs( $data[2] );
	}

	/**
	 * @test
	 */
	public function it_should_return_found_tickets( Restv1_etTester $I ) {
		$overrides = [
			'full_name'     => 'Meta\'s Attendee',
			'attendee_meta' => $this->field_values,
		];

		$event = $this->generate_event( $this->mock_date_value );
		//$created_ticket  = $this->generate_woo_ticket_for_event( $event->ID );
		$attendee1       = $this->generate_woo_attendee( $event, $overrides, true );
		$event2          = $this->generate_event( $this->mock_date_value );
		$attendee2       = $this->generate_edd_attendee( $event, $overrides, true );
		$event3          = $this->generate_event( $this->mock_date_value );
		$created_ticket3 = $this->generate_woo_ticket_for_event( $event3->ID );

		$this->setup_api_key_pair( $I );

		$params = [
			'access_token' => static::$valid_access_token,
		];

		$I->sendGET( $this->find_tickets_url, $params );
		$I->seeResponseCodeIs( 200 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );
		$I->assertNotEmpty( $response[0]['tickets'], 'Tickets should not be empty!' );
		$I->assertCount( 3, $response[0]['tickets'], 'Tickets count should be 3
		, ' . count( $response[0]['tickets'] ) . ' found!' );
	}

	/**
	 * @test
	 */
	public function it_should_return_valid_with_access_token_contains_verified_api_key_pair_and_last_access_is_updated( Restv1_etTester $I ) {
		$event           = $this->generate_event( $this->mock_date_value );
		$created_tickets = $this->generate_woo_ticket_for_event( $event->ID );

		$params = [
			'access_token' => static::$valid_access_token,
		];

		$this->setup_api_key_pair( $I );
		$I->sendGET( $this->find_tickets_url, $params );
		$I->seeResponseCodeIs( 200 );
		$I->seeResponseIsJson();

		// Check Last Access is Updated.
		$api_key_data = get_option( 'tec_zapier_api_key_4689db48b24f0ac42f3f0d8fe027b8f28f63f262b9fc2f73736dfa91b4045425' );
		$I->test_et_last_access( $api_key_data );

		// Check Last Access is Updated for Endpoint.
		$endpoint_details = get_option( '_tec_zapier_endpoint_details_find_tickets' );
		$I->test_et_last_access( $endpoint_details );
	}

	/**
	 * @test
	 */
	public function it_should_return_404_when_endpoint_disabled( Restv1_etTester $I ) {
		$endpoint = [
			'id'           => 'find_tickets',
			'display_name' => 'Find Tickets',
			'type'         => 'action',
			'last_access'  => '',
			'count'        => 0,
			'enabled'      => false,
		];
		$this->disable_endpoint( $I, '_tec_zapier_endpoint_details_find_tickets', $endpoint );
		$this->setup_api_key_pair( $I );
		$I->sendGET( $this->find_tickets_url, [ 'access_token' => static::$valid_access_token ] );
		$I->seeResponseCodeIs( 404 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}
}
