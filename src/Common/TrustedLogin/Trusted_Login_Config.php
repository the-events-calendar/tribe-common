<?php
/**
 * TrustedLogin Configuration.
 *
 * Provides configuration building for TrustedLogin within the TEC plugin architecture,
 * handling all constants and config array generation.
 *
 * @since TBD
 *
 * @package TEC\Common\TrustedLogin
 */

declare( strict_types=1 );

namespace TEC\Common\TrustedLogin;

use TEC\Common\Configuration\Configuration;
use Tribe__Main;

/**
 * Configuration class for TrustedLogin functionality.
 *
 * This class is responsible for building the complete configuration array
 * for TrustedLogin, including all constants and dynamic values.
 *
 * @since TBD
 *
 * @package TEC\Common\TrustedLogin
 */
class Trusted_Login_Config {

	/**
	 * Unique namespace for identifying the product in TrustedLogin.
	 *
	 * Used in hooks, filters, and URL generation to avoid conflicts
	 * between multiple products using TrustedLogin.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public const NAMESPACE = 'tec-common';

	/**
	 * Human-readable title for the product shown in the TrustedLogin UI.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public const TITLE = 'The Events Calendar';

	/**
	 * URL for the vendor's support page.
	 *
	 * Displayed in the TrustedLogin UI and used as a fallback redirect if
	 * the TrustedLogin service is unreachable.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public const SUPPORT_URL = 'https://theeventscalendar.com/support/';

	/**
	 * URL for the vendor's main website.
	 *
	 * Displayed in the TrustedLogin UI for informational purposes.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public const WEBSITE_URL = 'https://my.theeventscalendar.com';

	/**
	 * Email address for vendor support.
	 *
	 * Used when creating support usernames in the TrustedLogin flow.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public const SUPPORT_EMAIL = 'support@theeventscalendar.com';

	/**
	 * Slug for the TrustedLogin admin page.
	 *
	 * Used in the WordPress admin URL query parameter "page".
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public const MENU_SLUG = 'grant-tec-common-access';

	/**
	 * Role assigned to TrustedLogin support users.
	 *
	 * Defaults to "administrator" but can be filtered if needed.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public const ROLE = 'administrator';

	/**
	 * The configuration object.
	 *
	 * @since TBD
	 *
	 * @var Configuration
	 */
	protected Configuration $config;

	/**
	 * Constructor.
	 *
	 * @since TBD
	 *
	 * @param Configuration $config The configuration object.
	 */
	public function __construct( Configuration $config ) {
		$this->config = $config;
	}

	/**
	 * Factory method to build and return TrustedLogin configuration.
	 *
	 * @since TBD
	 *
	 * @return array<string,mixed> The configuration array.
	 */
	public static function build(): array {
		$config = new self( tribe( Configuration::class ) );
		$config->maybe_define_constants();
		return $config->get();
	}

	/**
	 * Define constants if they don't already exist.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function maybe_define_constants(): void {
		if ( ! defined( 'TEC_SUPPORT_TRUSTED_LOGIN_KEY' ) ) {
			define( 'TEC_SUPPORT_TRUSTED_LOGIN_KEY', '1d9fc7a576cb88ed' );
		}
	}

	/**
	 * Get the complete TrustedLogin configuration array.
	 *
	 * @see https://docs.trustedlogin.com/Client/configuration#all-options
	 *
	 * @since TBD
	 *
	 * @return array<string,mixed>
	 */
	public function get(): array {
		$logo_source = tribe_resource_url( 'images/logo/the-events-calendar.svg', false, null, Tribe__Main::instance() );

		$config = [
			'auth'       => [
				'api_key'     => $this->config->get( 'TEC_SUPPORT_TRUSTED_LOGIN_KEY' ),
				'license_key' => '',
			],
			'vendor'     => [
				'namespace'   => self::NAMESPACE,
				'title'       => self::TITLE,
				'logo_url'    => $logo_source,
				'email'       => self::SUPPORT_EMAIL,
				'support_url' => self::SUPPORT_URL,
				'website'     => self::WEBSITE_URL,
			],
			'menu'       => [
				'parent_slug' => null,
				'slug'        => self::MENU_SLUG,
			],
			'decay'      => WEEK_IN_SECONDS,
			'role'       => self::ROLE,
			'clone_role' => false,
		];

		/**
		 * Filter the TrustedLogin configuration before it's used.
		 *
		 * @since TBD
		 *
		 * @param array<string,mixed> $config The configuration array.
		 */
		return apply_filters( 'tec_common_trustedlogin_config', $config );
	}

	/**
	 * Get the full admin URL for the TrustedLogin page.
	 *
	 * @since TBD
	 *
	 * @return string|null The admin URL or null if page slug is missing.
	 */
	public function get_url(): ?string {
		$page_slug = self::MENU_SLUG;

		if ( empty( $page_slug ) ) {
			return null;
		}

		$url = admin_url( 'admin.php?page=' . $page_slug );

		/**
		 * Filter the TrustedLogin page URL.
		 *
		 * @since TBD
		 *
		 * @param string $url The full admin URL.
		 * @param string $page_slug The page slug used for TrustedLogin.
		 */
		return apply_filters( 'tec_common_trustedlogin_page_url', $url, $page_slug );
	}
}
