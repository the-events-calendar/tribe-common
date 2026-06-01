<?php

namespace Tribe;

use Tribe__Promoter__Connector;
use WP_Error;
use TEC\Common\Firebase\JWT\JWT as TEC_JWT;
use TEC\Common\Firebase\JWT\Key as TEC_JWT_Key;

class Promoter_Test extends \Codeception\TestCase\WPTestCase {

	/**
	 * This tests our reliance on a 3rd party library and temporary aliasing of a JWT library.
	 *
	 * @see TEC-4866 - this is regarding 3rd party library collissions.
	 *      https://theeventscalendar.atlassian.net/browse/TEC-4866
	 * @test
	 */
	public function should_handle_vendor_prefixed_firebase_library() {
		$this->assertFalse( class_exists( 'Firebase\JWT\JWT' ) );
		$this->assertTrue( class_exists( 'TEC\Common\Firebase\JWT\JWT' ) );
	}

	/**
	 * @after
	 */
	public function should_handle_payload_of_jwt_token(): void {
		wp_set_current_user( 0 );
	}

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

		$user_id = self::factory()->user->create( [ 'role' => 'administrator' ] );

		wp_set_current_user( $user_id );

		$payload = [];

		add_filter( 'pre_http_request', function ( $response, $parsed_args, $url ) use ( $promoter_key, &$payload ) {
			$token = $parsed_args['body']['token'];
			$key = new TEC_JWT_Key( $promoter_key, 'HS256' );
			$payload = (array) TEC_JWT::decode( $token, $key );
			return new WP_Error( 'http_request_failed', "Faux Failure." );
		}, 99, 3 );

		$response = $connector->authorize_with_connector( wp_get_current_user()->ID, $secret_key, $promoter_key, $license_key );
		$this->assertFalse( $response );
		$this->assertEquals( $payload['domain'], 'wordpress.test' );
		$this->assertEquals( $payload['clientSecret'], $secret_key );
		$this->assertEquals( $payload['licenseKey'], $license_key );
		$this->assertEquals( $payload['userId'], $user_id );
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

		$user_id = self::factory()->user->create( [ 'role' => 'administrator' ] );

		wp_set_current_user( $user_id );

		$payload = [];

		add_filter( 'pre_http_request', function ( $response, $parsed_args, $url ) use ( $promoter_key, &$payload ) {
			$token = $parsed_args['body']['token'];
			$key = new TEC_JWT_Key( $promoter_key, 'HS256' );
			$payload = (array) TEC_JWT::decode( $token, $key );
			return [ 'headers' => '', 'body' => 'Hello World', 'response' => '', 'cookies' => '', 'filename' => '' ];
		}, 99, 3 );

		$response = $connector->authorize_with_connector( wp_get_current_user()->ID, $secret_key, $promoter_key, $license_key );
		$this->assertTrue( $response );
		$this->assertEquals( $payload['domain'], 'wordpress.test' );
		$this->assertEquals( $payload['clientSecret'], $secret_key );
		$this->assertEquals( $payload['licenseKey'], $license_key );
		$this->assertEquals( $payload['userId'], $user_id );
	}

	/**
	 * @test
	 */
	public function should_send_domain_in_notify_payload(): void {
		$connector   = tribe( Tribe__Promoter__Connector::class );
		$secret_key  = 'bob';
		$license_key = 'jan';

		// The secret key used to sign the notify payload.
		update_option( 'tribe_promoter_auth_key', $secret_key );
		// The license info pulled by Tribe__Promoter__PUE::get_license_info().
		update_option( 'pue_install_key_promoter', $license_key );

		$post_id = self::factory()->post->create( [ 'post_type' => 'tribe_events' ] );

		$payload = [];

		add_filter( 'pre_http_request', function ( $response, $parsed_args, $url ) use ( $secret_key, &$payload ) {
			$token   = $parsed_args['body']['token'];
			$key     = new TEC_JWT_Key( $secret_key, 'HS256' );
			$payload = (array) TEC_JWT::decode( $token, $key );

			return [ 'headers' => '', 'body' => 'Hello World', 'response' => '', 'cookies' => '', 'filename' => '' ];
		}, 99, 3 );

		$connector->notify_promoter_of_changes( $post_id );

		$this->assertArrayHasKey( 'domain', $payload );
		$this->assertEquals( 'wordpress.test', $payload['domain'] );
		$this->assertEquals( $license_key, $payload['licenseKey'] );
		$this->assertEquals( $post_id, $payload['sourceId'] );
	}

}
