<?php
/**
 * Test suite for the TrustedLogin Controller.
 *
 * @since TBD
 *
 * @package TEC\Common\TrustedLogin
 */

namespace TEC\Common\TrustedLogin;

use Faker\Factory;
use TEC\Common\Configuration\Configuration;
use TEC\Common\Tests\Provider\Controller_Test_Case;

/**
 * Test suite for the TrustedLogin Controller.
 *
 * @since TBD
 *
 * @package TEC\Common\TrustedLogin
 */
class Controller_Test extends Controller_Test_Case {

	/**
	 * The controller under test.
	 *
	 * @var string
	 */
	protected $controller_class = Controller::class;

	/**
	 * @test
	 */
	public function should_fetch_vendor_public_key_from_the_public_key_website(): void {
		$this->make_controller()->register();

		$tl_config  = new Trusted_Login_Config( tribe( Configuration::class ) );
		$website    = $tl_config->get_website_url();
		$sdk_config = new Config( $tl_config->get() );
		$logging    = new Logging( $sdk_config );
		$encryption = new Encryption( $sdk_config, new Remote( $sdk_config, $logging ), $logging );
		$expected   = trailingslashit( $website ) . 'wp/?rest_route=/trustedlogin/v1/public_key';

		$this->assertSame( $expected, $encryption->get_remote_encryption_key_url() );
		$this->assertSame( $website, $sdk_config->get_setting( 'vendor/website' ) );
	}

	/**
	 * @test
	 */
	public function should_filter_the_public_key_website_of_the_namespace_the_sdk_is_configured_with(): void {
		$namespace = Factory::create()->lexify( 'tec-??????' );
		add_filter(
			'tec_trustedlogin_config',
			static function ( array $config ) use ( $namespace ): array {
				$config['vendor']['namespace'] = $namespace;

				return $config;
			}
		);

		$this->make_controller()->register();

		$tl_config = new Trusted_Login_Config( tribe( Configuration::class ) );
		$website   = $tl_config->get_website_url();

		$this->assertSame(
			$tl_config->get_public_key_website_url( $website ),
			apply_filters( 'trustedlogin/' . $namespace . '/vendor/public_key/website', $website ) // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores -- The TrustedLogin client's own hook name.
		);
	}

	/**
	 * @test
	 */
	public function should_follow_a_namespace_changed_before_trustedlogin_initializes(): void {
		$faker      = Factory::create();
		$registered = $faker->lexify( 'tec-??????' );
		$current    = $faker->lexify( 'tec-??????' );
		$namespace  = $registered;
		add_filter(
			'tec_trustedlogin_namespace',
			static function () use ( &$namespace ): string {
				return $namespace;
			}
		);

		$controller = $this->make_controller();
		$controller->register();
		$namespace = $current;
		$controller->init_trustedlogin();

		$website = ( new Trusted_Login_Config( tribe( Configuration::class ) ) )->get_website_url();

		$this->assertSame( trailingslashit( $website ) . 'wp/', apply_filters( 'trustedlogin/' . $current . '/vendor/public_key/website', $website ) ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores -- The TrustedLogin client's own hook name.
		$this->assertSame( $website, apply_filters( 'trustedlogin/' . $registered . '/vendor/public_key/website', $website ) ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores -- The TrustedLogin client's own hook name.
	}

	/**
	 * @test
	 */
	public function should_unhook_the_namespace_it_registered_after_the_namespace_changes(): void {
		$namespace        = Factory::create()->lexify( 'tec-??????' );
		$namespace_filter = static function () use ( $namespace ): string {
			return $namespace;
		};
		add_filter( 'tec_trustedlogin_namespace', $namespace_filter );

		$controller = $this->make_controller();
		$controller->register();
		remove_filter( 'tec_trustedlogin_namespace', $namespace_filter );
		$controller->unregister();

		$website = ( new Trusted_Login_Config( tribe( Configuration::class ) ) )->get_website_url();

		$this->assertSame( $website, apply_filters( 'trustedlogin/' . $namespace . '/vendor/public_key/website', $website ) ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores -- The TrustedLogin client's own hook name.
	}

	/**
	 * @test
	 */
	public function should_stop_filtering_the_public_key_website_when_unregistered(): void {
		$controller = $this->make_controller();
		$controller->register();
		$controller->unregister();

		$tl_config = new Trusted_Login_Config( tribe( Configuration::class ) );
		$hook      = 'trustedlogin/' . $tl_config->get_namespace() . '/vendor/public_key/website';
		$website   = $tl_config->get_website_url();

		$this->assertSame( $website, apply_filters( $hook, $website ) );
	}
}
