<?php

namespace TEC\Event_Automator\Zapier;

use tad\Codeception\SnapshotAssertions\SnapshotAssertions;
use TEC\Event_Automator\Tests\Traits\With_Uopz;

class SettingsTest extends \Codeception\TestCase\WPTestCase {

	use SnapshotAssertions;
	use With_Uopz;

	public function setUp() {
		// before
		parent::setUp();

		// Clear settings between tests.
		tribe_unset_var( \Tribe__Settings_Manager::OPTION_CACHE_VAR_NAME );
	}

	/**
	 * Gets an array of field keys to remove, to keep these tests about the API Key settings only.
	 *
	 * @since TBD
	 *
	 * @return array<string> An array of field keys to remove.
	 */
	protected function get_field_keys_to_remove() {
		return [ 'tec_zapier_endpoints_wrapper_open', 'tec_zapier_endpoints_header', 'tec_zapier_endpoints_endpoints', 'tec_zapier_endpoints_wrapper_close' ];
	}

	/**
	 * @test
	 */
	public function should_correctly_render_api_fields_initial_state() {
		$this->set_fn_return( 'wp_create_nonce', '123123' );
		$api = $this->construct( Api::class, [ tribe( Actions::class ), tribe( Template_Modifications::class ) ], [
			'get_random_hash' => static function ( $prefix = '', $length = 20 ) {
				return 'ci_8a2485a30a1298538da77dbb8e91a8d450ddf3a7';
			},
		] );
		$settings = new Settings( $api, tribe( Template_Modifications::class ), tribe( URL::class ) );
		$fields   = $settings->get_fields( [] );
		$fields   = array_diff_key( $fields, array_flip( $this->get_field_keys_to_remove() ) );

		$this->assertMatchesJsonSnapshot( json_encode( $fields, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function should_correctly_render_single_key_pair() {
		$this->set_fn_return( 'wp_create_nonce', '123123' );
		$mock_api_key_data = file_get_contents( codecept_data_dir( 'Zapier/API-Keys/200-account-valid-key-pair.json' ) );
		$api               = new Api( tribe( Actions::class ), tribe( Template_Modifications::class ) );
		$api_key_data      = json_decode( $mock_api_key_data, true );
		$api->set_api_key_by_id( $api_key_data );

		/** @var Settings $settings */
		$settings = tribe( Settings::class );
		$fields   = $settings->get_fields( [] );
		$fields   = array_diff_key( $fields, array_flip( $this->get_field_keys_to_remove() ) );

		$this->assertMatchesJsonSnapshot( json_encode( $fields, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function should_render_correctly_when_multiple_key_pairs() {
		$this->set_fn_return( 'wp_create_nonce', '123123' );
		$api               = new Api( tribe( Actions::class ), tribe( Template_Modifications::class ) );

		// Setup account 1.
		$mock_api_key_data = file_get_contents( codecept_data_dir( 'Zapier/API-Keys/200-account-valid-key-pair.json' ) );
		$api_key_data      = json_decode( $mock_api_key_data, true );
		$api->set_api_key_by_id( $api_key_data );

		// Setup account 2.
		$mock_account_data_2 = file_get_contents( codecept_data_dir( 'Zapier/API-Keys/200-account-valid-key-pair-2.json' ) );
		$api_key_data_2      = json_decode( $mock_account_data_2, true );
		$api->set_api_key_by_id( $api_key_data_2 );

		/** @var Settings $settings */
		$settings = tribe( Settings::class );
		$fields   = $settings->get_fields( [] );
		$fields   = array_diff_key( $fields, array_flip( $this->get_field_keys_to_remove() ) );

		$this->assertMatchesJsonSnapshot( json_encode( $fields, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function should_render_correctly_after_deleting_api_key_pairs() {
		$this->set_fn_return( 'wp_create_nonce', '123123' );
		$api               = new Api( tribe( Actions::class ), tribe( Template_Modifications::class ) );

		// Setup account 1.
		$mock_api_key_data = file_get_contents( codecept_data_dir( 'Zapier/API-Keys/200-account-valid-key-pair.json' ) );
		$api_key_data      = json_decode( $mock_api_key_data, true );
		$api->set_api_key_by_id( $api_key_data );

		// Setup account 2.
		$mock_account_data_2 = file_get_contents( codecept_data_dir( 'Zapier/API-Keys/200-account-valid-key-pair-2.json' ) );
		$api_key_data_2      = json_decode( $mock_account_data_2, true );
		$api->set_api_key_by_id( $api_key_data_2 );

		/** @var Settings $settings */
		$settings = tribe( Settings::class );
		$fields   = $settings->get_fields( [] );
		$fields   = array_diff_key( $fields, array_flip( $this->get_field_keys_to_remove() ) );

		$api->get_api_key_by_id( $api_key_data['consumer_id'] );
		$updated_fields = $settings->get_fields( [] );
		$updated_fields = array_diff_key( $updated_fields, array_flip( $this->get_field_keys_to_remove() ) );

		$before_and_after = array_merge( $fields, $updated_fields);

		$this->assertMatchesJsonSnapshot( json_encode( $before_and_after, JSON_PRETTY_PRINT ) );
	}
}
