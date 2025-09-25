<?php
/**
 * TrustedLogin Manager.
 *
 * Initializes TrustedLogin with validated configuration
 * and provides early bails for missing requirements.
 *
 * @since TBD
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
 * @since TBD
 */
class Trusted_Login_Manager {

	/**
	 * Initializes TrustedLogin with the given or default configuration.
	 *
	 * - Builds config if none provided
	 * - Validates configuration before use
	 * - Stops if library or required values are missing
	 *
	 * @since TBD
	 *
	 * @param array<string,mixed> $config Optional. Prebuilt configuration array.
	 *
	 * @return void
	 */
	public function init( array $config = [] ): void {
		// Build config if none provided.
		if ( empty( $config ) ) {
			$config = Trusted_Login_Config::build();
		}

		// Bail early if config is invalid.
		if ( ! $this->validate_config( $config ) ) {
			return;
		}

		// Initialize the TrustedLogin client safely.
		try {
			new TrustedLoginClient( new TrustedLoginConfig( $config ) );
		} catch ( Throwable $e ) {
			$this->log_init_failure( $e, $config );

			return;
		}

		/**
		 * Fires after TrustedLogin is successfully registered.
		 *
		 * @since TBD
		 *
		 * @param array<string,mixed> $config Configuration used to initialize TrustedLogin.
		 */
		do_action( 'tec_trustedlogin_registered', $config );
	}

	/**
	 * Validates that required configuration keys are available.
	 *
	 * @since TBD
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
	 * @since TBD
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
		 * @since TBD
		 *
		 * @param Throwable           $e            Exception thrown.
		 * @param array<string,mixed> $config       Configuration used during failure.
		 */
		do_action( 'tec_trustedlogin_init_failed', $e, $config );
	}
}
