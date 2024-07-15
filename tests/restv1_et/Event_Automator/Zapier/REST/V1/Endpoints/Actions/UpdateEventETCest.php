<?php

namespace TEC\Event_Automator\Zapier\REST\V1\Endpoints\Actions;

use TEC\Event_Automator\Tests\Testcases\REST\V1\BaseRestETCest;
use Restv1_etTester;

class UpdateEventETCest extends BaseRestETCest {

	/**
	 * @test
	 */
	public function it_should_return_404_when_missing_tec( Restv1_etTester $I ) {
		$params = [
			'access_token' => static::$valid_access_token,
			'id'           => 1242,
			'title'        => 'Zapier Actions Mark 001',
		];

		$this->setup_api_key_pair( $I );
		$I->sendPOST( $this->update_events_url, $params );
		$I->seeResponseCodeIs( 404 );
	}
}
