<?php

namespace Tribe\tests\eva_integration\Zapier;

use tad\Codeception\SnapshotAssertions\SnapshotAssertions;
use TEC\Event_Automator\Zapier\Actions;
use TEC\Event_Automator\Zapier\Api;
use TEC\Event_Automator\Zapier\Template_Modifications;
use Tribe\Tests\Traits\With_Uopz;

class ApiTest extends \Codeception\TestCase\WPTestCase {
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
	public function should_mark_api_as_not_ready_when_no_api_key_loaded() {
		$api = new Api( tribe( Actions::class ), tribe( Template_Modifications::class ) );

		$this->assertFalse( $api->is_ready() );
	}

	/**
	 * @test
	 */
	public function should_mark_api_as_ready_when_api_key_loaded() {
		$this->set_fn_return( 'is_ssl', true );
		$mock_api_key_data = file_get_contents( codecept_data_dir( 'Zapier/API-Keys/200-account-valid-key-pair.json' ) );
		$api               = new Api( tribe( Actions::class ), tribe( Template_Modifications::class ) );
		$api_key_data      = json_decode( $mock_api_key_data, true );
		$api->set_api_key_by_id( $api_key_data );
		$api->load_api_key( $api_key_data, $api_key_data['consumer_secret'] );

		$this->assertTrue( $api->is_ready() );
	}

	/**
	 * @test
	 */
	public function should_mark_api_as_ready_when_api_key_loaded_by_id() {
		$this->set_fn_return( 'is_ssl', true );
		$mock_api_key_data = file_get_contents( codecept_data_dir( 'Zapier/API-Keys/200-account-valid-key-pair.json' ) );
		$api               = new Api( tribe( Actions::class ), tribe( Template_Modifications::class ) );
		$api_key_data      = json_decode( $mock_api_key_data, true );
		$api->set_api_key_by_id( $api_key_data );
		$api->load_api_key_by_id( $api_key_data['consumer_id'], $api_key_data['consumer_secret'] );

		$this->assertTrue( $api->is_ready() );
	}

	/**
	 * @test
	 */
	public function should_update_api_key_pair() {
		$this->set_fn_return( 'is_ssl', true );
		$mock_api_key_data = file_get_contents( codecept_data_dir( 'Zapier/API-Keys/200-account-valid-key-pair.json' ) );
		$api               = new Api( tribe( Actions::class ), tribe( Template_Modifications::class ) );
		$api_key_data      = json_decode( $mock_api_key_data, true );
		$api->set_api_key_by_id( $api_key_data );
		$saved_pair = $api->get_api_key_by_id( $api_key_data['consumer_id'] );

		$this->assertEquals( $saved_pair['name'], 'Automated Tests 1' );

		$new_name             = 'Updated Name';
		$api_key_data['name'] = $new_name;
		$api->set_api_key_by_id( $api_key_data );
		$updated_pair = $api->get_api_key_by_id( $api_key_data['consumer_id'] );

		$this->assertEquals( $updated_pair['name'], $new_name );
	}

	/**
	 * @test
	 */
	public function should_delete_api_key_pair() {
		$this->set_fn_return( 'is_ssl', true );
		$mock_api_key_data = file_get_contents( codecept_data_dir( 'Zapier/API-Keys/200-account-valid-key-pair.json' ) );
		$api               = new Api( tribe( Actions::class ), tribe( Template_Modifications::class ) );
		$api_key_data      = json_decode( $mock_api_key_data, true );
		$api->set_api_key_by_id( $api_key_data );
		$saved_pair = $api->get_api_key_by_id( $api_key_data['consumer_id'] );

		$this->assertEquals( $saved_pair['name'], 'Automated Tests 1' );

		$api->delete_api_key_by_id( $api_key_data['consumer_id'] );
		$deleted_pair = $api->get_api_key_by_id( $api_key_data['consumer_id'] );
		$this->assertEmpty( $deleted_pair );
	}

	/**
	 * @test
	 */
	public function should_generate_expect_hash_prefix_and_lengths() {
		$api = new Api( tribe( Actions::class ), tribe( Template_Modifications::class ) );
		$consumer_id     = $api->get_random_hash( 'ci_', 128 );
		$consumer_secret = $api->get_random_hash( 'ck_', 128 );

		$this->assertContains( 'ci_', $consumer_id );
		$this->assertEquals( 259, strlen( $consumer_id ) );
		$this->assertContains( 'ck_', $consumer_secret );
		$this->assertEquals( 259, strlen( $consumer_secret ) );
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
