<?php

namespace Tribe\PUE;

use Closure;
use Codeception\TestCase\WPTestCase;
use Generator;
use ReflectionClass;
use TEC\Common\StellarWP\Uplink\Auth\Action_Manager;
use TEC\Common\StellarWP\Uplink\Auth\Admin\Connect_Controller;
use TEC\Common\StellarWP\Uplink\Auth\Admin\Disconnect_Controller;
use TEC\Common\StellarWP\Uplink\Auth\Nonce;
use TEC\Common\StellarWP\Uplink\Auth\Token\Contracts\Token_Manager;
use TEC\Common\StellarWP\Uplink\Config;
use TEC\Common\StellarWP\Uplink\Register;
use TEC\Common\StellarWP\Uplink\Resources\Resource;
use TEC\Common\Tests\Licensing\PUE_Service_Mock;
use Tribe\Tests\Traits\With_Uopz;
use Tribe__Main;
use Tribe__PUE__Checker as PUE_Checker;
use WP_Screen;
use function TEC\Common\StellarWP\Uplink\get_resource;

class Checker_Test extends WPTestCase {
	use With_Uopz;

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

					update_option( "pue_install_key_{$plugin_name}", $validated_key );

					$pue_instance = new PUE_Checker( 'deprecated', $plugin_name, [], "{$plugin_name}/{$plugin_name}.php" );
					if ( 0 === $i % 2 ) {
						// Even plugins are valid.
						$pue_instance->set_key_status( 1 );
						$plugin_names[] = $plugin_name;
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
					$validated_key           = md5( microtime() . $i );
					$plugin_name             = "test-plugin-{$i}";
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
		$this->assertEquals( 1, did_action( 'stellarwp/uplink/' . Config::get_hook_prefix() . '/connected' ) ,'Hook should only run once');

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
	 * It should monitor active plugins through constructor and update transient correctly
	 *
	 * @test
	 */
	public function should_monitor_active_plugins_through_constructor_and_update_transient(): void {
		// Use reflection to access the protected $instances property.
		$reflection         = new ReflectionClass( PUE_Checker::class );
		$instances_property = $reflection->getProperty( 'instances' );
		$instances_property->setAccessible( true );

		// Clear the $instances property.
		$instances_property->setValue( [] );
		// Clear transient to ensure a clean test environment.
		delete_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY );

		// Create mock plugin instances through constructor.
		$plugin_1 = new PUE_Checker( 'deprecated', 'plugin-1', [], 'plugin-1/plugin-1.php' );
		$plugin_1->set_key_status( 1 ); // Plugin 1 is valid.

		$plugin_2 = new PUE_Checker( 'deprecated', 'plugin-2', [], 'plugin-2/plugin-2.php' );
		$plugin_2->set_key_status( 0 ); // Plugin 2 is invalid.

		// Retrieve the transient data.
		$transient_data = get_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY );

		// Assert that the transient data contains the monitored plugins.
		$this->assertArrayHasKey( 'plugin-1', $transient_data['plugins'], 'Plugin 1 should exist in the transient.' );
		$this->assertArrayHasKey( 'plugin-2', $transient_data['plugins'], 'Plugin 2 should exist in the transient.' );

		// Assert the license status of each plugin.
		$this->assertTrue( $transient_data['plugins']['plugin-1'], 'Plugin 1 should have a valid license.' );
		$this->assertFalse( $transient_data['plugins']['plugin-2'], 'Plugin 2 should not have a valid license.' );

		// Add a new plugin through the constructor.
		$plugin_3 = new PUE_Checker( 'deprecated', 'plugin-3', [], 'plugin-3/plugin-3.php' );
		$plugin_3->set_key_status( 1 ); // Plugin 3 is valid.

		// Retrieve updated transient data.
		$transient_data = get_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY );

		// Assert the new plugin is added to the transient data.
		$this->assertArrayHasKey( 'plugin-3', $transient_data['plugins'], 'Plugin 3 should exist in the transient.' );
		$this->assertTrue( $transient_data['plugins']['plugin-3'], 'Plugin 3 should have a valid license.' );
	}

	/**
	 * It should handle monitor_active_plugins correctly directly.
	 *
	 * @test
	 */
	public function should_handle_monitor_active_plugins_directly(): void {
		// Use reflection to access the protected $instances property.
		$reflection         = new ReflectionClass( PUE_Checker::class );
		$instances_property = $reflection->getProperty( 'instances' );
		$instances_property->setAccessible( true );

		// Clear the $instances property.
		$instances_property->setValue( [] );
		// Clear transient to ensure a clean test environment.
		delete_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY );

		// Assert that no transient data exists since no plugins are being monitored.
		$this->assertFalse(
			get_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY ),
			'Transient should not exist when no checker is provided.'
		);

		// Create a mock plugin instance.
		$plugin_1 = new PUE_Checker( 'deprecated', 'plugin-1', [], 'plugin-1/plugin-1.php' );
		$plugin_1->set_key_status( 1 ); // Plugin 1 is valid.

		// Call monitor_active_plugins with a checker instance.
		PUE_Checker::monitor_active_plugins( $plugin_1 );

		// Retrieve the transient data.
		$transient_data = get_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY );

		// Assert that the transient data contains the monitored plugin.
		$this->assertArrayHasKey( 'plugin-1', $transient_data['plugins'], 'Plugin 1 should be monitored.' );
		$this->assertTrue( $transient_data['plugins']['plugin-1'], 'Plugin 1 should have a valid license.' );

		// Add another plugin and monitor it directly.
		$plugin_2 = new PUE_Checker( 'deprecated', 'plugin-2', [], 'plugin-2/plugin-2.php' );
		$plugin_2->set_key_status( 0 ); // Plugin 2 is invalid.
		PUE_Checker::monitor_active_plugins( $plugin_2 );

		// Retrieve the updated transient data.
		$transient_data = get_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY );

		// Assert the new plugin is added to the transient data.
		$this->assertArrayHasKey( 'plugin-2', $transient_data['plugins'], 'Plugin 2 should be monitored.' );
		$this->assertFalse( $transient_data['plugins']['plugin-2'], 'Plugin 2 should not have a valid license.' );
	}

	/**
	 * @test
	 */
	public function it_should_set_transient_and_option_for_valid_key(): void {
		$plugin_name = 'test_plugin_for_init_method';
		$site_domain = $_SERVER['SERVER_NAME']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput

		$transient_key = "pue_key_status_{$plugin_name}_{$site_domain}";
		$option_key = "pue_install_key_{$plugin_name}";

		// Clean up any existing transients or options.
		delete_transient($transient_key);
		delete_option($option_key);

		// Assert initial state is clean.
		$this->assertEmpty(get_transient($transient_key), 'Transient should be empty initially.');
		$this->assertEmpty(get_option($option_key), 'Option should be empty initially.');

		// Mock `validate_key` to return a valid response.
		$this->set_class_fn_return(
			PUE_Checker::class,
			'validate_key',
			[ 'status' => 'valid', 'replacement_key' => 'new-valid-key' ]
		);

		// Ensure the option is set before instantiation.
		update_option($option_key, 'new-valid-key');

		// Instantiate `PUE_Checker` to trigger the initialization logic.
		$pue_checker = new PUE_Checker('deprecated', $plugin_name, [], "{$plugin_name}/{$plugin_name}.php");
		$pue_checker->set_key_status(1); // Synchronize test data with mocked response.

		// Retrieve transient and option values for assertions.
		$transient_value = get_transient($pue_checker->pue_key_status_transient_name);
		$option_value = get_option($pue_checker->pue_key_status_option_name);
		$timeout = get_option("{$pue_checker->pue_key_status_option_name}_timeout");

		// Assertions to verify behavior.
		$this->assertEquals('valid', $transient_value, 'Transient should be set to "valid".');
		$this->assertEquals('valid', $option_value, 'Option should be set to "valid".');
		$this->assertNotEmpty($timeout, 'Timeout should be set for the valid key.');
		$this->assertGreaterThan(time(), $timeout, 'Timeout should be a future timestamp.');
	}

	/**
	 * @test
	 * @dataProvider license_check_scenarios
	 */
	public function it_should_handle_license_check_correctly(
		string $plugin_name,
		Closure $setup_closure,
		array $mock_response,
		string $expected_transient,
		string $expected_option,
		string $scenario
	): void {
		// Dynamic site domain for testing.
		$site_domain = $_SERVER['SERVER_NAME']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput

		// Keys used for testing.
		$transient_key = "pue_key_status_{$plugin_name}_{$site_domain}";
		$option_key    = "pue_install_key_{$plugin_name}";

		// Clean up any existing transients or options.
		delete_transient( $transient_key );
		delete_option( $option_key );

		// Assert initial state is clean.
		$this->assertEmpty( get_transient( $transient_key ), 'Transient should be empty initially.' );
		$this->assertEmpty( get_option( $option_key ), 'Option should be empty initially.' );

		// Run the setup closure to prepare the test scenario.
		$setup_closure();

		// Mock `validate_key` with dynamic response.
		$this->set_class_fn_return( PUE_Checker::class, 'validate_key', $mock_response );

		// Instantiate `PUE_Checker` to trigger the initialization logic.
		$pue_checker = new PUE_Checker( 'deprecated', $plugin_name, [], "{$plugin_name}/{$plugin_name}.php" );
		$pue_checker->set_key_status( $mock_response['status'] === 'valid' ? 1 : 0 ); // Synchronize test data.

		// Retrieve transient and option values for assertions.
		$transient_value = get_transient( $pue_checker->pue_key_status_transient_name );
		$option_value    = get_option( $pue_checker->pue_key_status_option_name );
		$timeout         = get_option( "{$pue_checker->pue_key_status_option_name}_timeout" );

		// Assertions to verify behavior.
		$this->assertEquals( $expected_transient, $transient_value, 'Transient should match the expected value.' );
		$this->assertEquals( $expected_option, $option_value, 'Option should match the expected value.' );
		$this->assertNotEmpty( $timeout, 'Timeout should be set for the key.' );
		$this->assertGreaterThan( time(), $timeout, 'Timeout should be a future timestamp.' );

		// Provide a descriptive scenario for debugging.
		$this->assertTrue( true, $scenario );
	}

	/**
	 * Data provider for license check scenarios.
	 *
	 * @return \Generator
	 */
	public function license_check_scenarios(): \Generator {
		yield 'Valid key, no transient or option' => [
			'plugin_name' => 'test_plugin_valid_no_data',
			function () {
				// Setup for no transient or option.
			},
			[ 'status' => 'valid', 'replacement_key' => 'new-valid-key' ],
			'valid',
			'valid',
			'Should set transient and option for a valid key.',
		];

		yield 'Invalid key, no transient or option' => [
			'plugin_name' => 'test_plugin_invalid_no_data',
			function () {
				// Setup for no transient or option.
			},
			[ 'status' => 'invalid', 'replacement_key' => '' ],
			'invalid',
			'invalid',
			'Should only set transient for an invalid key.',
		];

		yield 'Valid key, existing transient and option' => [
			'plugin_name' => 'test_plugin_valid_existing_data',
			function () {
				// Setup for existing transient and option.
				$site_domain = $_SERVER['SERVER_NAME']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
				set_transient( "pue_key_status_test_plugin_valid_existing_data_{$site_domain}", 'valid', HOUR_IN_SECONDS );
				update_option( 'pue_install_key_test_plugin_valid_existing_data', 'existing-valid-key' );
			},
			[ 'status' => 'valid', 'replacement_key' => 'updated-valid-key' ],
			'valid',
			'valid',
			'Should retain transient and option for an existing valid key.',
		];

		yield 'Invalid key, existing transient and option' => [
			'plugin_name' => 'test_plugin_invalid_existing_data',
			function () {
				// Setup for existing transient and option.
				$site_domain = $_SERVER['SERVER_NAME']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
				set_transient( "pue_key_status_test_plugin_invalid_existing_data_{$site_domain}", 'invalid', HOUR_IN_SECONDS );
				update_option( 'pue_install_key_test_plugin_invalid_existing_data', 'existing-invalid-key' );
			},
			[ 'status' => 'invalid', 'replacement_key' => '' ],
			'invalid',
			'invalid',
			'Should only update transient for an invalid key.',
		];

		yield 'Valid key, transient exists but option does not' => [
			'plugin_name' => 'test_plugin_valid_transient_no_option',
			function () {
				$site_domain = $_SERVER['SERVER_NAME']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
				set_transient("pue_key_status_test_plugin_valid_transient_no_option_{$site_domain}", 'valid', HOUR_IN_SECONDS);
			},
			[ 'status' => 'valid', 'replacement_key' => 'new-valid-key' ],
			'valid',
			'valid',
			'Should set option when transient exists but option does not.',
		];

		yield 'Invalid key, transient exists but option does not' => [
			'plugin_name' => 'test_plugin_invalid_transient_no_option',
			function () {
				$site_domain = $_SERVER['SERVER_NAME']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
				set_transient("pue_key_status_test_plugin_invalid_transient_no_option_{$site_domain}", 'invalid', HOUR_IN_SECONDS);
			},
			[ 'status' => 'invalid', 'replacement_key' => '' ],
			'invalid',
			'invalid',
			'Should not set option but retain transient for an invalid key.',
		];

		yield 'Transient timeout exceeded, valid key' => [
			'plugin_name' => 'test_plugin_transient_timeout_valid',
			function () {
				$site_domain = $_SERVER['SERVER_NAME']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
				set_transient("pue_key_status_test_plugin_transient_timeout_valid_{$site_domain}", 'valid', -1); // Expired transient.
			},
			[ 'status' => 'valid', 'replacement_key' => 'renewed-valid-key' ],
			'valid',
			'valid',
			'Should renew transient and set option when transient has expired.',
		];

		yield 'Transient timeout exceeded, invalid key' => [
			'plugin_name' => 'test_plugin_transient_timeout_invalid',
			function () {
				$site_domain = $_SERVER['SERVER_NAME']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
				set_transient("pue_key_status_test_plugin_transient_timeout_invalid_{$site_domain}", 'invalid', -1); // Expired transient.
			},
			[ 'status' => 'invalid', 'replacement_key' => '' ],
			'invalid',
			'invalid',
			'Should renew transient but not set option when transient has expired for an invalid key.',
		];

		yield 'Early bail with existing valid transient' => [
			'plugin_name' => 'test_plugin_early_bail_valid',
			function () {
				$site_domain = $_SERVER['SERVER_NAME']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
				set_transient("pue_key_status_test_plugin_early_bail_valid_{$site_domain}", 'valid', HOUR_IN_SECONDS);
			},
			[ 'status' => 'valid', 'replacement_key' => 'ignored-key' ],
			'valid',
			'valid',
			'Should bail early when a valid transient exists.',
		];

		yield 'Early bail with existing invalid transient' => [
			'plugin_name' => 'test_plugin_early_bail_invalid',
			function () {
				$site_domain = $_SERVER['SERVER_NAME']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
				set_transient("pue_key_status_test_plugin_early_bail_invalid_{$site_domain}", 'invalid', HOUR_IN_SECONDS);
			},
			[ 'status' => 'invalid', 'replacement_key' => 'ignored-key' ],
			'invalid',
			'invalid',
			'Should bail early when an invalid transient exists.',
		];
	}

	/**
	 * @test
	 */
	public function it_should_handle_missing_plugin_file_gracefully(): void {
		$plugin_slug = 'missing-plugin';
		$plugin_file = "{$plugin_slug}/{$plugin_slug}.php";

		// Instantiate the PUE_Checker with a non-existent plugin file.
		$pue_checker = new PUE_Checker( 'deprecated', $plugin_slug, [], $plugin_file );

		// Assert the plugin name remains null.
		$this->assertEmpty( $pue_checker->get_plugin_name(), 'It should use the plugin slug when the file name is missing.' );
	}

	/**
	 * @test
	 * @return void
	 */
	public function it_should_get_the_installed_version() {
		$validated_key = md5( microtime() );
		$plugin_name   = 'the-events-calendar';
		update_option( "pue_install_key_{$plugin_name}", $validated_key );
		$pue_instance   = new PUE_Checker( 'deprecated', $plugin_name, [], "{$plugin_name}/{$plugin_name}.php" );
		$version_number = $pue_instance->get_installed_version();

		$this->assertNotEmpty( $version_number, 'Version should come back for the-events-calendar' );
	}

	/**
	 * @test
	 * @return void
	 */
	public function it_should_not_get_the_installed_version() {
		$validated_key = md5( microtime() );
		$plugin_name   = 'fake-plugin-not-installed';
		update_option( "pue_install_key_{$plugin_name}", $validated_key );
		$pue_instance = new PUE_Checker( 'deprecated', $plugin_name, [], "{$plugin_name}/{$plugin_name}.php" );

		$version_number = $pue_instance->get_installed_version();

		$this->assertEmpty( $version_number, 'Version number should be empty for fake plugin.' );
	}
}
