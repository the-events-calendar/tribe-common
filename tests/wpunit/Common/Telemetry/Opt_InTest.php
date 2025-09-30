<?php

namespace TEC\Common\Telemetry;

use TEC\Common\StellarWP\Telemetry\Config as Telemetry_Config;
use TEC\Common\StellarWP\Telemetry\Opt_In\Status;
use TEC\Common\StellarWP\Telemetry\Telemetry\Telemetry;
use TEC\Common\Telemetry\Telemetry as Common_Telemetry;
use TEC\Events\Admin\Onboarding\Controller as Onboarding_Controller;

/**
 * Class Opt_InTest
 *
 * @since 5.1.13
 *
 * @package TEC\Common\Telemetry
 */
class Opt_InTest extends \Codeception\TestCase\WPTestCase {
	protected $request_args = [];

	/**
	 * @before
	 */
	public function init_telemetry() {
		update_option( Status::OPTION_NAME, [ 'token' => 1 ] );
		tribe( Common_Telemetry::class )->boot();

		add_filter( 'stellarwp/telemetry/tec/optin_status', [ $this, 'return_1' ] );

		// Ensure the Onboarding Controller is loaded to register the telemetry filter.
		$this->ensure_onboarding_controller_loaded();
	}

	/**
	 * @after
	 */
	public function deinit_telemetry() {
		remove_filter( 'stellarwp/telemetry/tec/optin_status', [ $this, 'return_1' ] );
	}

	public function return_1() {
		return 1;
	}

	/**
	 * Ensure the Onboarding Controller is loaded to register the telemetry filter.
	 * This is needed because the controller might not be loaded in the test environment.
	 */
	private function ensure_onboarding_controller_loaded() {
		// Check if the filter is already registered.
		if ( has_filter( 'tec_telemetry_should_show_modal' ) ) {
			return;
		}

		// Manually load the Onboarding Controller.
		$controller = new Onboarding_Controller( tribe() );
		$controller->do_register();
	}

	public function capture_http_request( $response, $parsed_args, $url ) {
		if ( strpos( $url, 'telemetry' ) === false ) {
			return $response;
		}

		$this->request_args = $parsed_args;

		return new \WP_Error( '', '', 5 );
	}

	public function get_last_http_args() {
		$request_args       = $this->request_args;
		$this->request_args = [];
		remove_filter( 'pre_http_request', [ $this, 'capture_http_request' ] );
		return $request_args;
	}

	public function listen_for_http_request() {
		add_filter( 'pre_http_request', [ $this, 'capture_http_request' ], 10, 3 );
	}

	/**
	 * @test
	 */
	public function it_should_include_opt_in_user() {
		delete_option( Status::OPTION_NAME_USER_INFO );
		update_option( Status::OPTION_NAME_USER_INFO, [ 'user' => wp_json_encode( [
			'name' => 'bacon',
			'email' => 'bacon@bacon.bacon',
			'opt_in_text' => null,
			'plugin_slug' => 'the-events-calendar',
		] ) ] );

		$this->listen_for_http_request();

		$container = Telemetry_Config::get_container();
		$container->get( Telemetry::class )->send_data();

		$request_args = $this->get_last_http_args();

		$request_args = json_decode( $request_args['body']['telemetry'], true );

		$this->assertArrayHasKey( 'opt_in_user', $request_args );
		$this->assertEquals( 'bacon', $request_args['opt_in_user']['name'] );
		$this->assertEquals( 'bacon@bacon.bacon', $request_args['opt_in_user']['email'] );

		delete_option( Status::OPTION_NAME_USER_INFO );
	}

	/**
	 * @test
	 */
	public function it_should_include_empty_opt_in_user() {
		delete_option( Status::OPTION_NAME_USER_INFO );
		update_option( Status::OPTION_NAME_USER_INFO, [ 'user' => wp_json_encode( [
			'name' => null,
			'email' => null,
			'opt_in_text' => null,
			'plugin_slug' => 'the-events-calendar',
		] ) ] );

		$this->listen_for_http_request();

		$container = Telemetry_Config::get_container();
		$container->get( Telemetry::class )->send_data();

		$request_args = $this->get_last_http_args();

		$request_args = json_decode( $request_args['body']['telemetry'], true );

		$this->assertArrayHasKey( 'opt_in_user', $request_args );
		$this->assertNull( $request_args['opt_in_user']['name'] );
		$this->assertNull( $request_args['opt_in_user']['email'] );

		delete_option( Status::OPTION_NAME_USER_INFO );
	}

	/**
	 * @test
	 */
	public function it_should_include_opt_in_where_admin_email_has_user() {
		delete_option( Status::OPTION_NAME_USER_INFO );
		$this->listen_for_http_request();

		$container = Telemetry_Config::get_container();
		$container->get( Telemetry::class )->send_data();

		$request_args = $this->get_last_http_args();

		$request_args = json_decode( $request_args['body']['telemetry'], true );

		$this->assertArrayHasKey( 'opt_in_user', $request_args );
		$this->assertEquals( 'admin', $request_args['opt_in_user']['name'] );
		$this->assertEquals( 'admin@wordpress.test', $request_args['opt_in_user']['email'] );

		$cached_user_info = get_option( Status::OPTION_NAME_USER_INFO );
		$cached_opt_in_user = json_decode( $cached_user_info['user'], true );
		$this->assertEquals( 'admin', $cached_opt_in_user['name'] );
		$this->assertEquals( 'admin@wordpress.test', $cached_opt_in_user['email'] );

		delete_option( Status::OPTION_NAME_USER_INFO );
	}

	/**
	 * @test
	 */
	public function it_should_include_opt_in_where_admin_user_is_not_admin_email() {
		delete_option( Status::OPTION_NAME_USER_INFO );
		$admin_user = get_user_by( 'email', 'admin@wordpress.test' );
		wp_update_user( [
			'ID' => $admin_user->ID,
			'user_email' => 'bork@bork.bork',
		] );

		$this->listen_for_http_request();

		$container = Telemetry_Config::get_container();
		$container->get( Telemetry::class )->send_data();

		$request_args = $this->get_last_http_args();

		$request_args = json_decode( $request_args['body']['telemetry'], true );

		$this->assertArrayHasKey( 'opt_in_user', $request_args );
		$this->assertEquals( 'admin', $request_args['opt_in_user']['name'] );
		$this->assertEquals( 'bork@bork.bork', $request_args['opt_in_user']['email'] );

		wp_update_user( [
			'ID' => $admin_user->ID,
			'user_email' => 'admin@wordpress.test',
		] );

		$cached_user_info = get_option( Status::OPTION_NAME_USER_INFO );
		$cached_opt_in_user = json_decode( $cached_user_info['user'], true );
		$this->assertEquals( 'admin', $cached_opt_in_user['name'] );
		$this->assertEquals( 'bork@bork.bork', $cached_opt_in_user['email'] );

		delete_option( Status::OPTION_NAME_USER_INFO );
	}

	/**
	 * @test
	 */
	public function it_should_include_empty_opt_in_where_there_is_no_admin() {
		global $wpdb;

		delete_option( Status::OPTION_NAME_USER_INFO );
		$admin_user = get_user_by( 'email', 'admin@wordpress.test' );
		$capabilities = get_user_meta( $admin_user->ID, $wpdb->prefix . 'capabilities' );
		delete_user_meta( $admin_user->ID, $wpdb->prefix . 'capabilities' );
		wp_update_user( [
			'ID' => $admin_user->ID,
			'user_email' => 'bork@bork.bork',
		] );

		$this->listen_for_http_request();

		$container = Telemetry_Config::get_container();
		$container->get( Telemetry::class )->send_data();

		$request_args = $this->get_last_http_args();

		$request_args = json_decode( $request_args['body']['telemetry'], true );

		$this->assertArrayHasKey( 'opt_in_user', $request_args );
		$this->assertNull( $request_args['opt_in_user']['name'] );
		$this->assertNull( $request_args['opt_in_user']['email'] );

		update_user_meta( $admin_user->ID, $wpdb->prefix . 'capabilities', $capabilities );
		wp_update_user( [
			'ID' => $admin_user->ID,
			'user_email' => 'admin@wordpress.test',
		] );

		$cached_user_info = get_option( Status::OPTION_NAME_USER_INFO );
		$cached_opt_in_user = json_decode( $cached_user_info['user'], true );
		$this->assertNull( $cached_opt_in_user['name'] );
		$this->assertNull( $cached_opt_in_user['email'] );

		delete_option( Status::OPTION_NAME_USER_INFO );
	}

	/**
	 * @test
	 */
	public function it_should_not_show_telemetry_modal_when_onboarding_wizard_is_completed() {
		// Set up wizard as completed (finished = true, completed_tabs = [0, 1, 2]).
		update_option( 'tec_onboarding_wizard_data', [
			'finished' => true,
			'completed_tabs' => [ 0, 1, 2 ],
		] );

		// Test the modal status calculation.
		$should_show = Common_Telemetry::calculate_modal_status();

		$this->assertFalse( $should_show, 'Modal should not show when wizard is completed.' );

		// Clean up.
		delete_option( 'tec_onboarding_wizard_data' );
	}

	/**
	 * @test
	 */
	public function it_should_show_telemetry_modal_when_onboarding_wizard_is_skipped() {
		// Set up wizard as skipped (finished = true, but only tab 0 completed).
		update_option( 'tec_onboarding_wizard_data', [
			'finished' => true,
			'completed_tabs' => [ 0 ],
		] );

		// Test the modal status calculation.
		$should_show = Common_Telemetry::calculate_modal_status();

		$this->assertTrue( $should_show, 'Modal should show when wizard is skipped (only tab 0 completed).' );

		// Clean up.
		delete_option( 'tec_onboarding_wizard_data' );
	}

	/**
	 * @test
	 */
	public function it_should_show_telemetry_modal_when_no_onboarding_data_exists() {
		// Ensure no onboarding data exists.
		delete_option( 'tec_onboarding_wizard_data' );

		// Test the modal status calculation.
		$should_show = Common_Telemetry::calculate_modal_status();

		$this->assertTrue( $should_show, 'Modal should show when no onboarding data exists.' );
	}
}
