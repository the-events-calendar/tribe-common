<?php


/**
 * Class Tribe__Exception
 * 
 * Handles exceptions to log when not in debug mode.
 */
class Tribe__Exception extends Exception {

	/**
	 * @var Exception
	 */
	private $original_exception;

	/**
	 * Tribe__Exception constructor.
	 *
	 * @param Exception $original_exception
	 */
	public function __construct( Exception $original_exception ) {
		$this->original_exception = $original_exception;
	}

	/**
	 * Handles the exception throwing the original when debugging (`WP_DEBUG` defined and `true`)
	 * or quietly logging when `WP_DEBUG` is `false` or not set.
	 *
	 * @return bool  `true` if the message was logged, `false` otherwise.
	 *
	 * @throws Exception
	 */
	public function handle() {
		$debug = defined( 'WP_DEBUG' ) && WP_DEBUG;

		if ( $debug ) {
			throw  $this->original_exception;
		}

		if ( ! class_exists( 'Tribe__Log' ) ) {
			return false;
		}

		$logger   = new Tribe__Log();
		$message  = $this->original_exception->getMessage();
		$log_type = $this->get_log_type_for_exception_code( $this->original_exception->getCode() );
		$src      = $this->original_exception->getFile() . ':' . $this->original_exception->getLine();

		$logger->log( $message, $log_type, $src );

		return true;
	}

	/**
	 * @return string
	 */
	private function get_log_type_for_exception_code( $code ) {
		$map = array(
			// @todo: let's add a decent exception code to log type map here
		);

		return isset( $map[ $code ] ) ? $map[ $code ] : Tribe__Log::ERROR;
	}
}