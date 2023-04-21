<?php

namespace Tribe;

use Tribe__Promoter__Connector;
use WP_Error;

class Promoter_Test extends \Codeception\TestCase\WPTestCase {

	/**
	 * Basic sanity check of "failed" connection.
	 *
	 * @test
	 */
	public function should_handle_failed_auth_connection(): void {
		$connector    = tribe( Tribe__Promoter__Connector::class );
		$secret_key   = 'bob';
		$promoter_key = 'fred';
		$license_key  = 'jan';

		add_filter( 'pre_http_request', function ( $response, $parsed_args, $url ) {
			return new WP_Error( 'http_request_failed', "Faux Failure." );
		}, 99, 3 );

		$response = $connector->authorize_with_connector( wp_get_current_user()->ID, $secret_key, $promoter_key, $license_key );
		$this->assertFalse( $response );
	}

	/**
	 * Basic sanity check of "success" connection.
	 *
	 * @test
	 */
	public function should_handle_success_auth_connection(): void {
		$connector    = tribe( Tribe__Promoter__Connector::class );
		$secret_key   = 'bob';
		$promoter_key = 'fred';
		$license_key  = 'jan';

		add_filter( 'pre_http_request', function ( $response, $parsed_args, $url ) {
			return [ 'headers' => '', 'body' => 'Hello World', 'response' => '', 'cookies' => '', 'filename' => '' ];
		}, 99, 3 );

		$response = $connector->authorize_with_connector( wp_get_current_user()->ID, $secret_key, $promoter_key, $license_key );
		$this->assertTrue( $response );
	}

}
