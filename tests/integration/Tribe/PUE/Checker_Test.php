<?php

namespace Tribe\PUE;

use TEC\Common\Tests\Licensing\PUE_Service_Mock;
use Tribe\Tests\Traits\With_Uopz;
use Tribe__PUE__Checker as PUE_Checker;

class Checker_Test extends \Codeception\TestCase\WPTestCase {
	use With_Uopz;

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
	 * Check that the is_any_license_valid can reference the Checker instance and validate the key.
	 *
	 * @test
	 */
	public function should_validate_any_license(): void {
		// At least one registered.
		new PUE_Checker( 'deprecated', 'test-plugin', [], 'test-plugin/test-plugin.php' );

		// Checker local valid key should precede anything else and flag valid.
		$this->set_class_fn_return( \Tribe__PUE__Checker::class, 'is_key_valid', true );
		$valid = PUE_Checker::is_any_license_valid();
		$this->assertTrue( $valid );

		// Checker will be invalid if the local is invalid and no valid license to use for a request.
		delete_transient(PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY );
		$this->set_class_fn_return( PUE_Checker::class, 'is_key_valid', false );
		$valid = PUE_Checker::is_any_license_valid();
		$this->assertFalse( $valid );

		// Now mock a valid response when validating a key.
		delete_transient(PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY );
		$response = [ 'status' => 1 ];
		$this->set_class_fn_return( PUE_Checker::class, 'validate_key', $response );
		$valid = PUE_Checker::is_any_license_valid();
		$this->assertTrue( $valid );
	}
}
