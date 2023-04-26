<?php

namespace Tribe\PUE;

use TEC\Common\Tests\Licensing\PUE_Service_Mock;
use Tribe\Tests\Traits\With_Uopz;
use Tribe__PUE__Checker as PUE_Checker;

class Replacement_Key_Checker_Test extends \Codeception\TestCase\WPTestCase {
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
	 * @before
	 */
	public function set_plugin_active_for_network(): void {
		$this->set_fn_return( 'is_plugin_active_for_network', function ( string $plugin ): bool {
			return $plugin === 'test-plugin/test-plugin.php' || is_plugin_active_for_network( $plugin );
		}, true );
	}

	/**
	 * It should not update local license key if replacement key not provided
	 *
	 * @test
	 */
	public function should_not_update_local_license_key_if_replacement_key_not_provided(): void {
		// Ensure there is no key set.
		delete_option( 'pue_install_key_test_plugin' );
		$original_key = md5( microtime() );
		$body = $this->pue_service_mock->get_validate_key_success_body();
		$mock_response = $this->pue_service_mock->make_response( 200, $body, 'application/json' );
		$this->pue_service_mock->will_reply_to_request( 'POST', '/plugins/v2/license/validate', $mock_response );

		$pue_instance = new PUE_Checker( 'deprecated', 'test-plugin', [], 'test-plugin/test-plugin.php' );
		$pue_instance->validate_key( $original_key, false );

		$this->assertEquals( $original_key, $pue_instance->get_key() );
	}

	/**
	 * It should not update local license key if replacement key is empty
	 *
	 * @test
	 */
	public function should_not_update_local_license_key_if_replacement_key_is_empty(): void {
		// Ensure there is no key set.
		delete_option( 'pue_install_key_test_plugin' );
		$original_key = md5( microtime() );
		$body = $this->pue_service_mock->get_validate_key_success_body();
		// Add an empty replacement key to the response body.
		$body['results'][0]['replacement_key'] = '';
		$mock_response = $this->pue_service_mock->make_response( 200, $body, 'application/json' );
		$this->pue_service_mock->will_reply_to_request( 'POST', '/plugins/v2/license/validate', $mock_response );

		$pue_instance = new PUE_Checker( 'deprecated', 'test-plugin', [], 'test-plugin/test-plugin.php' );
		$pue_instance->validate_key( $original_key, false );

		$this->assertEquals( $original_key, $pue_instance->get_key() );
	}

	/**
	 * It should update local license key if replacement key provided and key not previously set
	 *
	 * @test
	 */
	public function should_update_local_license_key_if_replacement_key_provided_and_key_not_previously_set(): void {
		// Ensure there is no key set.
		delete_option( 'pue_install_key_test_plugin' );
		$original_key = md5( microtime() );
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
	 * It should update local license key if replacement key provided and key previously set
	 *
	 * @test
	 */
	public function should_update_local_license_key_if_replacement_key_provided_and_key_previously_set(): void {
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
	 * It should set not previosly set network key to validated key when replacement key not provided
	 *
	 * @test
	 */
		public function should_set_network_key_to_validated_key_when_not_previously_set_and_replacement_not_provided(): void {
		$validated_key = md5( microtime() );
		// Ensure there is no license key locally or network wide.
		delete_option( 'pue_install_key_test_plugin' );
		delete_network_option( get_current_network_id(), 'pue_install_key_test_plugin' );
		$body = $this->pue_service_mock->get_validate_key_success_body();
		$mock_response = $this->pue_service_mock->make_response( 200, $body, 'application/json' );
		$this->pue_service_mock->will_reply_to_request( 'POST', '/plugins/v2/license/validate', $mock_response );

		$pue_instance = new PUE_Checker( 'deprecated', 'test-plugin', [], 'test-plugin/test-plugin.php' );
		$pue_instance->validate_key( $validated_key, true );

		$this->assertEquals( $validated_key, $pue_instance->get_key() );
	}

	/**
	 * @test
	 */
		public function should_set_network_key_to_validated_key_when_not_previously_set_and_replacement_key_empty(): void {
		$validated_key = md5( microtime() );
		// Ensure there is no license key locally or network wide.
		delete_option( 'pue_install_key_test_plugin' );
		delete_network_option( get_current_network_id(), 'pue_install_key_test_plugin' );
		$body = $this->pue_service_mock->get_validate_key_success_body();
		// Add a replacement key to the response body.
		$body['results'][0]['replacement_key'] = '';
		$mock_response = $this->pue_service_mock->make_response( 200, $body, 'application/json' );
		$this->pue_service_mock->will_reply_to_request( 'POST', '/plugins/v2/license/validate', $mock_response );

		$pue_instance = new PUE_Checker( 'deprecated', 'test-plugin', [], 'test-plugin/test-plugin.php' );
		$pue_instance->validate_key( $validated_key, true );

		$this->assertEquals( $validated_key, $pue_instance->get_key() );
	}

	/**
	 * @test
	 */
	public function should_set_network_key_to_provided_replacement_key_when_not_previously_set(): void {
		$validated_key = md5( microtime() );
		// Ensure there is no license key locally or network wide.
		delete_option( 'pue_install_key_test_plugin' );
		delete_network_option( get_current_network_id(), 'pue_install_key_test_plugin' );
		$body = $this->pue_service_mock->get_validate_key_success_body();
		// Add a replacement key to the response body.
		$replacement_key = '2222222222222222222222222222222222222222';
		$body['results'][0]['replacement_key'] = $replacement_key;
		$mock_response = $this->pue_service_mock->make_response( 200, $body, 'application/json' );
		$this->pue_service_mock->will_reply_to_request( 'POST', '/plugins/v2/license/validate', $mock_response );

		$pue_instance = new PUE_Checker( 'deprecated', 'test-plugin', [], 'test-plugin/test-plugin.php' );
		$pue_instance->validate_key( $validated_key, true );

		$this->assertEquals( $replacement_key, $pue_instance->get_key() );
	}


	/**
	 * It should set previously set network key to replacement key if provided
	 *
	 * @test
	 */
	public function should_set_previously_set_network_key_to_replacement_key_if_provided() {
		$validated_key = md5( microtime() );
		// Ensure there is no license key locally or network wide.
		delete_option( 'pue_install_key_test_plugin' );
		update_network_option( get_current_network_id(), 'pue_install_key_test_plugin', 'previous-network-key' );
		$body = $this->pue_service_mock->get_validate_key_success_body();
		// Add a replacement key to the response body.
		$replacement_key = '2222222222222222222222222222222222222222';
		$body['results'][0]['replacement_key'] = $replacement_key;
		$mock_response = $this->pue_service_mock->make_response( 200, $body, 'application/json' );
		$this->pue_service_mock->will_reply_to_request( 'POST', '/plugins/v2/license/validate', $mock_response );

		$pue_instance = new PUE_Checker( 'deprecated', 'test-plugin', [], 'test-plugin/test-plugin.php' );
		$pue_instance->validate_key( $validated_key, true );

		$this->assertEquals( $replacement_key, $pue_instance->get_key() );
	}
}
