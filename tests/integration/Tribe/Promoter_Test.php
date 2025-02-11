<?php

namespace Tribe;

use Tribe__Promoter__Connector;
use WP_Error;
use Tribe__PUE__Checker;

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

	/**
	 * @test
	 */
	public function it_can_set_a_valid_license_for_promoter() {
		// Retrieve the PUE checker instance.
		/** @var \Tribe__Promoter__PUE $pue */
		$pue = tribe( 'promoter.pue' );

		// Ensure `load()` has been called.
		$pue->load();

		// Access the `pue_checker` property using reflection.
		$reflection = new \ReflectionClass( $pue );
		$property   = $reflection->getProperty( 'pue_checker' );
		$property->setAccessible( true );
		$pue_checker = $property->getValue( $pue );

		// Ensure it's an instance of Tribe__PUE__Checker.
		$this->assertInstanceOf( Tribe__PUE__Checker::class, $pue_checker );

		// Set a valid license key.
		$valid_license_key = 'VALID-LICENSE-1234567890';
		update_option( $pue_checker->get_license_option_key(), $valid_license_key );

		// Manually trigger a validation check.
		$pue_checker->set_key_status( 1 ); // 1 indicates a valid key.

		// Check if the license key is valid.
		$this->assertTrue( $pue_checker->is_key_valid(), 'Expected the license key to be valid.' );

		// Ensure the option was stored correctly.
		$stored_license = get_option( $pue_checker->get_license_option_key() );
		$this->assertEquals( $valid_license_key, $stored_license, 'Expected the stored license key to match the valid key.' );
	}

	/**
	 * @test
	 */
	public function it_can_recreate_license_key_transients_properly() {
		// Retrieve the PUE checker instance.
		/** @var \Tribe__Promoter__PUE $pue */
		$pue = tribe( 'promoter.pue' );

		// Ensure `load()` has been called.
		$pue->load();

		// Access the `pue_checker` property using reflection.
		$reflection = new \ReflectionClass( $pue );
		$property   = $reflection->getProperty( 'pue_checker' );
		$property->setAccessible( true );
		$pue_checker = $property->getValue( $pue );

		// Ensure it's an instance of Tribe__PUE__Checker.
		$this->assertInstanceOf( Tribe__PUE__Checker::class, $pue_checker );

		// Get the transient and option keys.
		$license_option_key = $pue_checker->get_license_option_key();
		$transient_key      = $pue_checker->pue_key_status_transient_name;
		$global_transient   = Tribe__PUE__Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY;

		// Clear any existing transients and stored options.
		delete_transient( $transient_key );
		delete_transient( $global_transient );
		delete_option( $license_option_key );

		// Ensure they are actually deleted.
		$this->assertFalse( get_transient( $transient_key ), 'Expected the license status transient to be deleted.' );
		$this->assertFalse( get_transient( $global_transient ), 'Expected the global license transient to be deleted.' );
		$this->assertEmpty( get_option( $license_option_key ), 'Expected the stored license key to be empty.' );

		// Set a valid license key.
		$valid_license_key = 'VALID-LICENSE-1234567890';
		update_option( $license_option_key, $valid_license_key );

		// Manually trigger a validation check.
		$pue_checker->set_key_status( 1 ); // 1 indicates a valid key.

		// Check if the license key is valid.
		$this->assertTrue( $pue_checker->is_key_valid(), 'Expected the license key to be valid.' );

		// Ensure the option was stored correctly.
		$stored_license = get_option( $license_option_key );
		$this->assertEquals( $valid_license_key, $stored_license, 'Expected the stored license key to match the valid key.' );

		// Ensure the transient has been recreated.
		$this->assertEquals( 'valid', get_transient( $transient_key ), 'Expected the license status transient to be recreated with "valid".' );
		$global_transient_data = get_transient( $global_transient );
		$this->assertIsArray( $global_transient_data, 'Expected the global license transient to be recreated as an array.' );
		$this->assertArrayHasKey( $pue_checker->get_slug(), $global_transient_data['plugins'], 'Expected the plugin slug to be present in the global license transient.' );
		$this->assertTrue( $global_transient_data['plugins'][ $pue_checker->get_slug() ], 'Expected the global transient to recognize the license as valid.' );
	}
}
