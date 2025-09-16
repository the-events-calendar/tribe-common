<?php

namespace TEC\Event_Automator\Tests\Testcases\REST\V1;

use Restv1_etTester;
use tad\Codeception\SnapshotAssertions\SnapshotAssertions;

class BaseRestETPowerAutomateCest {

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
	protected $new_events_url;

	/**
	 * @var string
	 */
	protected $updated_events_url;

	/**
	 * @var string
	 */
	protected $canceled_events_url;

	/**
	 * @var string
	 */
	protected $create_events_url;

	/**
	 * @var string
	 */
	protected $attendees_url;

	/**
	 * @var string
	 */
	protected $updated_attendees_url;

	/**
	 * @var string
	 */
	protected $checkin_url;

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
	protected static $api_secret = '7143bdd88e82abc7bc1af0bd74702d4b8ea94a477b2d8705fea15e4fa92e15721b598020afb2e49a0f3ae9b17afeade1155c7656ba7d07b5e5edf80ec419c2164b2767cb701c26002c411cd6aa8310948aa45d42064ac21db760b551b27fd8aa48fe02a4729af985babd1dda53c107df4c86b3a73e560de6a7d95a0ca1245711';

	/**
	 * Invalid access token for REST authorization.
	 *
	 * @var string
	 */
	protected static $invalid_access_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vd29yZHByZXNzLnRlc3QiLCJpYXQiOjE2ODQzMjgwOTEsIm5iZiI6MTY4NDMyODA5MSwiZGF0YSI6eyJjb25zdW1lcl9pZCI6IjQ2ODlkYjQ4YjI0ZjBhYzQyZjNmMGQ4ZmUwMjdiOGYyOGY2M2YyNjJiOWZjMmY3MzczNmRmYTkxYjQwNDU0MjUiLCJjb25zdW1lcl9zZWNyZXQiOiJhOWQyNDdmMjJmZWIyMGY1YWMwOWI4MzliNWIzY2YyZWEzNjFjMTA4MWU3ZTU3YTQyZWQ1YzFmM2U3YjEyMjJlIiwiYXBwX25hbWUiOiJhdXRvbWF0ZWQtdGVzdHMifX0.GmfYyPvlvGkP9zn37WI1PfQJhctwCKVk7A51L34ZuM4';

	/**
	 * Valid access token for REST authorization.
	 *
	 * @var string
	 */
	protected static $valid_access_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vd29yZHByZXNzLnRlc3QiLCJpYXQiOjE2ODQzMzczNzYsIm5iZiI6MTY4NDMzNzM3NiwiZGF0YSI6eyJjb25zdW1lcl9pZCI6ImNpXzVjMmRhNDliYzU3Y2JiMjY5ZTBiYzM2MTBmOTY2MjUxMWUzNjU1MTkiLCJjb25zdW1lcl9zZWNyZXQiOiJja184ZDA0OGIzY2JiY2UwY2ZkOTUyYmVhYzgzNmM2OWJmODEzYzFjYzljIiwiYXBwX25hbWUiOiJhdXRvbWF0ZWQtdGVzdHMifX0.x4vdiVpBtCYH_RDmGVrZtMFhzgkCnNfgeuFV9PoxfBM';

	public function _before( Restv1_etTester $I ) {
		$this->site_url          = $I->grabSiteUrl();
		$this->wp_rest_url       = $this->site_url . '/wp-json/wp/v2/';
		$this->rest_url          = $this->site_url . '/wp-json/tribe/power-automate/v1/';
		$this->documentation_url = $this->rest_url . 'doc';
		$this->factory           = $I->factory();

		// Triggers.
		$this->new_events_url        = $this->rest_url . 'new-events';
		$this->updated_events_url    = $this->rest_url . 'updated-events';
		$this->canceled_events_url   = $this->rest_url . 'canceled-events';
		$this->attendees_url         = $this->rest_url . 'attendees';
		$this->updated_attendees_url = $this->rest_url . 'updated-attendees';
		$this->checkin_url           = $this->rest_url . 'checkin';
		$this->orders_url            = $this->rest_url . 'orders';
		$this->refunded_orders_url   = $this->rest_url . 'refunded-orders';
		//Actions
		$this->create_events_url     = $this->rest_url . 'create-events';

		$I->haveOptionInDatabase( 'tec_automator_power_automate_secret_key', static::$api_secret );

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
	 * @param \Restv1_etTester $I
	 *
	 * @return string Method identifier for snapshot.
	 */
	protected function setup_api_key_pair( Restv1_etTester $I ) {
		$key_pair_list = [
		    '6a8dc385e71764bac6b22ba6ccac07ba17e3904509f6d60f712e00ba080befd8' => [ 'name' => 'EA Tester' ],
		];
		$I->haveOptionInDatabase( 'tec_power_automate_connections', $key_pair_list );

		$api_key_data = [
		'consumer_id' => '6a8dc385e71764bac6b22ba6ccac07ba17e3904509f6d60f712e00ba080befd8', // ci_5c2da49bc57cbb269e0bc3610f9662511e365519
		    'consumer_secret' => 'a95c0ee3e17e86433ef12aad87a04d630c13f003cf7dfe27c887b99c45f358f2', // ck_8d048b3cbbce0cfd952beac836c69bf813c1cc9c
		    'has_pair' => 1,
			'name' => 'EA Tester',
		    'permissions' => 'read',
		    'last_access'=> '-',
		    'user_id' => 1,
		];
		$I->haveOptionInDatabase( 'tec_power_automate_connection_6a8dc385e71764bac6b22ba6ccac07ba17e3904509f6d60f712e00ba080befd8', $api_key_data );
	}

	/**
	 * Setup Invalid Api Key Pair in database.
	 *
	 * @param \Restv1_etTester $I
	 *
	 * @return string Method identifier for snapshot.
	 */
	protected function setup_invalid_api_key_pair( Restv1_etTester $I ) {
		$key_pair_list = [
		    '7421993746242c4cb71a3ace79a331f211a032d03e52eae63af70733d123d69d' => [ 'name' => 'EA Tester' ],
		];
		$I->haveOptionInDatabase( 'tec_power_automate_connections', $key_pair_list );

		$api_key_data = [
		'consumer_id' => '7421993746242c4cb71a3ace79a331f211a032d03e52eae63af70733d123d69d', // ci_1c2da49bc57cbb269e0bc3610f9662511e365519
		    'consumer_secret' => '43892a566bbbaf0bb17a24e7a085ccb15102e0ea168d67d6eb3b3ef613f16362', //ck_1d048b3cbbce0cfd952beac836c69bf813c1cc9c
		    'has_pair' => 1,
		    'name' => 'EA Tester',
		    'permissions' => 'read',
		    'last_access'=> '-',
		    'user_id' => 1,
		];
		$I->haveOptionInDatabase( 'tec_power_automate_connection_7421993746242c4cb71a3ace79a331f211a032d03e52eae63af70733d123d69d', $api_key_data );
	}

	/**
	 * Setup New Event Queue.
	 *
	 * @param \Restv1_etTester $I
	 */
	protected function setup_new_event_queue( Restv1_etTester $I, array $value = []  ) {
		$I->haveTransientInDatabase( '_tec_power_automate_queue_new_events', $value );
	}

	/**
	 * Setup Updated Event Queue.
	 *
	 * @param \Restv1_etTester $I
	 */
	protected function setup_updated_event_queue( Restv1_etTester $I, array $value = []  ) {
		$I->haveTransientInDatabase( '_tec_power_automate_queue_updated_events', $value );
	}

	/**
	 * Setup Canceled Event Queue.
	 *
	 * @param \Restv1_etTester $I
	 */
	protected function setup_canceled_event_queue( Restv1_etTester $I, array $value = []  ) {
		$I->haveTransientInDatabase( '_tec_power_automate_queue_canceled_events', $value );
	}

	/**
	 * Disable Endpoint.
	 *
	 * @param \Restv1_etTester $I
	 */
	protected function disable_endpoint( Restv1_etTester $I, $endpoint_option, $endpoint_details ) {
		$I->haveOptionInDatabase( $endpoint_option, $endpoint_details );
	}

	/**
	 * Setup Attendees Queue.
	 *
	 * @param \Restv1_etTester $I
	 */
	protected function setup_attendees_queue( Restv1_etTester $I, array $value = []  ) {
		$I->haveTransientInDatabase( '_tec_power_automate_queue_attendees', $value );
	}

	/**
	 * Setup Updated  Attendees Queue.
	 *
	 * @param \Restv1_etTester $I
	 */
	protected function setup_updated_attendees_queue( Restv1_etTester $I, array $value = []  ) {
		$I->haveTransientInDatabase( '_tec_power_automate_queue_updated_attendees', $value );
	}

	/**
	 * Setup Attendees Queue.
	 *
	 * @param \Restv1_etTester $I
	 */
	protected function setup_checkin_queue( Restv1_etTester $I, array $value = []  ) {
		$I->haveTransientInDatabase( '_tec_power_automate_queue_checkin', $value );
	}

	/**
	 * Setup Orders Queue.
	 *
	 * @param \Restv1_etTester $I
	 */
	protected function setup_orders_queue( Restv1_etTester $I, array $value = []  ) {
		$I->haveTransientInDatabase( '_tec_power_automate_queue_orders', $value );
	}

	/**
	 * Setup Refunded Orders Queue.
	 *
	 * @param \Restv1_etTester $I
	 */
	protected function setup_refunded_orders_queue( Restv1_etTester $I, array $value = []  ) {
		$I->haveTransientInDatabase( '_tec_power_automate_queue_refunded_orders', $value );
	}
}
