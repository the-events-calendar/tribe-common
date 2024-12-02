<?php

namespace Tribe\PUE;

use TEC\Common\Tests\Licensing\PUE_Service_Mock;
use Tribe__PUE__Checker as PUE_Checker;
use Tribe__Main;
use TEC\Common\StellarWP\Uplink\Register;
use function TEC\Common\StellarWP\Uplink\get_resource;

class Checker_Test extends \Codeception\TestCase\WPTestCase {
	/**
	 * @var PUE_Service_Mock
	 */
	private $pue_service_mock;

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
		delete_option( 'pue_install_key_test_plugin');
		$validated_key = md5( microtime() );
		$body = $this->pue_service_mock->get_validate_key_success_body();
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
		delete_option( 'pue_install_key_test_plugin');
		$validated_key = md5( microtime() );
		$body = $this->pue_service_mock->get_validate_key_success_body();
		// Add an empty replacement key to the response body.
		$body['results'][0]['replacement_key'] = '';
		$mock_response = $this->pue_service_mock->make_response( 200, $body, 'application/json' );
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
		delete_option( 'pue_install_key_test_plugin');
		// Set the response mock to provide a replacement key.
		$replacement_key = '2222222222222222222222222222222222222222';
		$body = $this->pue_service_mock->get_validate_key_success_body();
		// Add a replacement key to the response body.
		$body['results'][0]['replacement_key'] = $replacement_key;
		$mock_response = $this->pue_service_mock->make_response( 200, $body, 'application/json' );
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
		$body = $this->pue_service_mock->get_validate_key_success_body();
		// Add a replacement key to the response body.
		$body['results'][0]['replacement_key'] = $replacement_key;
		$mock_response = $this->pue_service_mock->make_response( 200, $body, 'application/json' );
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

	public function test_replacemnt_key_update_in_multisite_context(): void {

	}

	/**
	 * It should correctly determine license validity across different scenarios.
	 *
	 * @test
	 */
	public function should_correctly_determine_is_any_license_valid_across_scenarios(): void {
		// Clean up before starting.
		delete_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY );
		delete_option( 'pue_install_key_test_plugin_1' );
		delete_option( 'pue_install_key_test_plugin_2' );

		// Scenario 1: Initially unlicensed.
		$this->assertFalse( PUE_Checker::is_any_license_valid(), 'Initially unlicensed should return invalid.' );

		// Scenario 2: Initially unlicensed, then license a plugin.
		$validated_key_1 = md5( microtime() );
		update_option( 'pue_install_key_test_plugin_1', $validated_key_1 );
		$pue_instance_1 = new PUE_Checker( 'deprecated', 'test-plugin-1', [], 'test-plugin-1/test-plugin.php' );
		$pue_instance_1->set_key_status( 1 ); // Set valid status.
		$this->assertTrue( PUE_Checker::is_any_license_valid(), 'Licensing a plugin should make is_any_license_valid return valid.' );

		// Scenario 3: Initially licensed.
		delete_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY ); // Reset transient.
		$this->assertTrue( PUE_Checker::is_any_license_valid(), 'Initially licensed should return valid.' );

		// Scenario 4: Initially unlicensed, license a plugin, then unlicense.
		delete_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY ); // Reset transient.
		delete_option( 'pue_install_key_test_plugin_1' );
		$pue_instance_1->set_key_status( 0 ); // Set invalid status.
		$this->assertFalse( PUE_Checker::is_any_license_valid(), 'Initially unlicensed should return invalid.' );

		// Re-license the plugin.
		update_option( 'pue_install_key_test_plugin_1', $validated_key_1 );
		$pue_instance_1->set_key_status( 1 ); // Set valid status.
		$this->assertTrue( PUE_Checker::is_any_license_valid(), 'Licensing the plugin again should make is_any_license_valid return valid.' );

		// Unlicense the plugin.
		$pue_instance_1->set_key_status( 0 ); // Set invalid status.
		$this->assertFalse( PUE_Checker::is_any_license_valid(), 'Unlicensing the only valid plugin should make is_any_license_valid return invalid.' );
	}

	/**
	 * It should handle edge cases for multiple licenses and transient behavior.
	 *
	 * @test
	 */
	public function should_handle_edge_cases_for_is_any_license_valid_and_transient_behavior(): void {
		// Clean up before starting.
		delete_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY );
		delete_option( 'pue_install_key_test_plugin_1' );
		delete_option( 'pue_install_key_test_plugin_2' );

		// Scenario 1: Multiple plugins, one licensed, one unlicensed.
		$validated_key_1 = md5( microtime() );
		update_option( 'pue_install_key_test_plugin_1', $validated_key_1 );
		$pue_instance_1 = new PUE_Checker( 'deprecated', 'test-plugin-1', [], 'test-plugin-1/test-plugin.php' );
		$pue_instance_1->set_key_status( 1 ); // Set valid status for first plugin.

		$validated_key_2 = md5( microtime() );
		update_option( 'pue_install_key_test_plugin_2', $validated_key_2 );
		$pue_instance_2 = new PUE_Checker( 'deprecated', 'test-plugin-2', [], 'test-plugin-2/test-plugin.php' );
		$pue_instance_2->set_key_status( 0 ); // Set invalid status for second plugin.

		$this->assertTrue( PUE_Checker::is_any_license_valid(), 'At least one valid license should make is_any_license_valid return valid.' );

		// Scenario 2: All plugins unlicensed.
		$pue_instance_1->set_key_status( 0 ); // Unlicense first plugin.
		$this->assertFalse( PUE_Checker::is_any_license_valid(), 'When all plugins are unlicensed, is_any_license_valid should return invalid.' );

		// Scenario 3: Transient manually deleted.
		delete_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY );
		$this->assertFalse( PUE_Checker::is_any_license_valid(), 'Deleting transient should trigger revalidation and return invalid if all plugins are unlicensed.' );

		// Scenario 4: Invalid transient value.
		set_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY, 'unknown', HOUR_IN_SECONDS );
		$this->assertFalse( PUE_Checker::is_any_license_valid(), 'An invalid transient value should trigger revalidation and return invalid if no licenses are valid.' );

		// Scenario 5: Revalidate licenses after invalid transient.
		$pue_instance_1->set_key_status( 1 ); // License the first plugin.
		$this->assertTrue( PUE_Checker::is_any_license_valid(), 'Revalidating after an invalid transient should return valid if at least one license is valid.' );
	}

	/**
	 * It should validate licenses correctly across multiple plugins
	 *
	 * @test
	 */
	public function should_validate_is_any_license_valid_across_multiple_plugins(): void {
		// Clean up any existing transient or options.
		delete_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY );
		delete_option( 'pue_install_key_test_plugin_1' );
		delete_option( 'pue_install_key_test_plugin_2' );

		// Step 1: No license, validate `is_any_license_valid` is false.
		$is_valid = PUE_Checker::is_any_license_valid();
		$this->assertFalse( $is_valid, 'Initially, no licenses should be valid.' );

		// Step 2: Install a plugin with a valid license.
		$validated_key_1 = md5( microtime() );
		update_option( 'pue_install_key_test_plugin_1', $validated_key_1 );
		$pue_instance_1 = new PUE_Checker( 'deprecated', 'test-plugin-1', [], 'test-plugin-1/test-plugin.php' );
		$pue_instance_1->set_key_status( 1 ); // Set valid status.

		// Validate that `is_any_license_valid` is true.
		$is_valid = PUE_Checker::is_any_license_valid();
		$this->assertTrue( $is_valid, 'After licensing one plugin, `is_any_license_valid` should return true.' );

		// Step 3: Install another plugin with an invalid license.
		$validated_key_2 = md5( microtime() );
		update_option( 'pue_install_key_test_plugin_2', $validated_key_2 );
		$pue_instance_2 = new PUE_Checker( 'deprecated', 'test-plugin-2', [], 'test-plugin-2/test-plugin.php' );
		$pue_instance_2->set_key_status( 0 ); // Set invalid status.

		// Validate that `is_any_license_valid` is still true.
		$is_valid = PUE_Checker::is_any_license_valid();
		$this->assertTrue( $is_valid, 'After adding a plugin with an invalid license, `is_any_license_valid` should still return true as one plugin is valid.' );
	}

}
