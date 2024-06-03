<?php

namespace TEC\Event_Automator\Power_Automate\REST\V1\Endpoints\Actions;

use TEC\Event_Automator\Tests\Testcases\REST\V1\BaseRestETCest;
use Restv1_etTester;

class CreateEventETCest extends BaseRestETCest {

	/**
	 * @test
	 */
	public function it_should_return_404_when_missing_tec( Restv1_etTester $I ) {
		$params = [
			'access_token' => static::$pa_valid_access_token,
			'title'        => 'Power Automate Actions Mark 001',
			'start_date'   => '2023-04-21 08:00:00',
			'end_date'     => '2023-04-21 09:00:00',
		];

		$this->setup_power_automate_connection( $I );
		$I->sendPOST( $this->pa_create_events_url, $params );
		$I->seeResponseCodeIs( 404 );
	}
}
