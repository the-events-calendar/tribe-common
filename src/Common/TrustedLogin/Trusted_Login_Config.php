<?php
/**
 * @since 6.9.5
 */

declare( strict_types=1 );

namespace TEC\Common\TrustedLogin;

use TEC\Common\Configuration\Configuration;
use Tribe__Main;

/**
 * Builds the configuration array for the TrustedLogin Client SDK.
 *
 * ## TrustedLogin Workflow
 *
 * This integration uses the TrustedLogin Client SDK, which communicates with a separate
 * TrustedLogin Connector website. The workflow is:
 *
 * ```
 * Client SDK (this site) → TrustedLogin Connector (separate website)
 * ```
 *
 * The TrustedLogin Connector lives on a separate website (configured via `$website_url`).
 * That website must be set up to use the same public API key as this Client SDK.
 * For the integration to work properly, both the API key and website URL must be
 * in sync and correct. If either is incorrect, the integration will not work.
 *
 * For complete configuration options and detailed field descriptions, see:
 * {@link https://docs.trustedlogin.com/Client/configuration#all-options TrustedLogin Configuration Documentation}
 *
 * @since 6.9.5
 *
 * @package TEC\Common\TrustedLogin
 */
class Trusted_Login_Config {

	/**
	 * Configuration dependency for reading stored settings.
	 *
	 * @since 6.9.5
	 *
	 * @var Configuration
	 */
	protected Configuration $config;

	/**
	 * Default namespace for TrustedLogin hooks and filters.
	 *
	 * @since 6.9.5
	 *
	 * @var string
	 */
	protected string $namespace = 'the-events-calendar';

	/**
	 * Default support page URL for vendor documentation or help.
	 *
	 * @since 6.9.5
	 *
	 * @var string
	 */
	protected string $support_url = 'https://theeventscalendar.com/support/';

	/**
	 * Default website URL where the TrustedLogin Connector plugin lives.
	 *
	 * This must match the environment hosting the Connector plugin (staging, production, etc.).
	 *
	 * @since 6.9.5
	 *
	 * @var string
	 */
	protected string $website_url = 'https://my.theeventscalendar.com';

	/**
	 * Default vendor support email used when creating support users.
	 *
	 * @since 6.9.5
	 *
	 * @var string
	 */
	protected string $support_email = 'support@theeventscalendar.com';

	/**
	 * Default admin menu slug for the TrustedLogin access page.
	 *
	 * @since 6.9.5
	 *
	 * @var string
	 */
	protected string $menu_slug = 'grant-tec-common-access';

	/**
	 * Default WordPress role assigned to TrustedLogin support users.
	 *
	 * @since 6.9.5
	 *
	 * @var string
	 */
	protected string $role = 'administrator';

	/**
	 * Default access expiration (in seconds).
	 *
	 * @since 6.9.5
	 *
	 * @var int
	 */
	protected int $decay = WEEK_IN_SECONDS;

	/**
	 * Whether capabilities should be cloned from the default role.
	 *
	 * @since 6.9.5
	 *
	 * @var bool
	 */
	protected bool $clone_role = false;

	/**
	 * Required configuration fields for TrustedLogin validation.
	 *
	 * These fields are marked as required by the TrustedLogin specification.
	 * For complete field descriptions and all available options, see:
	 * {@link https://docs.trustedlogin.com/Client/configuration#all-options TrustedLogin Configuration Documentation}
	 *
	 * @since 6.9.5
	 *
	 * @var array<string,string> Field paths mapped to their descriptions.
	 */
	protected array $required_fields = [
		'auth.api_key'       => 'API key for TrustedLogin authentication',
		'role'               => 'WordPress role for TrustedLogin support users',
		'vendor.namespace'   => 'Vendor namespace for TrustedLogin',
		'vendor.title'       => 'Vendor title for TrustedLogin UI',
		'vendor.email'       => 'Vendor support email address',
		'vendor.website'     => 'Vendor website URL',
		'vendor.support_url' => 'Vendor support page URL',
	];

	/**
	 * Injects configuration dependency.
	 *
	 * @since 6.9.5
	 *
	 * @param Configuration $config Configuration object for retrieving settings.
	 */
	public function __construct( Configuration $config ) {
		$this->config = $config;
	}

	/**
	 * Builds the configuration using defaults + filters.
	 *
	 * @since 6.9.5
	 *
	 * @return array<string,mixed> Filtered configuration array.
	 */
	public static function build(): array {
		$instance = new self( tribe( Configuration::class ) );

		return $instance->get();
	}


	/**
	 * Builds the final TrustedLogin configuration array.
	 *
	 * This method constructs the complete configuration array that will be passed to the
	 * TrustedLogin Client SDK. The configuration includes authentication, vendor information,
	 * menu settings, and access controls.
	 *
	 * ## Configuration Structure
	 *
	 * The returned array follows the TrustedLogin Client SDK specification:
	 * - `auth` - Authentication settings (API key, license key)
	 * - `vendor` - Vendor information (namespace, title, email, URLs, logo)
	 * - `menu` - Admin menu configuration
	 * - `decay` - Access expiration time
	 * - `role` - WordPress role for support users
	 * - `clone_role` - Whether to clone role capabilities
	 *
	 * Applies the global `tec_trustedlogin_config` filter after building.
	 *
	 * @since 6.9.5
	 *
	 * @return array<string,mixed> Final configuration array ready for TrustedLogin Client SDK.
	 *
	 * @see https://docs.trustedlogin.com/Client/configuration#all-options TrustedLogin Configuration Documentation
	 */
	public function get(): array {
		$config = [
			'auth'       => [
				'api_key'     => $this->get_api_key(),
				'license_key' => '',
			],
			'vendor'     => [
				'namespace'   => $this->get_namespace(),
				'title'       => $this->get_title(),
				'logo_url'    => $this->get_logo_url(),
				'email'       => $this->get_support_email(),
				'support_url' => $this->get_support_url(),
				'website'     => $this->get_website_url(),
			],
			'menu'       => [
				'parent_slug' => null,
				'slug'        => $this->get_menu_slug(),
			],
			'decay'      => $this->get_decay(),
			'role'       => $this->get_role(),
			'clone_role' => $this->get_clone_role(),
		];

		/**
		 * Filters the full TrustedLogin configuration array.
		 *
		 * @since 6.9.5
		 *
		 * @param array<string,mixed> $config TrustedLogin configuration.
		 */
		return apply_filters( 'tec_trustedlogin_config', $config );
	}

	/**
	 * Returns the admin URL for the TrustedLogin page.
	 *
	 * Applies `tec_trustedlogin_page_url` filter.
	 *
	 * @since 6.9.5
	 *
	 * @return string|null Filtered admin URL or null if no menu slug exists.
	 */
	public function get_url(): ?string {
		$page_slug = $this->get_menu_slug();

		if ( empty( $page_slug ) ) {
			return null;
		}

		$url = admin_url( 'admin.php?page=' . $page_slug );

		/**
		 * Filters the TrustedLogin admin page URL.
		 *
		 * @since 6.9.5
		 *
		 * @param string $url Full admin URL.
		 * @param string $page_slug Menu slug for the TrustedLogin page.
		 */
		return apply_filters( 'tec_trustedlogin_page_url', $url, $page_slug );
	}

	/**
	 * Returns the API key for TrustedLogin authentication.
	 *
	 * This is a public API key that points to the live environment for TrustedLogin.
	 * It is safe to expose this key in client-side code as it does not grant privileged access.
	 *
	 * ## Critical Sync Requirement
	 *
	 * This API key must match the API key configured on the TrustedLogin Connector website
	 * (configured via `get_website_url()`). Both the Client SDK and Connector must use
	 * the same API key for the integration to work properly.
	 *
	 * The API key can be found in "API Keys" on https://app.trustedlogin.com.
	 *
	 * @since 6.9.5
	 *
	 * @return string API key for the TrustedLogin service.
	 *
	 * @see https://docs.trustedlogin.com/Client/configuration#all-options TrustedLogin Configuration Documentation
	 */
	public function get_api_key(): string {
		// Define constant if not already set.
		if ( ! defined( 'TEC_SUPPORT_TRUSTED_LOGIN_KEY' ) ) {
			define( 'TEC_SUPPORT_TRUSTED_LOGIN_KEY', '1d9fc7a576cb88ed' );
		}

		$value = $this->config->get( 'TEC_SUPPORT_TRUSTED_LOGIN_KEY' );

		/**
		 * Filters the API key used for TrustedLogin authentication.
		 *
		 * @since 6.9.5
		 *
		 * @param string $value The API key value.
		 */
		return apply_filters( 'tec_trustedlogin_api_key', (string) $value );
	}

	/**
	 * Returns the vendor namespace for TrustedLogin.
	 *
	 * @since 6.9.5
	 *
	 * @return string Vendor namespace.
	 */
	public function get_namespace(): string {
		/**
		 * Filters the namespace used for TrustedLogin.
		 *
		 * @since 6.9.5
		 *
		 * @param string $namespace The vendor namespace.
		 */
		return apply_filters( 'tec_trustedlogin_namespace', $this->namespace );
	}

	/**
	 * Returns the translatable vendor title for TrustedLogin UI.
	 *
	 * @since 6.9.5
	 *
	 * @return string Translated vendor title.
	 */
	public function get_title(): string {
		$value = __( 'The Events Calendar', 'tribe-common' );

		/**
		 * Filters the vendor title displayed in TrustedLogin UI.
		 *
		 * @since 6.9.5
		 *
		 * @param string $value The vendor title.
		 */
		return apply_filters( 'tec_trustedlogin_title', $value );
	}

	/**
	 * Returns the vendor logo URL for TrustedLogin UI.
	 *
	 * @since 6.9.5
	 *
	 * @return string Vendor logo URL.
	 */
	public function get_logo_url(): string {
		$default = tribe_resource_url( 'images/logo/the-events-calendar.svg', false, null, Tribe__Main::instance() );

		/**
		 * Filters the vendor logo URL for TrustedLogin UI.
		 *
		 * @since 6.9.5
		 *
		 * @param string $default The default logo URL.
		 */
		return apply_filters( 'tec_trustedlogin_logo_url', $default );
	}

	/**
	 * Returns the vendor support email for TrustedLogin.
	 *
	 * @since 6.9.5
	 *
	 * @return string Vendor support email.
	 */
	public function get_support_email(): string {
		/**
		 * Filters the vendor support email used by TrustedLogin.
		 *
		 * @since 6.9.5
		 *
		 * @param string $support_email The support email address.
		 */
		return apply_filters( 'tec_trustedlogin_support_email', $this->support_email );
	}

	/**
	 * Returns the vendor support page URL for TrustedLogin.
	 *
	 * @since 6.9.5
	 *
	 * @return string Vendor support page URL.
	 */
	public function get_support_url(): string {
		/**
		 * Filters the vendor support page URL for TrustedLogin.
		 *
		 * @since 6.9.5
		 *
		 * @param string $support_url The support page URL.
		 */
		return apply_filters( 'tec_trustedlogin_support_url', $this->support_url );
	}

	/**
	 * Returns the website URL where the TrustedLogin Connector is installed.
	 *
	 * ## Critical Sync Requirement
	 *
	 * This URL must point to the website where the TrustedLogin Connector plugin is installed.
	 * The Connector website must be configured with the same API key as this Client SDK
	 * (configured via `get_api_key()`). Both the Client SDK and Connector must use the same
	 * API key for the integration to work properly.
	 *
	 * This must match the environment hosting the Connector plugin (staging, production, etc.).
	 *
	 * @since 6.9.5
	 *
	 * @return string Website URL hosting the TrustedLogin Connector plugin.
	 *
	 * @see https://docs.trustedlogin.com/Client/configuration#all-options TrustedLogin Configuration Documentation
	 */
	public function get_website_url(): string {
		/**
		 * Filters the website URL where TrustedLogin Connector lives.
		 *
		 * @since 6.9.5
		 *
		 * @param string $website_url The website URL.
		 */
		return apply_filters( 'tec_trustedlogin_website_url', $this->website_url );
	}

	/**
	 * Returns the admin menu slug for the TrustedLogin page.
	 *
	 * @since 6.9.5
	 *
	 * @return string Menu slug for the TrustedLogin admin page.
	 */
	public function get_menu_slug(): string {
		/**
		 * Filters the admin menu slug for the TrustedLogin page.
		 *
		 * @since 6.9.5
		 *
		 * @param string $menu_slug The menu slug.
		 */
		return apply_filters( 'tec_trustedlogin_menu_slug', $this->menu_slug );
	}

	/**
	 * Returns the WordPress role assigned to TrustedLogin support users.
	 *
	 * @since 6.9.5
	 *
	 * @return string Role for TrustedLogin support users.
	 */
	public function get_role(): string {
		/**
		 * Filters the WordPress role assigned to TrustedLogin support users.
		 *
		 * @since 6.9.5
		 *
		 * @param string $role The WordPress role.
		 */
		return apply_filters( 'tec_trustedlogin_role', $this->role );
	}

	/**
	 * Returns the access expiration time in seconds.
	 *
	 * @since 6.9.5
	 *
	 * @return int Access expiration time in seconds.
	 */
	public function get_decay(): int {
		/**
		 * Filters the access expiration time for TrustedLogin users.
		 *
		 * @since 6.9.5
		 *
		 * @param int $decay The access expiration time in seconds.
		 */
		return (int) apply_filters( 'tec_trustedlogin_decay', $this->decay );
	}

	/**
	 * Returns whether to clone capabilities from the configured role.
	 *
	 * @since 6.9.5
	 *
	 * @return bool True if cloning role capabilities; otherwise false.
	 */
	public function get_clone_role(): bool {
		/**
		 * Filters whether capabilities are cloned from the assigned role.
		 *
		 * @since 6.9.5
		 *
		 * @param bool $clone_role Whether to clone role capabilities.
		 */
		return (bool) apply_filters( 'tec_trustedlogin_clone_role', $this->clone_role );
	}

	/**
	 * Returns the required configuration fields for TrustedLogin validation.
	 *
	 * @since 6.9.5
	 *
	 * @return array<string,string> Required field paths and their descriptions.
	 */
	public function get_required_fields(): array {
		return $this->required_fields;
	}

	/**
	 * Validates that all required configuration fields are present and not empty.
	 *
	 * This method checks that all fields marked as required by the TrustedLogin specification
	 * are present and contain non-empty values. The required fields are defined in the
	 * `$required_fields` property.
	 *
	 * @since 6.9.5
	 *
	 * @param array<string,mixed> $config Configuration array to validate.
	 *
	 * @return array<string> Array of missing field paths, empty if all required fields are present.
	 *
	 * @see https://docs.trustedlogin.com/Client/configuration#all-options TrustedLogin Configuration Documentation
	 */
	public function get_missing_required_fields( array $config ): array {
		$missing_fields = [];

		foreach ( $this->required_fields as $field_path => $description ) {
			$keys  = explode( '.', $field_path );
			$value = $config;

			foreach ( $keys as $key ) {
				if ( empty( $value[ $key ] ) ) {
					$missing_fields[] = $field_path;
					break;
				}
				$value = $value[ $key ];
			}
		}

		return $missing_fields;
	}
}
