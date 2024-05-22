<?php

namespace TEC\Event_Automator\Zapier;

use tad\Codeception\SnapshotAssertions\SnapshotAssertions;
use TEC\Event_Automator\Tests\Traits\With_Uopz;

class APITest extends \Codeception\TestCase\WPTestCase {

	use SnapshotAssertions;
	use With_Uopz;

	public function setUp() {
		// before
		parent::setUp();

		// Clear settings between tests.
		tribe_unset_var( \Tribe__Settings_Manager::OPTION_CACHE_VAR_NAME );
	}

	/**
	 * @test
	 */
	public function should_correctly_get_api_key_pairs_initial_state() {
		$api      = new Api( tribe( Actions::class ), tribe( Template_Modifications::class ) );
		$api_keys = $api->get_list_of_api_keys( true );

		$this->assertMatchesJsonSnapshot( json_encode( $api_keys, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function should_correctly_get_single_api_key_pair() {
		$this->set_fn_return( 'wp_create_nonce', '123123' );
		$mock_api_key_data = file_get_contents( codecept_data_dir( 'Zapier/API-Keys/200-account-valid-key-pair.json' ) );
		$api               = new Api( tribe( Actions::class ), tribe( Template_Modifications::class ) );
		$api_key_data      = json_decode( $mock_api_key_data, true );
		$api->set_api_key_by_id( $api_key_data );
		$api_keys = $api->get_list_of_api_keys( true );

		$this->assertMatchesJsonSnapshot( json_encode( $api_keys, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function should_correctly_get_multiple_api_key_pairs() {
		$this->set_fn_return( 'wp_create_nonce', '123123' );
		$api = new Api( tribe( Actions::class ), tribe( Template_Modifications::class ) );

		// Setup account 1.
		$mock_api_key_data = file_get_contents( codecept_data_dir( 'Zapier/API-Keys/200-account-valid-key-pair.json' ) );
		$api_key_data      = json_decode( $mock_api_key_data, true );
		$api->set_api_key_by_id( $api_key_data );

		// Setup account 2.
		$mock_account_data_2 = file_get_contents( codecept_data_dir( 'Zapier/API-Keys/200-account-valid-key-pair-2.json' ) );
		$api_key_data_2      = json_decode( $mock_account_data_2, true );
		$api->set_api_key_by_id( $api_key_data_2 );

		$api_keys = $api->get_list_of_api_keys( true );

		$this->assertMatchesJsonSnapshot( json_encode( $api_keys, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function should_correctly_get_user_dropdown() {
		$this->set_fn_return( 'wp_create_nonce', '123123' );
		$api = new Api( tribe( Actions::class ), tribe( Template_Modifications::class ) );
		$user_dropdown = $api->get_users_dropdown( true );

		$this->assertMatchesJsonSnapshot( json_encode( $user_dropdown, JSON_PRETTY_PRINT ) );
	}
}
