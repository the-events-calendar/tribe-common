<?php
/**
 * Hooks the `tribe_log` action based logger under the existing one for back-compatibility.
 *
 * @since   TBD
 *
 * @package Tribe\Log
 */

namespace Tribe\Log;

use Monolog\Logger;
use Tribe__Log;

/**
 * Class Action_Logger
 *
 * @since   TBD
 *
 * @package Tribe\Log
 */
class Action_Logger implements \Tribe__Log__Logger {

	/**
	 * {@inheritDoc}
	 *
	 * @since TBD
	 */
	public function is_available() {
		return true;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @since TBD
	 */
	public function get_name() {
		return 'Action Logger';
	}

	/**
	 * {@inheritDoc}
	 *
	 * @since TBD
	 */
	public function log( $entry, $type = Tribe__Log::DEBUG, $src = '' ) {
		if ( $type === Tribe__Log::DISABLE ) {
			return;
		}

		$message = empty( $src ) ? $entry : $src . ': ' . $entry;

		do_action( 'tribe_log', $this->translate_log_level( $type ), $message );
	}

	/**
	 * Translates the log types used by `Tribe__Log` to those used by Monolog.
	 *
	 * @since TBD
	 *
	 * @param string $type The `Tribe__Log` log type.
	 *
	 * @return int The Monolog equivalent of the current level.
	 */
	protected function translate_log_level( $type ) {
		switch ( $type ) {
			case Tribe__Log::DISABLE:
				return PHP_INT_MAX;
			case Tribe__Log::DEBUG:
				return Logger::DEBUG;
			case Tribe__Log::ERROR:
				return Logger::ERROR;
			case Tribe__Log::WARNING:
				return Logger::WARNING;
			case Tribe__Log::SUCCESS:
			default:
				return Logger::INFO;
		}
	}

	/**
	 * {@inheritDoc}
	 *
	 * @since TBD
	 */
	public function retrieve( $limit = 0, array $args = array() ) {
		return [
			[
				'message' => __(
					'The Action Logger will dispatch any logging message using the "tribe_log" action writing, by ' .
					'default, to the PHP error log.',
					'tribe-common' )
			],
		];
	}

	/**
	 * {@inheritDoc}
	 *
	 * @since TBD
	 */
	public function list_available_logs() {
		return [];
	}

	/**
	 * {@inheritDoc}
	 *
	 * @since TBD
	 */
	public function use_log( $log_identifier, $create = false ) {
		return false;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @since TBD
	 */
	public function cleanup() {
		return true;
	}
}
