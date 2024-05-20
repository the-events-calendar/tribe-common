<?php

namespace TEC\Event_Automator\Power_Automate;

use tad\Codeception\SnapshotAssertions\SnapshotAssertions;
use Tribe\Tests\Traits\With_Uopz;

class ApiAJAX extends \Codeception\TestCase\WPAjaxTestCase {

	use SnapshotAssertions;
	use With_Uopz;

	public function _tearDown() {
		parent::_tearDown();

		// Added to prevent "Test code or tested code did not (only) close its own output buffers" error.
		ob_start();
	}

	/**
	 * Setup AJAX Test.
	 *
	 * @since 1.0.0
	 */
	private function ajax_setup() {
		$this->set_fn_return( 'wp_create_nonce', 'c6f01bbbe9' );
		$this->set_fn_return( 'check_ajax_referer', true );
		$this->set_fn_return( 'wp_doing_ajax', true );
		$this->set_fn_return( 'wp_verify_nonce', true );
	}

	/**
	 * @test
	 */
	public function should_not_handle_ajax_request_if_nonce_is_not_valid() {
		$api = new Api( tribe( Actions::class ), tribe( Template_Modifications::class ) );

		try {
			$api->ajax_add_connection( 'foobar' );
		} catch ( \WPAjaxDieStopException $e ) {
			// Expected this, do nothing.
		}

		$this->assertTrue( isset( $e ) );
		$this->assertFalse( tribe_is_truthy( $e->getMessage() ) );
	}

	/**
	 * @test
	 */
	public function should_correctly_generate_new_connection_fields() {
		$this->ajax_setup();

		$api = $this->construct( Api::class, [ tribe( Actions::class ), tribe( Template_Modifications::class ) ], [
			'get_random_hash' => static function ( $prefix, $length ) {
				return 'ci_8a2485a30a1298538da77dbb8e91a8d450ddf3a7';
			},
		] );

		try {
			$api->ajax_add_connection( wp_create_nonce( Actions::$add_connection ) );
		} catch ( \WPAjaxDieContinueException $e ) {
			// Expected this, do nothing.
		}

		$this->assertTrue( isset( $e ) );
		$html = $this->_last_response;

		$this->assertMatchesHtmlSnapshot( $html );
	}

	public function generate_data_provider() {
		return [
			'missing-consumer-id' => [ '', '', '', '' ],
			'missing-name'        => [ 'ci_8a2485a30a1298538da77dbb8e91a8d450ddf3a7', '', '', '' ],
			'missing-user-id'     => [ 'ci_8a2485a30a1298538da77dbb8e91a8d450ddf3a7', 'automated-test', '', '' ],
			'missing-permissions' => [ 'ci_8a2485a30a1298538da77dbb8e91a8d450ddf3a7', 'automated-test', '1', '' ],
			'valid'               => [ 'ci_8a2485a30a1298538da77dbb8e91a8d450ddf3a7', 'automated-test', '1', 'read' ],
		];
	}

	/**
	 * @test
	 * @dataProvider generate_data_provider
	 */
	public function should_correctly_generate_access_token( $consumer_id, $name, $user_id, $permissions ) {
		$this->ajax_setup();

		$api = $this->construct( Api::class, [ tribe( Actions::class ), tribe( Template_Modifications::class ) ], [
			'create_access_token' => static function ( $prefix, $length ) {
				return 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vJVRFU1RfU0lURV9XUF9ET01BSU4lIiwiaWF0IjoxNjg0NDA2MzY2LCJuYmYiOjE2ODQ0MDYzNjYsImRhdGEiOnsiY29uc3VtZXJfaWQiOiJjaV84YTI0ODVhMzBhMTI5ODUzOGRhNzdkYmI4ZTkxYThkNDUwZGRmM2E3IiwiY29uc3VtZXJfc2VjcmV0IjoiY2tfOGEyNDg1YTMwYTEyOTg1MzhkYTc3ZGJiOGU5MWE4ZDQ1MGRkZjNhNyIsImFwcF9uYW1lIjoiYXV0b21hdGVkLXRlc3QifX0.aWIzNuPbTchVnpSrBQckKlBFbsKQNBy4MUROY_8MjoA';
			},
		] );

		$_REQUEST['consumer_id'] = $consumer_id;
		$_REQUEST['name']        = $name;
		$_REQUEST['user_id']     = $user_id;
		$_REQUEST['permissions'] = $permissions;

		try {
			$api->ajax_create_connection_access( wp_create_nonce( Actions::$create_access ) );
		} catch ( \WPAjaxDieContinueException $e ) {
			// Expected this, do nothing.
		}

		$this->assertTrue( isset( $e ) );
		$html = $this->_last_response;

		$this->assertMatchesHtmlSnapshot( $html );
	}

	public function delete_data_provider() {
		return [
			'missing-consumer-id' => [ '' ],
			'invalid-consumer-id' => [ 'ci_8a2485a30a1298538da77dbb8e91a8d450ddf3a7' ],
			'valid-consumer-id'   => [ 'efbe2a4f41ad592da9afe3cef24a5b358e9c6ab395e8c86e39593ab7a374815b' ],
		];
	}

	/**
	 * @test
	 * @dataProvider delete_data_provider
	 */
	public function should_correctly_delete_connection( $consumer_id ) {
		$this->ajax_setup();

		$api               = new Api( tribe( Actions::class ), tribe( Template_Modifications::class ) );
		$mock_api_key_data = file_get_contents( codecept_data_dir( 'Power_Automate/Connections/200-account-valid-key-pair.json' ) );
		$api_key_data      = json_decode( $mock_api_key_data, true );
		$api->set_api_key_by_id( $api_key_data );

		$_REQUEST['consumer_id'] = $consumer_id;

		try {
			$api->ajax_delete_connection( wp_create_nonce( Actions::$delete_connection ) );
		} catch ( \WPAjaxDieContinueException $e ) {
			// Expected this, do nothing.
		}

		$this->assertTrue( isset( $e ) );
		$html = $this->_last_response;

		$this->assertMatchesHtmlSnapshot( $html );
	}
}
