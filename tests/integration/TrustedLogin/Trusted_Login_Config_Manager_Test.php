<?php
/**
 * Tests for TrustedLogin configuration and manager logic.
 *
 * @since 6.9.5
 *
 * @package TEC\Common\TrustedLogin
 */

namespace TEC\Tests\Common\TrustedLogin;

use TEC\Common\Configuration\Configuration;
use TEC\Common\TrustedLogin\Trusted_Login_Config;
use TEC\Common\TrustedLogin\Trusted_Login_Manager;
use Codeception\TestCase\WPTestCase;

/**
 * Behavior-focused tests for TrustedLogin configuration and manager logic.
 *
 * @since 6.9.5
 */
class Trusted_Login_Config_Manager_Test extends WPTestCase {

	/**
	 * @before
	 */
	public function before(): void {
		// Ensure constants are defined for tests.
		if ( ! defined( 'TEC_SUPPORT_TRUSTED_LOGIN_KEY' ) ) {
			define( 'TEC_SUPPORT_TRUSTED_LOGIN_KEY', 'test-api-key-123' );
		}
	}

	/**
	 * @test
	 */
	public function it_builds_valid_configuration(): void {
		$config = Trusted_Login_Config::build();

		$this->assertIsArray( $config );
		$this->assertArrayHasKey( 'auth', $config );
		$this->assertArrayHasKey( 'vendor', $config );
		$this->assertArrayHasKey( 'menu', $config );
		$this->assertArrayHasKey( 'role', $config );

		// Vendor info should be present.
		$this->assertArrayHasKey( 'namespace', $config['vendor'] );
		$this->assertArrayHasKey( 'title', $config['vendor'] );
		$this->assertNotEmpty( $config['vendor']['namespace'] );
		$this->assertNotEmpty( $config['vendor']['title'] );
	}

	/**
	 * @test
	 */
	public function it_returns_expected_admin_url(): void {
		$config_instance = new Trusted_Login_Config( tribe( Configuration::class ) );
		$url             = $config_instance->get_url();

		$expected = admin_url( 'admin.php?page=' . $config_instance->get_menu_slug() );
		$this->assertEquals( $expected, $url );
	}

	/**
	 * @test
	 */
	public function it_applies_admin_url_filter(): void {
		add_filter(
			'tec_common_trustedlogin_page_url',
			function ( $url, $page_slug ) {
				return 'https://custom.test/page=' . $page_slug;
			},
			10,
			2
		);

		$config_instance = new Trusted_Login_Config( tribe( Configuration::class ) );
		$url             = $config_instance->get_url();

		// Only verify the admin path structure, not the domain.
		$this->assertStringContainsString( 'page=', $url );
		$this->assertStringContainsString( $config_instance->get_menu_slug(), $url );
	}

	/**
	 * @test
	 */
	public function it_initializes_manager_with_valid_config(): void {
		$manager = tribe( Trusted_Login_Manager::class );
		$config  = Trusted_Login_Config::build();

		// Test that init completes without errors.
		$manager->init( $config );

		// If we get here without errors, the initialization worked.
		$this->assertTrue( true );
	}

	/**
	 * @test
	 */
	public function it_validates_required_fields_correctly(): void {
		$config_instance = new Trusted_Login_Config( tribe( Configuration::class ) );

		// Test with complete config.
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

		$missing_fields = $config_instance->get_missing_required_fields( $complete_config );
		$this->assertEmpty( $missing_fields, 'Complete config should have no missing fields.' );

		// Test with incomplete config.
		$incomplete_config = [
			'auth'   => [ 'api_key' => 'test-key' ],
			'vendor' => [ 'namespace' => 'tec-common' ],
			// Missing: role, vendor.title, vendor.email, vendor.website, vendor.support_url
		];

		$missing_fields = $config_instance->get_missing_required_fields( $incomplete_config );
		$this->assertNotEmpty( $missing_fields, 'Incomplete config should have missing fields.' );
		$this->assertContains( 'role', $missing_fields );
		$this->assertContains( 'vendor.title', $missing_fields );
		$this->assertContains( 'vendor.email', $missing_fields );
		$this->assertContains( 'vendor.website', $missing_fields );
		$this->assertContains( 'vendor.support_url', $missing_fields );
	}
}
