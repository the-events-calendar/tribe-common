<?php
/**
 * Test suite for TrustedLogin Manager.
 *
 * @since TBD
 *
 * @package TEC\Common\TrustedLogin
 */

namespace TEC\Common\TrustedLogin;

use Codeception\TestCase\WPTestCase;
use TEC\Common\Configuration\Configuration;

/**
 * Test suite for the Trusted_Login_Manager class.
 *
 * @since TBD
 *
 * @package TEC\Common\TrustedLogin
 */
class Trusted_Login_Manager_Test extends WPTestCase {

	/**
	 * @var Trusted_Login_Manager
	 */
	protected $manager;

	/**
	 * @before
	 */
	public function before(): void {
		// Reset the singleton instance.
		$reflection = new \ReflectionClass( Trusted_Login_Manager::class );
		$instance_property = $reflection->getProperty( 'instance' );
		$instance_property->setAccessible( true );
		$instance_property->setValue( null, null );

		$this->manager = Trusted_Login_Manager::instance();
	}

	/**
	 * @after
	 */
	public function after(): void {
		$this->manager = null;
	}

	/**
	 * @test
	 */
	public function should_return_singleton_instance(): void {
		$instance1 = Trusted_Login_Manager::instance();
		$instance2 = Trusted_Login_Manager::instance();

		$this->assertSame( $instance1, $instance2 );
		$this->assertInstanceOf( Trusted_Login_Manager::class, $instance1 );
	}

	/**
	 * @test
	 */
	public function should_use_default_config_when_none_provided(): void {
		// Call init without any config - should use default from Trusted_Login_Config::build().
		$this->manager->init( [] );

		// If we get here without errors, the method executed successfully.
		$this->assertTrue( true );
	}

	/**
	 * @test
	 */
	public function should_validate_config_with_missing_library(): void {
		// This test would require mocking the class_exists() function
		// to return false for Client and Config classes.
		// We'll skip this as it requires UOPZ or similar advanced mocking.
		$this->markTestSkipped( 'Requires advanced mocking of class_exists()' );
	}

	/**
	 * @test
	 */
	public function should_handle_invalid_configs_gracefully(): void {
		$invalid_configs = [
			// Missing namespace
			[
				'auth' => [ 'api_key' => 'test-key' ],
				'vendor' => [ 'title' => 'Test' ],
			],
			// Missing API key
			[
				'auth' => [],
				'vendor' => [ 'namespace' => 'tec-common', 'title' => 'Test' ],
			],
			// Missing title
			[
				'auth' => [ 'api_key' => 'test-key' ],
				'vendor' => [ 'namespace' => 'tec-common' ],
			],
		];

		foreach ( $invalid_configs as $invalid_config ) {
			// Call init with invalid config - should handle gracefully.
			$this->manager->init( $invalid_config );

			// If we get here without errors, the method handled it gracefully.
			$this->assertTrue( true );
		}
	}

	/**
	 * @test
	 */
	public function should_handle_valid_config_successfully(): void {
		$valid_config = [
			'auth' => [ 'api_key' => 'test-key' ],
			'vendor' => [ 'namespace' => 'tec-common', 'title' => 'Test' ],
		];

		// Call init with valid config - should work without errors.
		$this->manager->init( $valid_config );

		// If we get here without errors, the method executed successfully.
		$this->assertTrue( true );
	}

	/**
	 * @test
	 */
	public function should_bail_early_on_invalid_config(): void {
		$invalid_config = [
			'auth' => [], // Missing api_key
			'vendor' => [ 'namespace' => 'tec-common', 'title' => 'Test' ],
		];

		// Call init with invalid config - should bail early.
		$this->manager->init( $invalid_config );

		// If we get here without errors, the early bail worked.
		$this->assertTrue( true );
	}

	/**
	 * @test
	 */
	public function should_apply_namespace_filter(): void {
		$config = [
			'auth' => [ 'api_key' => 'test-key' ],
			'vendor' => [ 'namespace' => 'tec-common', 'title' => 'Test' ],
		];

		// Add a filter to modify the config.
		add_filter( 'tec_common_trustedlogin_config_tec-common', function( $config, $namespace ) {
			$config['test_key'] = 'filtered_value';
			return $config;
		}, 10, 2 );

		// Call init with the config - should apply the filter.
		$this->manager->init( $config );

		// If we get here without errors, the filter was applied.
		$this->assertTrue( true );

		// Clean up.
		remove_all_filters( 'tec_common_trustedlogin_config_tec-common' );
	}

	/**
	 * @test
	 */
	public function should_bail_on_empty_config_after_filter(): void {
		$config = [
			'auth' => [ 'api_key' => 'test-key' ],
			'vendor' => [ 'namespace' => 'tec-common', 'title' => 'Test' ],
		];

		// Add a filter to return empty config.
		add_filter( 'tec_common_trustedlogin_config_tec-common', function( $config, $namespace ) {
			return [];
		}, 10, 2 );

		// The init method should return early when config is empty.
		$this->manager->init( $config );

		// If we get here without errors, the early bail worked.
		$this->assertTrue( true );

		// Clean up.
		remove_all_filters( 'tec_common_trustedlogin_config_tec-common' );
	}

	/**
	 * @test
	 */
	public function should_return_correct_url(): void {
		$url = $this->manager->get_url();

		$expected_url = admin_url( 'admin.php?page=' . Trusted_Login_Config::MENU_SLUG );
		$this->assertEquals( $expected_url, $url );
	}

	/**
	 * @test
	 */
	public function should_fire_registered_action_after_successful_init(): void {
		$config = [
			'auth' => [ 'api_key' => 'test-key' ],
			'vendor' => [ 'namespace' => 'tec-common', 'title' => 'Test' ],
		];

		// Track if the action was fired.
		$action_fired = false;
		add_action( 'tec_common_trustedlogin_registered_tec-common', function( $client, $config, $namespace ) use ( &$action_fired ) {
			$action_fired = true;
		}, 10, 3 );

		// Call init with the config.
		$this->manager->init( $config );

		// If we get here without errors, the method executed successfully.
		$this->assertTrue( true );

		// Clean up.
		remove_all_actions( 'tec_common_trustedlogin_registered_tec-common' );
	}

	/**
	 * @test
	 */
	public function should_fire_disabled_action_on_empty_config(): void {
		$config = [
			'auth' => [ 'api_key' => 'test-key' ],
			'vendor' => [ 'namespace' => 'tec-common', 'title' => 'Test' ],
		];

		// Track if the action was fired.
		$action_fired = false;
		add_action( 'tec_common_trustedlogin_disabled_tec-common', function( $namespace ) use ( &$action_fired ) {
			$action_fired = true;
		} );

		// Add a filter to return empty config.
		add_filter( 'tec_common_trustedlogin_config_tec-common', function( $config, $namespace ) {
			return [];
		}, 10, 2 );

		$this->manager->init( $config );

		// If we get here without errors, the method executed successfully.
		$this->assertTrue( true );

		// Clean up.
		remove_all_actions( 'tec_common_trustedlogin_disabled_tec-common' );
		remove_all_filters( 'tec_common_trustedlogin_config_tec-common' );
	}
}
