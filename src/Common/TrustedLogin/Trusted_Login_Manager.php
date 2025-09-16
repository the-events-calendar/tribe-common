<?php
/**
 * TrustedLogin Manager.
 *
 * Provides a flexible wrapper for integrating TrustedLogin into plugins.
 * Handles configuration, initialization, hooks, and optional admin UI customization.
 *
 * @since   TBD
 *
 * @package TEC\Common\TrustedLogin
 */

namespace TEC\Common\TrustedLogin;

use TEC\Common\Configuration;

/**
 * TrustedLogin Manager for TEC/ET.
 *
 * Provides a flexible wrapper for TrustedLogin setup with early bails,
 * hooks for configuration, and optional admin page customization.
 *
 * @since TBD
 */
class Trusted_Login_Manager {

	/**
	 * Singleton instance of the class.
	 *
	 * Ensures only one instance of the Trusted_Login_Manager class exists
	 * throughout the request lifecycle. Accessed via the `instance()` method.
	 *
	 * @since TBD
	 *
	 * @var self|null
	 */
	protected static $instance = null;

	const HOOK_BASE = 'tec_common_trustedlogin';

	/**
	 * The final configuration used to initialize TrustedLogin.
	 *
	 * This is merged from defaults, user config, and any filters.
	 *
	 * @since TBD
	 *
	 * @var array<string,mixed>
	 */
	protected array $resolved_config = [];

	/**
	 * The page slug used for the TrustedLogin screen.
	 *
	 * This may be filtered per namespace and may be empty if not provided.
	 *
	 * @since TBD
	 *
	 * @var string|null
	 */
	protected ?string $page_slug = null;

	/**
	 * Get singleton instance.
	 *
	 * @since TBD
	 *
	 * @return static
	 */
	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Main entry point for setting up TrustedLogin.
	 *
	 * @since TBD
	 *
	 * @param array<string,mixed> $config User-provided config.
	 *
	 * @return void
	 */
	public function init( array $config = [] ) {
		// Use default config if none provided.
		if ( empty( $config ) ) {
			$config = Trusted_Login_Config::build();
		}

		// Run all validation checks and bail if needed.
		if ( ! $this->validate_config( $config ) ) {
			return;
		}

		// Apply filters so products can override values.
		$config_namespace = $config['vendor']['namespace'];
		$config           = apply_filters( self::HOOK_BASE . "_config_{$config_namespace}", $config, $config_namespace );

		// Allow disabling via empty config.
		if ( empty( $config ) ) {
			do_action( self::HOOK_BASE . "_disabled_{$config_namespace}", $config_namespace );

			return;
		}

		// Store config for later use.
		$this->resolved_config = $config;
		$this->page_slug       = $this->get_page_slug( $config, $config_namespace );

		// Register the TrustedLogin client safely.
		try {
			$client = new Client( new Config( $config ) );
		} catch ( \Throwable $e ) {
			// Log the error so developers can debug if needed.
			$source  = __CLASS__;
			$message = sprintf(
				'Failed to initialize TrustedLogin for namespace "%s".',
				$config_namespace
			);
			do_action(
				'tribe_log',
				'error',
				$source,
				[
					'action'    => 'trustedlogin_init_failed',
					'message'   => $message,
					'error'     => $e->getMessage(),
					'trace'     => $e->getTraceAsString(),
					'config'    => $config,
					'namespace' => $config_namespace,
				]
			);

			// Fire an action so devs can handle it however they want.
			do_action( self::HOOK_BASE . "_init_failed_{$config_namespace}", $e, $config, $config_namespace );

			return;
		}

		// Fire a post-registration action.
		do_action( self::HOOK_BASE . "_registered_{$config_namespace}", $client, $config, $config_namespace );
	}

	/**
	 * Run validation checks for early bails.
	 *
	 * @since TBD
	 *
	 * @param array<string,mixed> $config Configuration array.
	 *
	 * @return bool True if valid, false otherwise.
	 */
	protected function validate_config( array $config ): bool {
		// Check library availability first.
		if ( ! class_exists( Client::class ) || ! class_exists( Config::class ) ) {
			do_action( self::HOOK_BASE . '_missing_library', $config );

			return false;
		}

		// Require a namespace for namespacing hooks.
		if ( empty( $config['vendor']['namespace'] ) ) {
			do_action( self::HOOK_BASE . '_missing_namespace', $config );

			return false;
		}

		// Require API key and title for TrustedLogin setup.
		if ( empty( $config['auth']['api_key'] ) || empty( $config['vendor']['title'] ) ) {
			$namespace = $config['vendor']['namespace'] ?? 'unknown';
			do_action( self::HOOK_BASE . "_invalid_config_{$namespace}", $config, $namespace );

			return false;
		}

		return true;
	}

	/**
	 * Get the page slug for TrustedLogin.
	 *
	 * @since TBD
	 *
	 * @param array<string,mixed> $config           Configuration array.
	 * @param string              $config_namespace Namespace string.
	 *
	 * @return string
	 */
	protected function get_page_slug( array $config, string $config_namespace ): string {
		return apply_filters(
			self::HOOK_BASE . "_page_slug_{$config_namespace}",
			$config['menu']['slug'] ?? '',
			$config_namespace,
			$config
		);
	}

	/**
	 * Get the full admin URL for the TrustedLogin page.
	 *
	 * @since TBD
	 *
	 * @return string|null The admin URL or null if page slug is missing.
	 */
	public function get_url(): ?string {
		$config = new Trusted_Login_Config( tribe( Configuration::class ) );
		return $config->get_url();
	}
}
