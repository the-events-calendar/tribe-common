<?php

namespace Tribe\PUE;

use Closure;
use Codeception\TestCase\WPTestCase;
use Generator;
use TEC\Common\StellarWP\Uplink\Register;
use TEC\Common\Tests\Licensing\PUE_Service_Mock;
use Tribe__Main;
use Tribe__PUE__Checker as PUE_Checker;
use TEC\Common\StellarWP\Uplink\Auth\Token\Contracts\Token_Manager;
use TEC\Common\StellarWP\Uplink\Resources\Collection;
use TEC\Common\StellarWP\Uplink\Storage\Contracts\Storage;

use function TEC\Common\StellarWP\Uplink\get_resource;

class Checker_Test extends WPTestCase {
	/**
	 * @var PUE_Service_Mock
	 */
	private $pue_service_mock;

	/**
	 * @var array
	 */
	protected $temp_pue_plugin = [];

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

	public function test_replacemnt_key_update_in_multisite_context(): void {}

	/**
	 * It should validate licenses correctly across various scenarios.
	 *
	 * @test
	 * @dataProvider license_validation_data_provider
	 */
	public function should_is_any_license_valid_return_correctly( Closure $setup_closure, $expected_result, $message ): void {
		// Clean up before each scenario.
		$this->clean_up_test_options();

		// Run the setup closure to configure the test scenario.
		$setup_closure();

		// Assert the expected outcome.
		$this->assertEquals( $expected_result, PUE_Checker::is_any_license_valid(), $message );
	}

	/**
	 * Cleans up transient and options dynamically based on plugins in $temp_pue_plugin.
	 */
	private function clean_up_test_options(): void {
		// Clear transient.
		delete_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY );

		// Clear dynamic options based on the plugins.
		foreach ( $this->temp_pue_plugin as $plugin_name ) {
			delete_option( "pue_install_key_{$plugin_name}" );
		}

		// Reset the array to ensure no carryover between tests.
		$this->temp_pue_plugin = [];
	}

	/**
	 * Data provider for license validation test scenarios.
	 *
	 * @return Generator
	 */
	public function license_validation_data_provider(): Generator {
		yield 'initially_unlicensed' => [
			function () {
				// No setup needed, all licenses are invalid initially.
			},
			false,
			'Initially unlicensed should return invalid.',
		];

		yield 'license_a_plugin' => [
			function () {
				$validated_key           = md5( microtime() );
				$plugin_name             = 'test-plugin-1';
				$this->temp_pue_plugin[] = $plugin_name;
				update_option( "pue_install_key_{$plugin_name}", $validated_key );
				$pue_instance = new PUE_Checker( 'deprecated', $plugin_name, [], "{$plugin_name}/{$plugin_name}.php" );
				$pue_instance->set_key_status( 1 ); // Set valid status.
			},
			true,
			'Licensing a plugin should make is_any_license_valid return valid.',
		];

		yield 'transient_deleted' => [
			function () {
				$validated_key           = md5( microtime() );
				$plugin_name             = 'test-plugin-1';
				$this->temp_pue_plugin[] = $plugin_name;
				update_option( "pue_install_key_{$plugin_name}", $validated_key );
				$pue_instance = new PUE_Checker( 'deprecated', $plugin_name, [], "{$plugin_name}/{$plugin_name}.php" );
				$pue_instance->set_key_status( 1 ); // Set valid status.
				delete_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY ); // Simulate transient deletion.
			},
			true,
			'Deleting transient should trigger revalidation and return valid if at least one license is valid.',
		];

		yield 'multiple_plugins_with_even_valid' => [
			function () {
				for ( $i = 1; $i <= 10; $i++ ) {
					$validated_key           = md5( microtime() . $i );
					$plugin_name             = "test-plugin-{$i}";
					$this->temp_pue_plugin[] = $plugin_name;
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
			},
			true,
			'At least one valid license (even-numbered plugins) should make is_any_license_valid return valid.',
		];

		yield 'all_plugins_invalid' => [
			function () {
				for ( $i = 1; $i <= 10; $i++ ) {
					$validated_key           = md5( microtime() . $i );
					$plugin_name             = "test-plugin-{$i}";
					$this->temp_pue_plugin[] = $plugin_name;
					update_option( "pue_install_key_{$plugin_name}", $validated_key );

					$pue_instance = new PUE_Checker( 'deprecated', $plugin_name, [], "{$plugin_name}/{$plugin_name}.php" );
					// All plugins are set as invalid.
					$pue_instance->set_key_status( 0 );
				}
			},
			false,
			'When all plugins are invalid, is_any_license_valid should return false.',
		];

		yield 'Testing Uplink' => [
			function () {
				$key = 'license-key-for-test-plugin';
				$collection    = tribe( Collection::class );
				$resource      = $collection->get( 'test-plugin' );
				$token_manager = tribe( Token_Manager::class );
				$storage       = tribe( Storage::class );
				$resource->set_license_key( $key );
				$this->assertEquals( $key, $resource->get_license_key() );
				$storage->set(
					'stellarwp_auth_url_tec_seating',
					'https://my.theeventscalendar.com/account-auth/?uplink_callback=aHR0cHM6Ly90ZWNkZXYubG5kby5zaXRlL3dwLWFkbWluL2FkbWluLnBocD9wYWdlPXRlYy10aWNrZXRzLXNldHRpbmdzJnRhYj1saWNlbnNlcyZ1cGxpbmtfc2x1Zz10ZWMtc2VhdGluZyZfdXBsaW5rX25vbmNlPU1zb3ptQlZJVUp4aFh6c0Q%3D'
				);
				$token_manager->store( $key, $resource );
			},
			true,
			'Testing uplink',
		];
	}
}
