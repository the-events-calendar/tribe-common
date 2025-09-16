<?php
/**
 * Test suite for TrustedLogin Configuration.
 *
 * @since TBD
 *
 * @package TEC\Common\TrustedLogin
 */

namespace TEC\Common\TrustedLogin;

use Codeception\TestCase\WPTestCase;
use TEC\Common\Configuration\Configuration;

/**
 * Test suite for the Trusted_Login_Config class.
 *
 * @since TBD
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
		$this->config = tribe( Configuration::class );
		$this->config_instance = new Trusted_Login_Config( $this->config );
	}

	/**
	 * @after
	 */
	public function after(): void {
		$this->config = null;
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
	public function should_use_constants_for_vendor_values(): void {
		$config = $this->config_instance->get();

		// Verify vendor values match constants.
		$this->assertEquals( Trusted_Login_Config::NAMESPACE, $config['vendor']['namespace'] );
		$this->assertEquals( Trusted_Login_Config::TITLE, $config['vendor']['title'] );
		$this->assertEquals( Trusted_Login_Config::SUPPORT_EMAIL, $config['vendor']['email'] );
		$this->assertEquals( Trusted_Login_Config::SUPPORT_URL, $config['vendor']['support_url'] );
		$this->assertEquals( Trusted_Login_Config::WEBSITE_URL, $config['vendor']['website'] );
	}

	/**
	 * @test
	 */
	public function should_use_constants_for_menu_values(): void {
		$config = $this->config_instance->get();

		// Verify menu values match constants.
		$this->assertEquals( Trusted_Login_Config::MENU_SLUG, $config['menu']['slug'] );
		$this->assertNull( $config['menu']['parent_slug'] );
	}

	/**
	 * @test
	 */
	public function should_use_constants_for_role_values(): void {
		$config = $this->config_instance->get();

		// Verify role values match constants.
		$this->assertEquals( Trusted_Login_Config::ROLE, $config['role'] );
		$this->assertFalse( $config['clone_role'] );
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
	public function should_define_constants_when_called(): void {
		// Skip this test if constant is already defined from previous tests.
		if ( defined( 'TEC_SUPPORT_TRUSTED_LOGIN_KEY' ) ) {
			$this->markTestSkipped( 'Constant already defined from previous test' );
		}

		$this->config_instance->maybe_define_constants();

		$this->assertTrue( defined( 'TEC_SUPPORT_TRUSTED_LOGIN_KEY' ) );
		$this->assertEquals( '1d9fc7a576cb88ed', TEC_SUPPORT_TRUSTED_LOGIN_KEY );
	}

	/**
	 * @test
	 */
	public function should_not_redefine_existing_constants(): void {
		// Skip this test if constant is already defined from previous tests.
		if ( defined( 'TEC_SUPPORT_TRUSTED_LOGIN_KEY' ) ) {
			$this->markTestSkipped( 'Constant already defined from previous test' );
		}

		// Define the constant first.
		define( 'TEC_SUPPORT_TRUSTED_LOGIN_KEY', 'existing-value' );

		$this->config_instance->maybe_define_constants();

		// Should still be the original value.
		$this->assertEquals( 'existing-value', TEC_SUPPORT_TRUSTED_LOGIN_KEY );
	}

	/**
	 * @test
	 */
	public function should_return_correct_url_for_menu_slug(): void {
		$url = $this->config_instance->get_url();

		$expected_url = admin_url( 'admin.php?page=' . Trusted_Login_Config::MENU_SLUG );
		$this->assertEquals( $expected_url, $url );
	}

	/**
	 * @test
	 */
	public function should_apply_config_filter(): void {
		// Add a filter to modify the config.
		add_filter( 'tec_common_trustedlogin_config', function( $config ) {
			$config['test_key'] = 'test_value';
			return $config;
		} );

		$config = $this->config_instance->get();

		$this->assertEquals( 'test_value', $config['test_key'] );

		// Clean up.
		remove_all_filters( 'tec_common_trustedlogin_config' );
	}

	/**
	 * @test
	 */
	public function should_apply_url_filter(): void {
		// Add a filter to modify the URL.
		add_filter( 'tec_common_trustedlogin_page_url', function( $url, $page_slug ) {
			return 'https://example.com/custom-url?page=' . $page_slug;
		}, 10, 2 );

		$url = $this->config_instance->get_url();

		$expected_url = 'https://example.com/custom-url?page=' . Trusted_Login_Config::MENU_SLUG;
		$this->assertEquals( $expected_url, $url );

		// Clean up.
		remove_all_filters( 'tec_common_trustedlogin_page_url' );
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
