<?php
/**
 * Invalid REST argument exception.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Exceptions
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Exceptions;

use Exception;
use Throwable;
use WP_Error;

// phpcs:disable StellarWP.Classes.ValidClassName.NotSnakeCase, Generic.CodeAnalysis.UselessOverridingMethod.Found

/**
 * Invalid REST argument exception.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Exceptions
 */
class InvalidRestArgumentException extends Exception {

	/**
	 * The argument that was invalid.
	 *
	 * @since 6.9.0
	 *
	 * @var string
	 */
	private string $argument;

	/**
	 * The error code.
	 *
	 * @since 6.9.0
	 *
	 * @var string
	 */
	private string $error_code;

	/**
	 * The details.
	 *
	 * @since 6.9.0
	 *
	 * @var string
	 */
	private string $details;

	/**
	 * Constructs the exception.
	 *
	 * @since 6.9.0
	 *
	 * @param string     $generic_message The generic message to use for the exception.
	 * @param int        $status_code     The status code to use for the exception.
	 * @param ?Throwable $previous        The previous exception to use for the exception.
	 */
	public function __construct( string $generic_message = '', int $status_code = 400, ?Throwable $previous = null ) {
		parent::__construct( $generic_message, $status_code, $previous );
	}

	/**
	 * Sets the argument that was invalid.
	 *
	 * @since 6.9.0
	 *
	 * @param string $argument The argument that was invalid.
	 *
	 * @return void
	 */
	public function set_argument( string $argument ): void {
		$this->argument = $argument;
	}

	/**
	 * Gets the argument that was invalid.
	 *
	 * @since 6.9.0
	 *
	 * @return string
	 */
	public function get_argument(): string {
		return $this->argument;
	}

	/**
	 * Sets the error code.
	 *
	 * @since 6.9.0
	 *
	 * @param string $error_code The error code.
	 *
	 * @return void
	 */
	public function set_internal_error_code( string $error_code ): void {
		$this->error_code = $error_code;
	}

	/**
	 * Sets the details.
	 *
	 * @since 6.9.0
	 *
	 * @param string $details The details.
	 *
	 * @return void
	 */
	public function set_details( string $details ): void {
		$this->details = $details;
	}

	/**
	 * Gets the details.
	 *
	 * @since 6.9.0
	 *
	 * @return string
	 */
	public function get_details(): string {
		return $this->details;
	}

	/**
	 * Gets the error code.
	 *
	 * @since 6.9.0
	 *
	 * @return string
	 */
	public function get_internal_error_code(): string {
		return $this->error_code;
	}

	/**
	 * Converts the exception to a WP_Error.
	 *
	 * @since 6.9.0
	 *
	 * @return WP_Error
	 */
	public function to_wp_error(): WP_Error {
		return new WP_Error(
			$this->get_internal_error_code(),
			$this->getMessage(),
			[
				'status'   => $this->getCode(),
				'argument' => $this->get_argument(),
				'details'  => $this->get_details(),
			]
		);
	}

	/**
	 * Creates an exception.
	 *
	 * @since 6.10.0
	 *
	 * @param string $generic_message The generic message to use for the exception.
	 * @param string $argument        The argument that was invalid.
	 * @param string $error_code      The error code.
	 * @param string $details         The details.
	 *
	 * @return self
	 */
	public static function create( string $generic_message, string $argument, string $error_code, string $details = '' ): self {
		$exception = new self( $generic_message );
		$exception->set_argument( $argument );
		$exception->set_details( $details );
		$exception->set_internal_error_code( $error_code );
		return $exception;
	}
}
