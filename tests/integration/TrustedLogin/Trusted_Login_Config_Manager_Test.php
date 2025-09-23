<?php
/**
 * Tests for TrustedLogin configuration and manager logic.
 *
 * @since   TBD
 *
 * @package TEC\Common\TrustedLogin
 */

namespace TEC\Tests\Common\TrustedLogin;

use TEC\Common\TrustedLogin\Trusted_Login_Config;
use TEC\Common\TrustedLogin\Trusted_Login_Manager;
use Codeception\TestCase\WPTestCase;

/**
 * Behavior-focused tests for TrustedLogin configuration and manager logic.
 *
 * @since TBD
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
	 * @after
	 */
	public function after(): void {
		remove_all_filters( 'tec_common_trustedlogin_config' );
		remove_all_filters( 'tec_common_trustedlogin_page_url' );
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

		// Vendor info should match constants.
		$this->assertEquals( Trusted_Login_Config::NAMESPACE, $config['vendor']['namespace'] );
		$this->assertEquals( Trusted_Login_Config::get_title(), $config['vendor']['title'] );
	}

	/**
	 * @test
	 */
	public function it_applies_config_filter(): void {
		add_filter(
			'tec_common_trustedlogin_config',
			function ( $config ) {
				$config['test_key'] = 'injected';

				return $config;
			}
		);

		$config = Trusted_Login_Config::build();
		$this->assertEquals( 'injected', $config['test_key'] );
	}

	/**
	 * @test
	 */
	public function it_returns_expected_admin_url(): void {
		$url = ( new Trusted_Login_Config( tribe( \TEC\Common\Configuration\Configuration::class ) ) )->get_url();

		$expected = admin_url( 'admin.php?page=' . Trusted_Login_Config::MENU_SLUG );
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

		$url = ( new Trusted_Login_Config( tribe( \TEC\Common\Configuration\Configuration::class ) ) )->get_url();

		$this->assertStringStartsWith( 'https://custom.test', $url );
	}

	/**
	 * @test
	 */
	public function it_initializes_manager_with_valid_config(): void {
		$manager = Trusted_Login_Manager::instance();
		$manager->init( Trusted_Login_Config::build() );

		$url = $manager->get_url();
		$this->assertNotNull( $url );
		$this->assertStringContainsString( 'admin.php?page=', $url );
	}

	/**
	 * @test
	 */
	public function it_bails_when_config_is_invalid(): void {
		$manager = Trusted_Login_Manager::instance();
		$config  = Trusted_Login_Config::build();
		unset( $config['auth']['api_key'] ); // Break config.

		$fired = false;
		add_action(
			'tec_common_trustedlogin_invalid_config_' . $config['vendor']['namespace'],
			function () use ( &$fired ) {
				$fired = true;
			}
		);

		$manager->init( $config );

		$this->assertTrue( $fired, 'Expected invalid config action to fire.' );
	}

	/**
	 * @test
	 */
	public function it_fires_registered_action_after_success(): void {
		$manager = Trusted_Login_Manager::instance();
		$config  = Trusted_Login_Config::build();

		$fired = false;
		add_action(
			'tec_common_trustedlogin_registered_' . $config['vendor']['namespace'],
			function () use ( &$fired ) {
				$fired = true;
			}
		);

		$manager->init( $config );

		$this->assertTrue( $fired, 'Expected registered action to fire after init.' );
	}
}
