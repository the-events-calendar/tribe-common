<?php

namespace TEC\Event_Automator\Power_Automate;

use Tribe\Tests\Traits\With_Uopz;

class ApiTest extends \Codeception\TestCase\WPTestCase {

	use With_Uopz;

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
		$mock_api_key_data = file_get_contents( codecept_data_dir( 'Power_Automate/Connections/200-account-valid-key-pair.json' ) );
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
		$mock_api_key_data = file_get_contents( codecept_data_dir( 'Power_Automate/Connections/200-account-valid-key-pair.json' ) );
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
		$mock_api_key_data = file_get_contents( codecept_data_dir( 'Power_Automate/Connections/200-account-valid-key-pair.json' ) );
		$api               = new Api( tribe( Actions::class ), tribe( Template_Modifications::class ) );
		$api_key_data      = json_decode( $mock_api_key_data, true );
		$api->set_api_key_by_id( $api_key_data );
		$saved_pair = $api->get_api_key_by_id( $api_key_data['consumer_id'] );

		$this->assertEquals( $saved_pair['name'], 'Automated Tests PA 1' );

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
		$mock_api_key_data = file_get_contents( codecept_data_dir( 'Power_Automate/Connections/200-account-valid-key-pair.json' ) );
		$api               = new Api( tribe( Actions::class ), tribe( Template_Modifications::class ) );
		$api_key_data      = json_decode( $mock_api_key_data, true );
		$api->set_api_key_by_id( $api_key_data );
		$saved_pair = $api->get_api_key_by_id( $api_key_data['consumer_id'] );

		$this->assertEquals( $saved_pair['name'], 'Automated Tests PA 1' );

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
}
