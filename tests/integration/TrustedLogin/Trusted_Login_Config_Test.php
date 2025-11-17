<?php
/**
 * Test suite for TrustedLogin Configuration.
 *
 * @since   6.10.0
 *
 * @package TEC\Common\TrustedLogin
 */

namespace TEC\Common\TrustedLogin;

use Codeception\TestCase\WPTestCase;
use TEC\Common\Configuration\Configuration;

/**
 * Test suite for the Trusted_Login_Config class.
 *
 * @since   6.10.0
 *
 * @package TEC\Common\TrustedLogin
 */
class Trusted_Login_Config_Test extends WPTestCase {

	/**
	 * @var Configuration
	 */
	protected $config;

	/**
	 * @var Trusted_Login_Config
	 */
	protected $config_instance;

	/**
	 * @before
	 */
	public function before(): void {
		$this->config          = tribe( Configuration::class );
		$this->config_instance = new Trusted_Login_Config( $this->config );
	}

	/**
	 * @after
	 */
	public function after(): void {
		$this->config          = null;
		$this->config_instance = null;
	}

	/**
	 * @test
	 */
	public function should_return_config_with_expected_keys(): void {
		$config = $this->config_instance->get();

		// Verify all expected keys are present.
		$this->assertArrayHasKey( 'auth', $config );
		$this->assertArrayHasKey( 'vendor', $config );
		$this->assertArrayHasKey( 'menu', $config );
		$this->assertArrayHasKey( 'decay', $config );
		$this->assertArrayHasKey( 'role', $config );
		$this->assertArrayHasKey( 'clone_role', $config );
	}

	/**
	 * @test
	 */
	public function should_use_getters_for_vendor_values(): void {
		$config = $this->config_instance->get();

		// Verify vendor values match getter methods.
		$this->assertEquals( $this->config_instance->get_namespace(), $config['vendor']['namespace'] );
		$this->assertEquals( $this->config_instance->get_title(), $config['vendor']['title'] );
		$this->assertEquals( $this->config_instance->get_support_email(), $config['vendor']['email'] );
		$this->assertEquals( $this->config_instance->get_support_url(), $config['vendor']['support_url'] );
		$this->assertEquals( $this->config_instance->get_website_url(), $config['vendor']['website'] );
	}

	/**
	 * @test
	 */
	public function should_use_getters_for_menu_values(): void {
		$config = $this->config_instance->get();

		// Verify menu values match getter methods.
		$this->assertEquals( $this->config_instance->get_menu_slug(), $config['menu']['slug'] );
		$this->assertNull( $config['menu']['parent_slug'] );
	}

	/**
	 * @test
	 */
	public function should_use_getters_for_role_values(): void {
		$config = $this->config_instance->get();

		// Verify role values match getter methods.
		$this->assertEquals( $this->config_instance->get_role(), $config['role'] );
		$this->assertEquals( $this->config_instance->get_clone_role(), $config['clone_role'] );
	}

	/**
	 * @test
	 */
	public function should_include_api_key_from_configuration(): void {
		$config = $this->config_instance->get();

		// Verify API key is retrieved from configuration.
		$this->assertArrayHasKey( 'api_key', $config['auth'] );
		$this->assertArrayHasKey( 'license_key', $config['auth'] );
		$this->assertEquals( '', $config['auth']['license_key'] );
	}

	/**
	 * @test
	 */
	public function should_include_logo_url_from_tribe_resource_url(): void {
		$config = $this->config_instance->get();

		// Verify logo URL is present and contains expected path.
		$this->assertArrayHasKey( 'logo_url', $config['vendor'] );
		$this->assertStringContainsString( 'the-events-calendar.svg', $config['vendor']['logo_url'] );
	}

	/**
	 * @test
	 */
	public function should_return_required_fields(): void {
		$required_fields = $this->config_instance->get_required_fields();

		$this->assertIsArray( $required_fields );
		$this->assertArrayHasKey( 'auth.api_key', $required_fields );
		$this->assertArrayHasKey( 'role', $required_fields );
		$this->assertArrayHasKey( 'vendor.namespace', $required_fields );
		$this->assertArrayHasKey( 'vendor.title', $required_fields );
		$this->assertArrayHasKey( 'vendor.email', $required_fields );
		$this->assertArrayHasKey( 'vendor.website', $required_fields );
		$this->assertArrayHasKey( 'vendor.support_url', $required_fields );
	}

	/**
	 * @test
	 */
	public function should_identify_missing_required_fields(): void {
		$incomplete_config = [
			'auth'   => [ 'api_key' => 'test-key' ],
			'vendor' => [ 'namespace' => 'tec-common' ],
			// Missing: title, email, website, support_url, role
		];

		$missing_fields = $this->config_instance->get_missing_required_fields( $incomplete_config );

		$this->assertIsArray( $missing_fields );
		$this->assertContains( 'role', $missing_fields );
		$this->assertContains( 'vendor.title', $missing_fields );
		$this->assertContains( 'vendor.email', $missing_fields );
		$this->assertContains( 'vendor.website', $missing_fields );
		$this->assertContains( 'vendor.support_url', $missing_fields );
		$this->assertNotContains( 'auth.api_key', $missing_fields );
		$this->assertNotContains( 'vendor.namespace', $missing_fields );
	}

	/**
	 * @test
	 */
	public function should_return_empty_array_for_complete_config(): void {
		$complete_config = [
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

		$missing_fields = $this->config_instance->get_missing_required_fields( $complete_config );

		$this->assertIsArray( $missing_fields );
		$this->assertEmpty( $missing_fields );
	}

	/**
	 * @test
	 */
	public function should_return_correct_url_for_menu_slug(): void {
		$url = $this->config_instance->get_url();

		$expected_url = admin_url( 'admin.php?page=' . $this->config_instance->get_menu_slug() );
		$this->assertEquals( $expected_url, $url );
	}

	/**
	 * @test
	 */
	public function should_apply_url_filter(): void {
		// Add a filter to modify the URL.
		add_filter(
			'tec_common_trustedlogin_page_url',
			function ( $url, $page_slug ) {
				return 'https://example.com/custom-url?page=' . $page_slug;
			},
			10,
			2
		);

		$url = $this->config_instance->get_url();

		// Only verify the admin path structure, not the domain.
		$this->assertStringContainsString( 'admin.php?page=', $url );
		$this->assertStringContainsString( $this->config_instance->get_menu_slug(), $url );
	}

	/**
	 * @test
	 */
	public function should_build_config_via_static_method(): void {
		// Ensure the constant is defined for the test.
		if ( ! defined( 'TEC_SUPPORT_TRUSTED_LOGIN_KEY' ) ) {
			define( 'TEC_SUPPORT_TRUSTED_LOGIN_KEY', 'test-api-key-123' );
		}

		$config = Trusted_Login_Config::build();

		// Verify the config has the expected structure.
		$this->assertIsArray( $config );
		$this->assertArrayHasKey( 'auth', $config );
		$this->assertArrayHasKey( 'vendor', $config );
		$this->assertArrayHasKey( 'menu', $config );
		$this->assertArrayHasKey( 'decay', $config );
		$this->assertArrayHasKey( 'role', $config );
		$this->assertArrayHasKey( 'clone_role', $config );
	}
}
