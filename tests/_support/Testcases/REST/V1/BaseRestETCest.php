<?php

namespace TEC\Event_Automator\Tests\Testcases\REST\V1;

use Restv1_etTester;
use tad\Codeception\SnapshotAssertions\SnapshotAssertions;

class BaseRestETCest {

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
	 * @var string The site full URL to the REST API root for Power Automate.
	 */
	protected $pa_rest_url;

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
	protected $pa_create_events_url;

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
	protected static $api_secret = 'db9d46d3673759a348f5fcee9781e940be1751a051a7ad611f822210fbe445f756a18e1aced4fe6d3c1854c0fcc1c2a5413b907e43e5440f986ca39d46e1c727f2464358ab371c3c566b8b0f626f90df58437ee41037697be641f3f0e232466a50f7c0a5280b8950f22dc8e79a7b759d3299a9cc434096c752dc5bad2b7a611d';

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
	protected static $valid_access_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vd29yZHByZXNzLnRlc3QiLCJpYXQiOjE3MjcyNzIwMDksIm5iZiI6MTcyNzI3MjAwOSwiZGF0YSI6eyJjb25zdW1lcl9pZCI6ImNpXzVjMmRhNDliYzU3Y2JiMjY5ZTBiYzM2MTBmOTY2MjUxMWUzNjU1MTkiLCJjb25zdW1lcl9zZWNyZXQiOiJja184ZDA0OGIzY2JiY2UwY2ZkOTUyYmVhYzgzNmM2OWJmODEzYzFjYzljIiwiYXBwX25hbWUiOiJ6YXBpZXItZXZlbnQtdGlja2V0cyJ9fQ.lK0atfbhpQdV4atczqeZWQU1nAHLp-K_3qviNhkxNu4';

	/**
	 * Secret Key for Power Automate JWT authentication.
	 *
	 * @var string
	 */
	protected static $pa_api_secret = '7143bdd88e82abc7bc1af0bd74702d4b8ea94a477b2d8705fea15e4fa92e15721b598020afb2e49a0f3ae9b17afeade1155c7656ba7d07b5e5edf80ec419c2164b2767cb701c26002c411cd6aa8310948aa45d42064ac21db760b551b27fd8aa48fe02a4729af985babd1dda53c107df4c86b3a73e560de6a7d95a0ca1245711';

	/**
	 * Invalid access token for Power Automate REST authorization.
	 *
	 * @var string
	 */
	protected static $pa_invalid_access_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vd29yZHByZXNzLnRlc3QiLCJpYXQiOjE2ODQzMjgwOTEsIm5iZiI6MTY4NDMyODA5MSwiZGF0YSI6eyJjb25zdW1lcl9pZCI6IjQ2ODlkYjQ4YjI0ZjBhYzQyZjNmMGQ4ZmUwMjdiOGYyOGY2M2YyNjJiOWZjMmY3MzczNmRmYTkxYjQwNDU0MjUiLCJjb25zdW1lcl9zZWNyZXQiOiJhOWQyNDdmMjJmZWIyMGY1YWMwOWI4MzliNWIzY2YyZWEzNjFjMTA4MWU3ZTU3YTQyZWQ1YzFmM2U3YjEyMjJlIiwiYXBwX25hbWUiOiJhdXRvbWF0ZWQtdGVzdHMifX0.GmfYyPvlvGkP9zn37WI1PfQJhctwCKVk7A51L34ZuM4';

	/**
	 * Valid access token for Power Automate REST authorization.
	 *
	 * @var string
	 */
	protected static $pa_valid_access_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vd29yZHByZXNzLnRlc3QiLCJpYXQiOjE2ODQzMzczNzYsIm5iZiI6MTY4NDMzNzM3NiwiZGF0YSI6eyJjb25zdW1lcl9pZCI6ImNpXzVjMmRhNDliYzU3Y2JiMjY5ZTBiYzM2MTBmOTY2MjUxMWUzNjU1MTkiLCJjb25zdW1lcl9zZWNyZXQiOiJja184ZDA0OGIzY2JiY2UwY2ZkOTUyYmVhYzgzNmM2OWJmODEzYzFjYzljIiwiYXBwX25hbWUiOiJhdXRvbWF0ZWQtdGVzdHMifX0.x4vdiVpBtCYH_RDmGVrZtMFhzgkCnNfgeuFV9PoxfBM';

	public function _before( Restv1_etTester $I ) {
		$this->site_url          = $I->grabSiteUrl();
		$this->wp_rest_url       = $this->site_url . '/wp-json/wp/v2/';
		$this->rest_url          = $this->site_url . '/wp-json/tribe/zapier/v1/';
		$this->pa_rest_url       = $this->site_url . '/wp-json/tribe/power-automate/v1/';
		$this->documentation_url = $this->rest_url . 'doc';
		$this->factory           = $I->factory();
		$this->authorize_url     = $this->rest_url . 'authorize';
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
		$this->create_events_url    = $this->rest_url . 'create-events';
		$this->find_attendees_url   = $this->rest_url . 'find-attendees';
		$this->find_events_url      = $this->rest_url . 'find-events';
		$this->find_tickets_url     = $this->rest_url . 'find-tickets';
		$this->update_events_url    = $this->rest_url . 'update-events';
		$this->pa_create_events_url = $this->pa_rest_url . 'create-events';

		$I->haveOptionInDatabase( 'tec_automator_zapier_secret_key', static::$api_secret );
		$I->haveOptionInDatabase( 'tec_automator_power_automate_secret_key', static::$pa_api_secret );

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
			'4689db48b24f0ac42f3f0d8fe027b8f28f63f262b9fc2f73736dfa91b4045425' => [ 'name' => 'EA Tester' ],
		];
		$I->haveOptionInDatabase( 'tec_zapier_api_keys', $key_pair_list );

		$api_key_data = [
			'consumer_id'     => '4689db48b24f0ac42f3f0d8fe027b8f28f63f262b9fc2f73736dfa91b4045425',
			'consumer_secret' => 'a9d247f22feb20f5ac09b839b5b3cf2ea361c1081e7e57a42ed5c1f3e7b1222e',
			'has_pair'        => 1,
			'name'            => 'EA Tester',
			'permissions'     => 'read',
			'last_access'     => '-',
			'user_id'         => 1,
		];
		$I->haveOptionInDatabase( 'tec_zapier_api_key_4689db48b24f0ac42f3f0d8fe027b8f28f63f262b9fc2f73736dfa91b4045425', $api_key_data );
	}

	/**
	 * Setup Power Automate Connection in database.
	 *
	 * @param \Restv1_etTester $I
	 *
	 * @return string Method identifier for snapshot.
	 */
	protected function setup_power_automate_connection( Restv1_etTester $I ) {
		$key_pair_list = [
			'7421993746242c4cb71a3ace79a331f211a032d03e52eae63af70733d123d69d' => [ 'name' => 'EA Tester' ],
		];
		$I->haveOptionInDatabase( 'tec_power_automate_connections', $key_pair_list );

		$api_key_data = [
			'consumer_id'     => '7421993746242c4cb71a3ace79a331f211a032d03e52eae63af70733d123d69d', // ci_1c2da49bc57cbb269e0bc3610f9662511e365519
			'consumer_secret' => '43892a566bbbaf0bb17a24e7a085ccb15102e0ea168d67d6eb3b3ef613f16362', //ck_1d048b3cbbce0cfd952beac836c69bf813c1cc9c
			'has_pair'        => 1,
			'name'            => 'EA Tester',
			'permissions'     => 'read',
			'last_access'     => '-',
			'user_id'         => 1,
		];
		$I->haveOptionInDatabase( 'tec_power_automate_connection_7421993746242c4cb71a3ace79a331f211a032d03e52eae63af70733d123d69d', $api_key_data );
	}

	/**
	 * Setup Canceled Event Queue.
	 *
	 * @param \Restv1_etTester $I
	 */
	protected function setup_canceled_event_queue( Restv1_etTester $I, array $value = [] ) {
		$I->haveTransientInDatabase( '_tec_zapier_queue_canceled_events', $value );
	}

	/**
	 * Setup New Event Queue.
	 *
	 * @param \Restv1_etTester $I
	 */
	protected function setup_new_event_queue( Restv1_etTester $I, array $value = [] ) {
		$I->haveTransientInDatabase( '_tec_zapier_queue_new_events', $value );
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
	 * Setup Updated Event Queue.
	 *
	 * @param \Restv1_etTester $I
	 */
	protected function setup_updated_event_queue( Restv1_etTester $I, array $value = [] ) {
		$I->haveTransientInDatabase( '_tec_zapier_queue_updated_events', $value );
	}

	/**
	 * Setup Attendee Queue.
	 *
	 * @param \Restv1_etTester $I
	 */
	protected function setup_attendee_queue( Restv1_etTester $I, array $value = [] ) {
		$I->haveTransientInDatabase( '_tec_zapier_queue_attendees', $value );
	}

	/**
	 * Setup Updated Attendee Queue.
	 *
	 * @param \Restv1_etTester $I
	 */
	protected function setup_updated_attendees_queue( Restv1_etTester $I, array $value = [] ) {
		$I->haveTransientInDatabase( '_tec_zapier_queue_updated_attendees', $value );
	}

	/**
	 * Setup Checkin Queue.
	 *
	 * @param \Restv1_etTester $I
	 */
	protected function setup_checkin_queue( Restv1_etTester $I, array $value = [] ) {
		$I->haveTransientInDatabase( '_tec_zapier_queue_checkin', $value );
	}

	/**
	 * Setup Orders Queue.
	 *
	 * @param \Restv1_etTester $I
	 */
	protected function setup_orders_queue( Restv1_etTester $I, array $value = [] ) {
		$I->haveTransientInDatabase( '_tec_zapier_queue_orders', $value );
	}

	/**
	 * Setup Refunded Orders Queue.
	 *
	 * @param \Restv1_etTester $I
	 */
	protected function setup_refunded_orders_queue( Restv1_etTester $I, array $value = [] ) {
		$I->haveTransientInDatabase( '_tec_zapier_queue_refunded_orders', $value );
	}
}
