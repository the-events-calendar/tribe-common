<?php

namespace TEC\Event_Automator\Zapier\REST\V1\Endpoints;

use TEC\Event_Automator\Tests\Testcases\REST\V1\BaseRestCest;
use Restv1Tester;

class AuthorizeCest extends BaseRestCest {

	/**
	 * @test
	 */
	public function it_should_return_error_when_missing_all_parameters( Restv1Tester $I ) {
		$I->sendGET( $this->authorize_url );
		$I->seeResponseCodeIs( 400 );
		$I->seeResponseIsJson();
	}

	/**
	 * @test
	 */
	public function it_should_return_error_when_missing_consumer_id( Restv1Tester $I ) {
		$I->sendGET( $this->authorize_url, [ 'consumer_secret' => '2n71n0v72ngp1h2912d2e2gi023f' ] );
		$I->seeResponseCodeIs( 400 );
		$I->seeResponseIsJson();
	}

	/**
	 * @test
	 */
	public function it_should_return_error_when_missing_consumer_secret( Restv1Tester $I ) {
		$I->sendGET( $this->authorize_url, [ 'consumer_id' => '2n71n0v72ngp1h2912d2e2gi023f' ] );
		$I->seeResponseCodeIs( 400 );
		$I->seeResponseIsJson();
	}

	/**
	 * @test
	 */
	public function it_should_return_error_when_sending_invalid_consumer_pair( Restv1Tester $I ) {
		$I->sendGET( $this->authorize_url, [ 'consumer_id' => '2n71n0v72ngp1h2912d2e2gi023f', 'consumer_secret' => '2n71n0v72ngp1h2912d2e2gi023f' ] );
		$I->seeResponseCodeIs( 400 );
		$I->seeResponseIsJson();
	}

	/**
	 * @test
	 */
	public function it_should_return_access_token_when_sending_valid_consumer_pair( Restv1Tester $I ) {
		$this->setup_api_key_pair( $I );
		$I->sendGET( $this->authorize_url, [ 'consumer_id' => 'ci_5c2da49bc57cbb269e0bc3610f9662511e365519', 'consumer_secret' => 'ck_8d048b3cbbce0cfd952beac836c69bf813c1cc9c', 'app_name' => 'zapier-the-events-calendar' ] );
		$I->seeResponseCodeIs( 200 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );
		$I->assertArrayHasKey( 'access_token', $response );

		// Check Last Access is Updated for API Key.
		$api_key_data = get_option( 'tec_zapier_api_key_4689db48b24f0ac42f3f0d8fe027b8f28f63f262b9fc2f73736dfa91b4045425' );
		$I->test_tec_last_access( $api_key_data);

		// Check Last Access is Updated for Endpoint.
		$endpoint_details = get_option( '_tec_zapier_endpoint_details_authorize' );
		$I->test_tec_last_access( $endpoint_details);
	}

	/**
	 * @test
	 */
	public function it_should_return_404_when_endpoint_disabled( Restv1Tester $I ) {
		$endpoint = [
			'id'           => 'authorize',
			'display_name' => 'Authorize',
			'type'         => 'authorize',
			'last_access'  => '',
			'count'        => 0,
			'enabled'      => false,
		];
		$this->disable_endpoint( $I, '_tec_zapier_endpoint_details_authorize', $endpoint );
		$this->setup_api_key_pair( $I );
		$I->sendGET( $this->authorize_url, [ 'access_token' => static::$valid_access_token ] );
		$I->seeResponseCodeIs( 404 );
		$I->seeResponseIsJson();
		$response = json_decode( $I->grabResponse(), true );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}
}
