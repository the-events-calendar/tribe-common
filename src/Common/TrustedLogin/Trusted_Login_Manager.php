<?php
/**
 * TrustedLogin Manager.
 *
 * Initializes TrustedLogin with validated configuration
 * and provides early bails for missing requirements.
 *
 * @since 6.9.5
 *
 * @package TEC\Common\TrustedLogin
 */

namespace TEC\Common\TrustedLogin;

use Throwable;
use TEC\Common\TrustedLogin\Client as TrustedLoginClient;
use TEC\Common\TrustedLogin\Config as TrustedLoginConfig;
use TEC\Common\Configuration\Configuration;

/**
 * Handles TrustedLogin initialization for TEC.
 *
 * @since 6.9.5
 */
class Trusted_Login_Manager {

	/**
	 * TrustedLogin configuration array.
	 *
	 * Built via {@see Trusted_Login_Config::build()} and passed in at construction.
	 * Contains all settings required to initialize TrustedLogin.
	 * For full details on available keys, see {@see Trusted_Login_Config}.
	 *
	 * @since 6.9.5
	 *
	 * @var array<string,mixed>
	 */
	protected array $config = [];

	/**
	 * Constructor.
	 *
	 * Stores the TrustedLogin configuration array for use when initializing.
	 *
	 * @since 6.9.5
	 *
	 * @param array<string,mixed> $config Configuration array from {@see Trusted_Login_Config::build()}.
	 */
	public function __construct( array $config ) {
		$this->config = $config;
	}

	/**
	 * Initializes TrustedLogin with the given or default configuration.
	 *
	 * - Builds config if none provided
	 * - Validates configuration before use
	 * - Stops if library or required values are missing
	 *
	 * @since 6.9.5
	 *
	 * @return void
	 */
	public function init(): void {

		// Bail early if config is invalid.
		if ( ! $this->validate_config( $this->config ) ) {
			return;
		}

		// Initialize the TrustedLogin client safely.
		try {
			new TrustedLoginClient( new TrustedLoginConfig( $this->config ) );
		} catch ( Throwable $e ) {
			$this->log_init_failure( $e, $this->config );

			return;
		}

		/**
		 * Fires after TrustedLogin is successfully registered.
		 *
		 * @since 6.9.5
		 *
		 * @param array<string,mixed> $config Configuration used to initialize TrustedLogin.
		 */
		do_action( 'tec_trustedlogin_registered', $this->config );
	}

	/**
	 * Validates that required configuration keys are available.
	 *
	 * @since 6.9.5
	 *
	 * @param array<string,mixed> $config Configuration array to validate.
	 *
	 * @return bool True if valid, false otherwise.
	 */
	protected function validate_config( array $config ): bool {
		$config_instance = new Trusted_Login_Config( tribe( Configuration::class ) );
		$missing_fields  = $config_instance->get_missing_required_fields( $config );

		if ( ! empty( $missing_fields ) ) {
			do_action( 'tec_trustedlogin_invalid_config', $config, $missing_fields );
			return false;
		}

		return true;
	}

	/**
	 * Logs an initialization failure for debugging purposes.
	 *
	 * @since 6.9.5
	 *
	 * @param Throwable           $e      Exception thrown during initialization.
	 * @param array<string,mixed> $config Configuration used when failure occurred.
	 *
	 * @return void
	 */
	protected function log_init_failure( Throwable $e, array $config ): void {
		do_action(
			'tribe_log',
			'error',
			__CLASS__,
			[
				'action'  => 'trustedlogin_init_failed',
				'message' => 'TrustedLogin initialization failed.',
				'error'   => $e->getMessage(),
				'trace'   => $e->getTraceAsString(),
				'config'  => $config,
			]
		);

		/**
		 * Fires when TrustedLogin initialization fails.
		 *
		 * @since 6.9.5
		 *
		 * @param Throwable           $e            Exception thrown.
		 * @param array<string,mixed> $config       Configuration used during failure.
		 */
		do_action( 'tec_trustedlogin_init_failed', $e, $config );
	}
}
