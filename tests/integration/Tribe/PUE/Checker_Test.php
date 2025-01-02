<?php

namespace Tribe\PUE;

use Closure;
use Codeception\TestCase\WPTestCase;
use Generator;
use TEC\Common\StellarWP\Uplink\Auth\Action_Manager;
use TEC\Common\StellarWP\Uplink\Auth\Admin\Connect_Controller;
use TEC\Common\StellarWP\Uplink\Auth\Admin\Disconnect_Controller;
use TEC\Common\StellarWP\Uplink\Auth\Nonce;
use TEC\Common\StellarWP\Uplink\Auth\Token\Contracts\Token_Manager;
use TEC\Common\StellarWP\Uplink\Config;
use TEC\Common\StellarWP\Uplink\Register;
use TEC\Common\StellarWP\Uplink\Resources\Resource;
use TEC\Common\Tests\Licensing\PUE_Service_Mock;
use Tribe__Main;
use Tribe__PUE__Checker as PUE_Checker;
use WP_Screen;

use function TEC\Common\StellarWP\Uplink\get_resource;

class Checker_Test extends WPTestCase {
	/**
	 * @var PUE_Service_Mock
	 */
	private $pue_service_mock;

	/**
	 * @var Token_Manager
	 */
	private $token_manager;

	/**
	 * @before
	 */
	public function set_up_pue_service_mock(): void {
		$this->pue_service_mock = new PUE_Service_Mock();
	}

	/**
	 * It should not update license key if replacement key not provided
	 *
	 * @test
	 */
	public function should_not_update_license_key_if_replacement_key_not_provided(): void {
		// Ensure there is no key set.
		delete_option( 'pue_install_key_test_plugin' );
		$validated_key = md5( microtime() );
		$body          = $this->pue_service_mock->get_validate_key_success_body();
		$mock_response = $this->pue_service_mock->make_response( 200, $body, 'application/json' );
		$this->pue_service_mock->will_reply_to_request( 'POST', '/plugins/v2/license/validate', $mock_response );

		$pue_instance = new PUE_Checker( 'deprecated', 'test-plugin', [], 'test-plugin/test-plugin.php' );
		$pue_instance->validate_key( $validated_key, false );

		$this->assertEquals( $validated_key, $pue_instance->get_key() );
	}

	/**
	 * It should not update license key if replacement key is empty
	 *
	 * @test
	 */
	public function should_not_update_license_key_if_replacement_key_is_empty(): void {
		// Ensure there is no key set.
		delete_option( 'pue_install_key_test_plugin' );
		$validated_key = md5( microtime() );
		$body          = $this->pue_service_mock->get_validate_key_success_body();
		// Add an empty replacement key to the response body.
		$body['results'][0]['replacement_key'] = '';
		$mock_response                         = $this->pue_service_mock->make_response( 200, $body, 'application/json' );
		$this->pue_service_mock->will_reply_to_request( 'POST', '/plugins/v2/license/validate', $mock_response );

		$pue_instance = new PUE_Checker( 'deprecated', 'test-plugin', [], 'test-plugin/test-plugin.php' );
		$pue_instance->validate_key( $validated_key, false );

		$this->assertEquals( $validated_key, $pue_instance->get_key() );
	}

	/**
	 * It should update license key if replacement key provided and key not previously set
	 *
	 * @test
	 */
	public function should_update_license_key_if_replacement_key_provided_and_key_not_previously_set(): void {
		$validated_key = md5( microtime() );
		// Ensure there is no key set.
		delete_option( 'pue_install_key_test_plugin' );
		// Set the response mock to provide a replacement key.
		$replacement_key = '2222222222222222222222222222222222222222';
		$body            = $this->pue_service_mock->get_validate_key_success_body();
		// Add a replacement key to the response body.
		$body['results'][0]['replacement_key'] = $replacement_key;
		$mock_response                         = $this->pue_service_mock->make_response( 200, $body, 'application/json' );
		$this->pue_service_mock->will_reply_to_request( 'POST', '/plugins/v2/license/validate', $mock_response );

		$pue_instance = new PUE_Checker( 'deprecated', 'test-plugin', [], 'test-plugin/test-plugin.php' );
		$pue_instance->validate_key( $validated_key, false );

		$this->assertEquals( $replacement_key, $pue_instance->get_key() );
	}

	/**
	 * It should update license key if replacement key provided and key previously set
	 *
	 * @test
	 */
	public function should_update_license_key_if_replacement_key_provided_and_key_previously_set(): void {
		$original_key = md5( microtime() );
		// Set the current license key for the plugin.
		update_option( 'pue_install_key_test_plugin', $original_key );
		// Set the response mock to provide a replacement key.
		$replacement_key = '2222222222222222222222222222222222222222';
		$body            = $this->pue_service_mock->get_validate_key_success_body();
		// Add a replacement key to the response body.
		$body['results'][0]['replacement_key'] = $replacement_key;
		$mock_response                         = $this->pue_service_mock->make_response( 200, $body, 'application/json' );
		$this->pue_service_mock->will_reply_to_request( 'POST', '/plugins/v2/license/validate', $mock_response );

		$pue_instance = new PUE_Checker( 'deprecated', 'test-plugin', [], 'test-plugin/test-plugin.php' );
		$pue_instance->validate_key( $original_key, false );

		$this->assertEquals( $replacement_key, $pue_instance->get_key() );
	}

	/**
	 * @test
	 */
	public function it_should_check_uplink_before_pue() {
		Register::plugin(
			'test-plugin',
			'Test Plugin',
			'1.0.0',
			__DIR__,
			tribe( Tribe__Main::class )
		);

		$key = 'license-key-for-test-plugin';

		$resource = get_resource( 'test-plugin' );
		$resource->set_license_key( $key, 'any' );

		$pue_instance = new PUE_Checker( 'deprecated', 'test-plugin', [], 'test-plugin/test-plugin.php' );

		$this->assertEquals( $key, $pue_instance->get_key() );
	}

	/**
	 * It should validate licenses correctly across various scenarios.
	 *
	 * @test
	 * @dataProvider license_validation_data_provider
	 */
	public function should_is_any_license_valid_return_correctly( Closure $setup_closure, $expected_result, $message ): void {
		// Run the setup closure to configure the test scenario.
		$plugins_names = $setup_closure();

		// Assert the expected outcome.
		$this->assertEquals( $expected_result, PUE_Checker::is_any_license_valid(), $message );

		$transient = get_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY );

		// Assert that all plugin names are present in the transient.
		foreach ( $plugins_names as $plugin_name ) {
			$this->assertArrayHasKey(
				$plugin_name,
				$transient['plugins'],
				sprintf( 'The plugin "%s" should exist in the transient.', $plugin_name )
			);
		}
		// Clean up before each scenario.
		$this->clean_up_test_options( $plugins_names );
	}

	/**
	 * Cleans up transient and options dynamically based on plugins passed in.
	 */
	private function clean_up_test_options( array $plugin_names ): void {
		// Clear transient.
		delete_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY );

		// Clear dynamic options based on the plugins.
		foreach ( $plugin_names as $plugin_name ) {
			delete_option( "pue_install_key_{$plugin_name}" );
			//Disconnect any Uplink plugins
			$this->disconnect_uplink_plugin( $plugin_name );
		}
	}

	/**
	 * Data provider for license validation test scenarios.
	 *
	 * @return Generator
	 */
	public function license_validation_data_provider(): Generator {
		$plugin_names = [];
		yield 'initially_unlicensed' => [
			function () use ( &$plugin_names ) {
				$plugin_names = [];

				return $plugin_names;
				// No setup needed, all licenses are invalid initially.
			},
			false,
			'Initially unlicensed should return invalid.',
		];

		yield 'license_a_plugin' => [
			function () {
				$plugin_names   = [];
				$validated_key  = md5( microtime() );
				$plugin_name    = 'test-plugin-1';
				$plugin_names[] = $plugin_name;
				update_option( "pue_install_key_{$plugin_name}", $validated_key );
				$pue_instance = new PUE_Checker( 'deprecated', $plugin_name, [], "{$plugin_name}/{$plugin_name}.php" );
				$pue_instance->set_key_status( 1 ); // Set valid status.

				return $plugin_names;
			},
			true,
			'Licensing a plugin should make is_any_license_valid return valid.',
		];

		yield 'license_a_plugin_old_transient_valid' => [
			function () {
				$plugin_names   = [];
				$validated_key  = md5( microtime() );
				$plugin_name    = 'test-plugin-1';
				$plugin_names[] = $plugin_name;
				update_option( "pue_install_key_{$plugin_name}", $validated_key );
				$pue_instance = new PUE_Checker( 'deprecated', $plugin_name, [], "{$plugin_name}/{$plugin_name}.php" );
				$pue_instance->set_key_status( 1 ); // Set valid status.
				set_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY, 'valid', HOUR_IN_SECONDS );

				return $plugin_names;
			},
			true,
			'Licensing a plugin should make is_any_license_valid return valid.',
		];

		yield 'invalid_license_old_transient' => [
			function () {
				$plugin_names   = [];
				$validated_key  = md5( microtime() );
				$plugin_name    = 'test-plugin-1';
				$plugin_names[] = $plugin_name;
				update_option( "pue_install_key_{$plugin_name}", $validated_key );
				$pue_instance = new PUE_Checker( 'deprecated', $plugin_name, [], "{$plugin_name}/{$plugin_name}.php" );
				$pue_instance->set_key_status( 0 ); // Set valid status.
				set_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY, 'invalid', HOUR_IN_SECONDS );

				return $plugin_names;
			},
			false,
			'Licensing a plugin should make is_any_license_valid return valid.',
		];

		yield 'transient_deleted' => [
			function () use ( &$plugin_names ) {
				$plugin_names   = [];
				$validated_key  = md5( microtime() );
				$plugin_name    = 'test-plugin-1';
				$plugin_names[] = $plugin_name;
				update_option( "pue_install_key_{$plugin_name}", $validated_key );
				$pue_instance = new PUE_Checker( 'deprecated', $plugin_name, [], "{$plugin_name}/{$plugin_name}.php" );
				$pue_instance->set_key_status( 1 ); // Set valid status.
				delete_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY ); // Simulate transient deletion.

				return $plugin_names;
			},
			true,
			'Deleting transient should trigger revalidation and return valid if at least one license is valid.',
		];

		yield 'multiple_plugins_with_even_valid' => [
			function () {
				$plugin_names = [];
				for ( $i = 1; $i <= 10; $i++ ) {
					$validated_key  = md5( microtime() . $i );
					$plugin_name    = "test-plugin-{$i}";
					$plugin_names[] = $plugin_name;
					update_option( "pue_install_key_{$plugin_name}", $validated_key );

					$pue_instance = new PUE_Checker( 'deprecated', $plugin_name, [], "{$plugin_name}/{$plugin_name}.php" );
					if ( 0 === $i % 2 ) {
						// Even plugins are valid.
						$pue_instance->set_key_status( 1 );
					} else {
						// Odd plugins are invalid.
						$pue_instance->set_key_status( 0 );
					}
				}

				return $plugin_names;
			},
			true,
			'At least one valid license (even-numbered plugins) should make is_any_license_valid return valid.',
		];

		yield 'all_plugins_invalid' => [
			function () {
				$plugin_names = [];
				for ( $i = 1; $i <= 10; $i++ ) {
					$validated_key  = md5( microtime() . $i );
					$plugin_name    = "test-plugin-{$i}";
					$plugin_names[] = $plugin_name;
					update_option( "pue_install_key_{$plugin_name}", $validated_key );

					$pue_instance = new PUE_Checker( 'deprecated', $plugin_name, [], "{$plugin_name}/{$plugin_name}.php" );
					// All plugins are set as invalid.
					$pue_instance->set_key_status( 0 );
				}

				return $plugin_names;
			},
			false,
			'When all plugins are invalid, is_any_license_valid should return false.',
		];

		yield 'Uplink, valid license' => [
			function () {
				$plugin_names   = [];
				$slug           = 'valid-plugin-1';
				$plugin_names[] = $slug;
				$this->create_valid_uplink_license( $slug );

				return $plugin_names;
			},
			true,
			'When an Uplink license is valid, is_any_license_valid should return true.',
		];

		yield 'Uplink, valid license, with invalid PUE plugin' => [
			function () {
				$plugin_names   = [];
				$slug           = 'valid-plugin-2';
				$plugin_names[] = $slug;
				$this->create_valid_uplink_license( $slug );

				for ( $i = 1; $i <= 10; $i++ ) {
					$validated_key  = md5( microtime() . $i );
					$plugin_name    = "test-plugin-{$i}";
					$plugin_names[] = $plugin_name;
					update_option( "pue_install_key_{$plugin_name}", $validated_key );

					$pue_instance = new PUE_Checker( 'deprecated', $plugin_name, [], "{$plugin_name}/{$plugin_name}.php" );
					$pue_instance->set_key_status( 0 ); // All plugins are invalid.
				}

				return $plugin_names;
			},
			true,
			'When an Uplink license is valid, and old licenses are invalid, is_any_license_valid should return true.',
		];
	}

	/**
	 * Mock we're inside the wp-admin dashboard and fire off the admin_init hook.
	 *
	 * @param bool $network Whether we're in the network dashboard.
	 *
	 * @return void
	 */
	protected function admin_init( bool $network = false ): void {
		$screen                    = WP_Screen::get( $network ? 'dashboard-network' : 'dashboard' );
		$GLOBALS['current_screen'] = $screen;

		if ( $network ) {
			$this->assertTrue( $screen->in_admin( 'network' ) );
		}

		$this->assertTrue( $screen->in_admin() );

		// Fire off admin_init to run any of our events hooked into this action.
		do_action( 'admin_init' );
	}

	/**
	 * Helper to register a new plugin for Uplink testing.
	 *
	 * @param string $slug    The slug of the plugin.
	 * @param string $name    The name of the plugin.
	 * @param string $version The version of the plugin.
	 *
	 * @return Resource
	 */
	private function register_new_uplink_plugin( string $slug, string $name = 'Sample Plugin', string $version = '1.0.10' ): Resource {
		return Register::plugin(
			$slug,
			$name,
			$version,
			__DIR__,
			tribe( Tribe__Main::class )
		);
	}

	/**
	 * Creates a valid Uplink license for testing.
	 *
	 * @param string $slug The slug of the plugin to create a license for.
	 *
	 * @return string The generated token for the Uplink license.
	 */
	private function create_valid_uplink_license( string $slug ): string {
		$plugin = $this->register_new_uplink_plugin( $slug );

		// Set the current user to an admin.
		wp_set_current_user( 1 );

		// Initialize the token manager.
		$this->token_manager = tribe( Token_Manager::class );

		// Ensure no token exists for the plugin initially.
		$this->assertNull( $this->token_manager->get( $plugin ) );

		// Generate a nonce and a token.
		$nonce = ( tribe( Nonce::class ) )->create();
		$token = '53ca40ab-c6c7-4482-a1eb-14c56da31015';

		// Mock these were passed via the query string.
		global $_GET;
		$_GET[ Connect_Controller::TOKEN ] = $token;
		$_GET[ Connect_Controller::NONCE ] = $nonce;
		$_GET[ Connect_Controller::SLUG ]  = $slug;

		// Mock we're an admin inside the dashboard.
		$this->admin_init();

		// Fire off the specification action tied to this slug.
		do_action( tribe( Action_Manager::class )->get_hook_name( $slug ) );

		// Verify that the token was assigned correctly.
		$this->assertSame( $token, $this->token_manager->get( $plugin ) );

		// Verify that the general 'connected' action fires.
		$this->assertEquals( 1, did_action( 'stellarwp/uplink/' . Config::get_hook_prefix() . '/connected' ), 'Hook should only run once' );

		return $token;
	}

	/**
	 * Helper to disconnect an Uplink plugin.
	 *
	 * @param string $slug The slug of the plugin to disconnect.
	 */
	private function disconnect_uplink_plugin( string $slug ): void {
		$plugin = get_resource( $slug );
		if ( empty( $plugin ) ) {
			return;
		}

		if ( empty( $this->token_manager ) ) {
			return;
		}

		global $_GET;
		wp_set_current_user( 1 );

		$token = $this->token_manager->get( $plugin );
		$this->assertNotNull( $token );

		// Mock these were passed via the query string.
		$_GET[ Disconnect_Controller::ARG ]       = 1;
		$_GET[ Disconnect_Controller::CACHE_KEY ] = 'nada';
		$_GET[ Disconnect_Controller::SLUG ]      = $slug;
		$_GET['_wpnonce']                         = wp_create_nonce( Disconnect_Controller::ARG );

		// Mock we're an admin inside the dashboard.
		$this->admin_init();

		// Fire off the specification action tied to this slug.
		do_action( tribe( Action_Manager::class )->get_hook_name( $slug ) );

		// Assert that the token is removed.
		$this->assertNull( $this->token_manager->get( $plugin ) );

		// Verify that the disconnected action fires.
		$this->assertEquals( 1, did_action( 'stellarwp/uplink/' . Config::get_hook_prefix() . '/disconnected' ) );
	}

	/**
	 * It should validate license key using uplink and transient fallback
	 *
	 * @test
	 * @dataProvider license_key_validation_data_provider
	 */
	public function is_key_valid_should_return_correctly(
		Closure $setup_closure,
		bool $expected_result,
		string $scenario
	): void {
		// Run the setup closure to prepare the test environment.
		$pue_instance = $setup_closure();

		// Test the key validation.
		$actual_result = $pue_instance->is_key_valid();

		// Assert the outcome matches the expected result.
		$this->assertEquals( $expected_result, $actual_result, $scenario );
	}

	/**
	 * Data provider for is_key_valid_should_return_correctly test.
	 *
	 * @return Generator
	 */
	public function license_key_validation_data_provider(): Generator {
		yield 'Uplink: valid_key' => [
			function () {
				$plugin_slug         = 'test-plugin-valid';
				$license_key         = 'valid-license-key';
				$status_option_value = 'valid';

				$this->register_new_uplink_plugin( $plugin_slug );
				// Set the license key in the resource.
				$resource = get_resource( $plugin_slug );
				$resource->set_license_key( $license_key, 'any' );
				$status_option_name = $resource->get_license_object()->get_key_status_option_name();
				update_option( $status_option_name, $status_option_value );

				// Create the PUE checker instance.
				return new PUE_Checker( 'deprecated', $plugin_slug, [], "{$plugin_slug}/{$plugin_slug}.php" );
			},
			true,
			'A valid license key should return true.',
		];

		yield 'Uplink: invalid_key' => [
			function () {
				$plugin_slug         = 'test-plugin-invalid';
				$license_key         = 'invalid-license-key';
				$status_option_value = 'invalid';

				$this->register_new_uplink_plugin( $plugin_slug );
				// Set the license key in the resource.
				$resource = get_resource( $plugin_slug );
				$resource->set_license_key( $license_key, 'any' );
				$status_option_name = $resource->get_license_object()->get_key_status_option_name();
				update_option( $status_option_name, $status_option_value );

				// Create the PUE checker instance.
				return new PUE_Checker( 'deprecated', $plugin_slug, [], "{$plugin_slug}/{$plugin_slug}.php" );
			},
			false,
			'An invalid license key should return false.',
		];

		yield 'PUE: transient not empty and valid' => [
			function () {
				$plugin_slug = 'pue-plugin-plugin';

				$pue_checker = new PUE_Checker( 'deprecated', $plugin_slug, [], "{$plugin_slug}/{$plugin_slug}.php" );

				// Set up the transient and options for validation.
				set_transient( $pue_checker->pue_key_status_transient_name, 'valid', HOUR_IN_SECONDS );
				update_option( $pue_checker->pue_key_status_option_name, 'valid' );

				return $pue_checker;
			},
			true,
			'A valid transient should return true.',
		];

		yield 'PUE: transient empty, option invalid' => [
			function () {
				$plugin_slug = 'pue-plugin-plugin';

				$pue_checker = new PUE_Checker( 'deprecated', $plugin_slug, [], "{$plugin_slug}/{$plugin_slug}.php" );

				// Set up the options, but no transient for validation.
				delete_transient( $pue_checker->pue_key_status_transient_name );
				update_option( $pue_checker->pue_key_status_transient_name, 'invalid' );

				return $pue_checker;
			},
			false,
			'An empty transient with an invalid option should return false.',
		];

		yield 'PUE: transient empty, option valid' => [
			function () {
				$plugin_slug = 'pue-plugin-plugin';

				$pue_checker = new PUE_Checker( 'deprecated', $plugin_slug, [], "{$plugin_slug}/{$plugin_slug}.php" );

				// Set up the options, but no transient for validation.
				delete_transient( $pue_checker->pue_key_status_transient_name );
				set_transient( $pue_checker->pue_key_status_transient_name, 'valid', HOUR_IN_SECONDS );
				update_option( $pue_checker->pue_key_status_option_name, 'valid' );

				return $pue_checker;
			},
			true,
			'An empty transient with a valid option should return true.',
		];

		yield 'PUE: transient not empty but invalid' => [
			function () {
				$plugin_slug = 'pue-plugin-plugin';

				$pue_checker = new PUE_Checker( 'deprecated', $plugin_slug, [], "{$plugin_slug}/{$plugin_slug}.php" );

				// Set up the transient and options for validation.
				set_transient( $pue_checker->pue_key_status_transient_name, 'invalid', HOUR_IN_SECONDS );
				update_option( $pue_checker->pue_key_status_option_name, 'invalid' );

				return $pue_checker;
			},
			false,
			'A transient marked invalid should return false.',
		];
	}

	/**
	 * It should correctly update the transient in is_key_valid for different scenarios.
	 *
	 * @test
	 * @dataProvider is_key_valid_scenarios_data_provider
	 */
	public function is_key_valid_should_update_transient_correctly_with_scenarios(
		Closure $setup_closure,
		bool $expected_valid,
		string $scenario
	): void {
		$transient_key = PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY;

		// Run the setup closure to configure the test case.
		[ $plugin_slug, $pue_instance ] = $setup_closure();

		// Assert the transient is initially empty.
		$this->assertFalse( get_transient( $transient_key ), 'Transient should be empty initially.' );

		// Call is_key_valid and verify the return value.
		$is_valid = $pue_instance->is_key_valid();
		$this->assertSame( $expected_valid, $is_valid, $scenario );

		// Retrieve the transient and verify its structure.
		$transient = get_transient( $transient_key );
		$this->assertIsArray( $transient, 'Transient should be an array.' );
		$this->assertArrayHasKey( 'plugins', $transient, 'Transient should have a "plugins" key.' );
		$this->assertArrayHasKey( $plugin_slug, $transient['plugins'], "Transient should contain the plugin slug: {$plugin_slug}." );

		// Verify the plugin status in the transient matches the expected validity.
		$this->assertSame(
			$expected_valid,
			$transient['plugins'][ $plugin_slug ],
			"Transient should reflect the expected validity for plugin slug: {$plugin_slug}."
		);
	}

	/**
	 * Data provider for is_key_valid scenarios.
	 *
	 * @return Generator
	 */
	public function is_key_valid_scenarios_data_provider(): Generator {
		yield 'Valid transient, valid option' => [
			function () {
				$plugin_slug  = 'test-plugin-valid-both';
				$pue_instance = new PUE_Checker( 'deprecated', $plugin_slug, [], "{$plugin_slug}/{$plugin_slug}.php" );

				// Mock the transient and option to simulate a valid state.
				set_transient( $pue_instance->pue_key_status_transient_name, 'valid', HOUR_IN_SECONDS );
				update_option( $pue_instance->pue_key_status_option_name, 'valid' );

				return [ $plugin_slug, $pue_instance ];
			},
			true,
			'A valid transient and a valid option should return true.',
		];

		yield 'Empty transient, valid option' => [
			function () {
				$plugin_slug  = 'test-plugin-empty-transient-valid-option';
				$pue_instance = new PUE_Checker( 'deprecated', $plugin_slug, [], "{$plugin_slug}/{$plugin_slug}.php" );

				// Mock only the option as valid.
				delete_transient( $pue_instance->pue_key_status_transient_name );
				update_option( $pue_instance->pue_key_status_option_name, 'valid' );

				return [ $plugin_slug, $pue_instance ];
			},
			true,
			'An empty transient with a valid option should return true.',
		];

		yield 'Valid transient, invalid option' => [
			function () {
				$plugin_slug  = 'test-plugin-valid-transient-invalid-option';
				$pue_instance = new PUE_Checker( 'deprecated', $plugin_slug, [], "{$plugin_slug}/{$plugin_slug}.php" );

				// Mock a valid transient and an invalid option.
				set_transient( $pue_instance->pue_key_status_transient_name, 'valid', HOUR_IN_SECONDS );
				update_option( $pue_instance->pue_key_status_option_name, 'invalid' );

				return [ $plugin_slug, $pue_instance ];
			},
			true,
			'A valid transient with an invalid option should return true.',
		];

		yield 'Empty transient, invalid option' => [
			function () {
				$plugin_slug  = 'test-plugin-empty-transient-invalid-option';
				$pue_instance = new PUE_Checker( 'deprecated', $plugin_slug, [], "{$plugin_slug}/{$plugin_slug}.php" );

				// Mock only the option as invalid.
				delete_transient( $pue_instance->pue_key_status_transient_name );
				update_option( $pue_instance->pue_key_status_option_name, 'invalid' );

				return [ $plugin_slug, $pue_instance ];
			},
			false,
			'An empty transient with an invalid option should return false.',
		];
	}

}
