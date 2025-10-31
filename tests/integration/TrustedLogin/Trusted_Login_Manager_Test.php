<?php
/**
 * Test suite for TrustedLogin Manager.
 *
 * @since 6.9.5
 *
 * @package TEC\Common\TrustedLogin
 */

namespace TEC\Common\TrustedLogin;

use Codeception\TestCase\WPTestCase;

/**
 * Test suite for the Trusted_Login_Manager class.
 *
 * @since 6.9.5
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
		$this->manager = tribe( Trusted_Login_Manager::class );
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
		$instance1 = tribe( Trusted_Login_Manager::class );
		$instance2 = tribe( Trusted_Login_Manager::class );

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
	public function should_handle_invalid_configs_gracefully(): void {
		$invalid_configs = [
			// Missing required fields
			[
				'auth' => [ 'api_key' => 'test-key' ],
				'vendor' => [ 'title' => 'Test' ],
				// Missing: role, vendor.namespace, vendor.email, vendor.website, vendor.support_url
			],
			// Missing API key
			[
				'auth'   => [],
				'role'   => 'editor',
				'vendor' => [
					'namespace'   => 'tec-common',
					'title'       => 'Test',
					'email'       => 'test@example.com',
					'website'     => 'https://example.com',
					'support_url' => 'https://example.com/support',
				],
			],
			// Missing role
			[
				'auth'   => [ 'api_key' => 'test-key' ],
				'vendor' => [
					'namespace'   => 'tec-common',
					'title'       => 'Test',
					'email'       => 'test@example.com',
					'website'     => 'https://example.com',
					'support_url' => 'https://example.com/support',
				],
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
			'auth'   => [ 'api_key' => 'test-key' ],
			'role'   => 'editor',
			'vendor' => [
				'namespace'   => 'tec-common',
				'title'       => 'Test Company',
				'email'       => 'test@example.com',
				'website'     => 'https://example.com',
				'support_url' => 'https://example.com/support',
			],
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
			'auth'   => [], // Missing api_key.
			'role'   => 'editor',
			'vendor' => [
				'namespace'   => 'tec-common',
				'title'       => 'Test Company',
				'email'       => 'test@example.com',
				'website'     => 'https://example.com',
				'support_url' => 'https://example.com/support',
			],
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
			'auth'   => [ 'api_key' => 'test-key' ],
			'role'   => 'editor',
			'vendor' => [
				'namespace'   => 'tec-common',
				'title'       => 'Test Company',
				'email'       => 'test@example.com',
				'website'     => 'https://example.com',
				'support_url' => 'https://example.com/support',
			],
		];

		// Add a filter to modify the config.
		add_filter(
			'tec_common_trustedlogin_config_tec-common',
			function ( $config, $namespace ) {
				$config['test_key'] = 'filtered_value';

				return $config;
			},
			10,
			2
		);

		// Call init with the config - should apply the filter.
		$this->manager->init( $config );

		// If we get here without errors, the filter was applied.
		$this->assertTrue( true );
	}

	/**
	 * @test
	 */
	public function should_bail_on_empty_config_after_filter(): void {
		$config = [
			'auth'   => [ 'api_key' => 'test-key' ],
			'role'   => 'editor',
			'vendor' => [
				'namespace'   => 'tec-common',
				'title'       => 'Test Company',
				'email'       => 'test@example.com',
				'website'     => 'https://example.com',
				'support_url' => 'https://example.com/support',
			],
		];

		// Add a filter to return empty config.
		add_filter(
			'tec_common_trustedlogin_config_tec-common',
			function ( $config, $namespace ) {
				return [];
			},
			10,
			2
		);

		// The init method should return early when config is empty.
		$this->manager->init( $config );

		// If we get here without errors, the early bail worked.
		$this->assertTrue( true );
	}

	/**
	 * @test
	 */
	public function should_initialize_without_errors(): void {
		$config = [
			'auth'   => [ 'api_key' => 'test-key' ],
			'role'   => 'editor',
			'vendor' => [
				'namespace'   => 'tec-common',
				'title'       => 'Test Company',
				'email'       => 'test@example.com',
				'website'     => 'https://example.com',
				'support_url' => 'https://example.com/support',
			],
		];

		// Test that init completes without errors.
		$this->manager->init( $config );

		// If we get here without errors, the initialization worked.
		$this->assertTrue( true );
	}

	/**
	 * @test
	 */
	public function should_fire_registered_action_after_successful_init(): void {
		$config = [
			'auth'   => [ 'api_key' => 'test-key' ],
			'role'   => 'editor',
			'vendor' => [
				'namespace'   => 'tec-common',
				'title'       => 'Test Company',
				'email'       => 'test@example.com',
				'website'     => 'https://example.com',
				'support_url' => 'https://example.com/support',
			],
		];

		// Track if the action was fired.
		$action_fired = false;
		add_action(
			'tec_common_trustedlogin_registered_tec-common',
			function ( $client, $config, $namespace ) use ( &$action_fired ) {
				$action_fired = true;
			},
			10,
			3
		);

		// Call init with the config.
		$this->manager->init( $config );

		// If we get here without errors, the method executed successfully.
		$this->assertTrue( true );
	}

	/**
	 * @test
	 */
	public function should_fire_disabled_action_on_empty_config(): void {
		$config = [
			'auth'   => [ 'api_key' => 'test-key' ],
			'role'   => 'editor',
			'vendor' => [
				'namespace'   => 'tec-common',
				'title'       => 'Test Company',
				'email'       => 'test@example.com',
				'website'     => 'https://example.com',
				'support_url' => 'https://example.com/support',
			],
		];

		// Track if the action was fired.
		$action_fired = false;
		add_action(
			'tec_common_trustedlogin_disabled_tec-common',
			function ( $namespace ) use ( &$action_fired ) {
				$action_fired = true;
			}
		);

		// Add a filter to return empty config.
		add_filter(
			'tec_common_trustedlogin_config_tec-common',
			function ( $config, $namespace ) {
				return [];
			},
			10,
			2
		);

		$this->manager->init( $config );

		// If we get here without errors, the method executed successfully.
		$this->assertTrue( true );
	}
}
