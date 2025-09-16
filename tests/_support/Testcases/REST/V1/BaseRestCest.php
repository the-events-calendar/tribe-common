<?php

namespace TEC\Event_Automator\Tests\Testcases\REST\V1;

use Restv1Tester;
use tad\Codeception\SnapshotAssertions\SnapshotAssertions;

class BaseRestCest {

	use SnapshotAssertions;

	/**
	 * @var string The site full URL to the homepage.
	 */
	protected $site_url;

	/**
	 * @var string The site full URL to the REST API root.
	 */
	protected $rest_url;

	/**
	 * @var string
	 */
	protected $authorize_url;

	/**
	 * @var string
	 */
	protected $new_events_url;

	/**
	 * @var string
	 */
	protected $canceled_events_url;

	/**
	 * @var string
	 */
	protected $updated_events_url;

	/**
	 * @var string
	 */
	protected $attendees_url;

	/**
	 * @var string
	 */
	protected $checkin_url;

	/**
	 * @var string
	 */
	protected $updated_attendees_url;

	/**
	 * @var string
	 */
	protected $orders_url;

	/**
	 * @var string
	 */
	protected $refunded_orders_url;

	/**
	 * @var string
	 */
	protected $create_events_url;

	/**
	 * @var string
	 */
	protected $find_attendees_url;

	/**
	 * @var string
	 */
	protected $find_events_url;

	/**
	 * @var string
	 */
	protected $find_tickets_url;

	/**
	 * @var string
	 */
	protected $update_events_url;

	/**
	 * @var string
	 */
	protected $documentation_url;

	/**
	 * @var \tad\WPBrowser\Module\WPLoader\FactoryStore
	 */
	protected $factory;

	/**
	 * @var string
	 */
	protected $wp_rest_url;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected static $current_test_url;

	/**
	 * When mocking the `date` function this is the value that will be used to generate the date in place of the real
	 * one.
	 *
	 * @var string
	 */
	protected $mock_date_value = '2019-01-01 09:00:00';

	/**
	 * Secret Key for JWT authentication.
	 *
	 * @var string
	 */
	protected static $api_secret = '5470c97cd2657af4036a389b12e151985197bc514f30102c8918cb0f94c0051698e43e0054eeaba6072f1e15032029210648b08666ae650f7ce8486a55444096e06dfb5fc0502aad4684d916cd68bd343e0ab937a5a34c4edf2122480dff0f806df7aaee0b88215d096aa29a56841f53c67b61938b512aaeb5690bda906d42b5';


	/**
	 * Invalid access token for REST authorization.
	 *
	 * @var string
	 */
	protected static $invalid_access_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vd29yZHByZXNzLnRlc3QiLCJpYXQiOjE2ODQzMjMxNTIsIm5iZiI6MTY4NDMyMzE1MiwiZGF0YSI6eyJjb25zdW1lcl9pZCI6ImNpXzFjMmRhNDliYzU3Y2JiMjY5ZTBiYzM2MTBmOTY2MjUxMWUzNjU1MTkiLCJjb25zdW1lcl9zZWNyZXQiOiJja18xZDA0OGIzY2JiY2UwY2ZkOTUyYmVhYzgzNmM2OWJmODEzYzFjYzljIiwiYXBwX25hbWUiOiJ6YXBpZXItZXZlbnQtdGlja2V0cyJ9fQ.sflPCFl1iKvdysZ19H1RHoZ75Gcq1ijflSOuCuUftbs';

	/**
	 * Valid access token for REST authorization.
	 *
	 * @var string
	 */
	protected static $valid_access_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vd29yZHByZXNzLnRlc3QiLCJpYXQiOjE3MjcyNjcxOTgsIm5iZiI6MTcyNzI2NzE5OCwiZGF0YSI6eyJjb25zdW1lcl9pZCI6ImNpXzVjMmRhNDliYzU3Y2JiMjY5ZTBiYzM2MTBmOTY2MjUxMWUzNjU1MTkiLCJjb25zdW1lcl9zZWNyZXQiOiJja184ZDA0OGIzY2JiY2UwY2ZkOTUyYmVhYzgzNmM2OWJmODEzYzFjYzljIiwiYXBwX25hbWUiOiJ6YXBpZXItdGhlLWV2ZW50cy1jYWxlbmRhciJ9fQ.O7MUko22kQ-aHVs44e4IOBWa1k5xvryTm46ipBTdHJQ';

	public function _before( Restv1Tester $I ) {
		$this->site_url              = $I->grabSiteUrl();
		$this->wp_rest_url           = $this->site_url . '/wp-json/wp/v2/';
		$this->rest_url              = $this->site_url . '/wp-json/tribe/zapier/v1/';
		$this->documentation_url     = $this->rest_url . 'doc';
		$this->factory               = $I->factory();
		$this->authorize_url         = $this->rest_url . 'authorize';
		// Triggers.
		$this->canceled_events_url   = $this->rest_url . 'canceled-events';
		$this->new_events_url        = $this->rest_url . 'new-events';
		$this->updated_events_url    = $this->rest_url . 'updated-events';
		$this->attendees_url         = $this->rest_url . 'attendees';
		$this->updated_attendees_url = $this->rest_url . 'updated-attendees';
		$this->checkin_url           = $this->rest_url . 'checkin';
		$this->orders_url            = $this->rest_url . 'orders';
		$this->refunded_orders_url   = $this->rest_url . 'refunded-orders';
		// Actions.
		$this->create_events_url  = $this->rest_url . 'create-events';
		$this->find_attendees_url = $this->rest_url . 'find-attendees';
		$this->find_events_url    = $this->rest_url . 'find-events';
		$this->find_tickets_url   = $this->rest_url . 'find-tickets';
		$this->update_events_url  = $this->rest_url . 'update-events';

		$I->haveOptionInDatabase( 'tec_automator_zapier_secret_key', static::$api_secret );

		wp_cache_flush();

		// reset the user to visitor before each test
		wp_set_current_user( 0 );
	}

	/**
	 * Set name for snapshot.
	 *
	 * @param string $name Method identifier for snapshot.
	 */
	protected function setName( $name ) {
		$this->name = $name;
	}

	/**
	 * Get name for snapshot.
	 *
	 * @return string Method identifier for snapshot.
	 */
	protected function getName() {
		return $this->name;
	}

	/**
	 * Setup Api Key Pair in database.
	 *
	 * @param \Restv1Tester $I
	 *
	 * @return string Method identifier for snapshot.
	 */
	protected function setup_api_key_pair( Restv1Tester $I ) {
		$key_pair_list = [
		    '4689db48b24f0ac42f3f0d8fe027b8f28f63f262b9fc2f73736dfa91b4045425' => [ 'name' => 'EA Tester' ],
		];
		$I->haveOptionInDatabase( 'tec_zapier_api_keys', $key_pair_list );

		$api_key_data = [
			'consumer_id' => '4689db48b24f0ac42f3f0d8fe027b8f28f63f262b9fc2f73736dfa91b4045425', // ci_5c2da49bc57cbb269e0bc3610f9662511e365519
			'consumer_secret' => 'a9d247f22feb20f5ac09b839b5b3cf2ea361c1081e7e57a42ed5c1f3e7b1222e', // ck_8d048b3cbbce0cfd952beac836c69bf813c1cc9c
		    'has_pair' => 1,
		    'name' => 'EA Tester',
		    'permissions' => 'read',
		    'last_access'=> '-',
		    'user_id' => 1,
			'app_name' => 'zapier-the-events-calendar',
		];
		$I->haveOptionInDatabase( 'tec_zapier_api_key_4689db48b24f0ac42f3f0d8fe027b8f28f63f262b9fc2f73736dfa91b4045425', $api_key_data );
	}

	/**
	 * Setup Invalid Api Key Pair in database.
	 *
	 * @param \Restv1Tester $I
	 *
	 * @return string Method identifier for snapshot.
	 */
	protected function setup_invalid_api_key_pair( Restv1Tester $I ) {
		$key_pair_list = [
		    '7421993746242c4cb71a3ace79a331f211a032d03e52eae63af70733d123d69d' => [ 'name' => 'EA Tester' ],
		];
		$I->haveOptionInDatabase( 'tec_zapier_api_keys', $key_pair_list );

		$api_key_data = [
		'consumer_id' => '7421993746242c4cb71a3ace79a331f211a032d03e52eae63af70733d123d69d', // ci_1c2da49bc57cbb269e0bc3610f9662511e365519
		    'consumer_secret' => '43892a566bbbaf0bb17a24e7a085ccb15102e0ea168d67d6eb3b3ef613f16362', //ck_1d048b3cbbce0cfd952beac836c69bf813c1cc9c
		    'has_pair' => 1,
		    'name' => 'EA Tester',
		    'permissions' => 'read',
		    'last_access'=> '-',
		    'user_id' => 1,
		];
		$I->haveOptionInDatabase( 'tec_zapier_api_key_7421993746242c4cb71a3ace79a331f211a032d03e52eae63af70733d123d69d', $api_key_data );
	}

	/**
	 * Setup Canceled Event Queue.
	 *
	 * @param \Restv1Tester $I
	 */
	protected function setup_canceled_event_queue( Restv1Tester $I, array $value = []  ) {
		$I->haveTransientInDatabase( '_tec_zapier_queue_canceled_events', $value );
	}

	/**
	 * Setup New Event Queue.
	 *
	 * @param \Restv1Tester $I
	 */
	protected function setup_new_event_queue( Restv1Tester $I, array $value = []  ) {
		$I->haveTransientInDatabase( '_tec_zapier_queue_new_events', $value );
	}

	/**
	 * Disable Endpoint.
	 *
	 * @param \Restv1Tester $I
	 */
	protected function disable_endpoint( Restv1Tester $I, $endpoint_option, $endpoint_details ) {
		$I->haveOptionInDatabase( $endpoint_option, $endpoint_details );
	}

	/**
	 * Setup Updated Event Queue.
	 *
	 * @param \Restv1Tester $I
	 */
	protected function setup_updated_event_queue( Restv1Tester $I, array $value = []  ) {
		$I->haveTransientInDatabase( '_tec_zapier_queue_updated_events', $value );
	}

	/**
	 * Setup Attendee Queue.
	 *
	 * @param \Restv1Tester $I
	 */
	protected function setup_attendee_queue( Restv1Tester $I, array $value = []  ) {
		$I->haveTransientInDatabase( '_tec_zapier_queue_attendees', $value );
	}

	/**
	 * Setup Updated Attendee Queue.
	 *
	 * @param \Restv1Tester $I
	 */
	protected function setup_updated_attendees_queue( Restv1Tester $I, array $value = []  ) {
		$I->haveTransientInDatabase( '_tec_zapier_queue_updated_attendees', $value );
	}

	/**
	 * Setup Checkin Queue.
	 *
	 * @param \Restv1Tester $I
	 */
	protected function setup_checkin_queue( Restv1Tester $I, array $value = []  ) {
		$I->haveTransientInDatabase( '_tec_zapier_queue_checkin', $value );
	}

	/**
	 * Setup Orders Queue.
	 *
	 * @param \Restv1Tester $I
	 */
	protected function setup_orders_queue( Restv1Tester $I, array $value = []  ) {
		$I->haveTransientInDatabase( '_tec_zapier_queue_orders', $value );
	}

	/**
	 * Setup Refunded Orders Queue.
	 *
	 * @param \Restv1Tester $I
	 */
	protected function setup_refunded_orders_queue( Restv1Tester $I, array $value = []  ) {
		$I->haveTransientInDatabase( '_tec_zapier_queue_refunded_orders', $value );
	}
}
