<?php
/**
 * Provides methods to both mute and record log messages dispatched in the context
 * by the Logger.
 *
 * @since 5.0.1
 *
 * @package Tribe\Tests\Traits
 */

namespace Tribe\Tests\Traits;

use TEC\Common\Monolog\Formatter\FormatterInterface;
use TEC\Common\Monolog\Handler\HandlerInterface;
use TEC\Common\Monolog\Logger;
use PHPUnit\Framework\Assert;

trait With_Log_Recording {

	private static $test_log_recorder;
	private static $log_recorder_subscribe;

	/**
	 * @before
	 */
	public function log_recorder_start() {
		if ( null === static::$test_log_recorder ) {
			static::$test_log_recorder = new class implements HandlerInterface {
				private $log_records = [];

				public function isHandling( array $record ) {
					return true;
				}

				public function handle( array $record ) {
					$this->log_records[] = $record;
				}

				public function handleBatch( array $records ) {
					array_push( $this->log_records, ... $records );
				}

				public function pushProcessor( $callback ) {
					// No-op.
				}

				public function popProcessor() {
					return null;
				}

				public function setFormatter( FormatterInterface $formatter ) {
					// No-op.
				}

				public function getFormatter() {
					return null;
				}

				public function reset() {
					$this->log_records = [];
				}

				public function get_records() {
					return $this->log_records;
				}
			};

			static::$log_recorder_subscribe = static function () {
				return [ static::$test_log_recorder ];
			};
		}

		if ( ! tribe()->isBound( Logger::class ) ) {
			// The logger has not been instantiated yet, hook for later.
			if ( ! has_filter( 'tribe_log_handlers', static::$log_recorder_subscribe ) ) {
				add_filter( 'tribe_log_handlers', static::$log_recorder_subscribe, PHP_INT_MAX, 0 );
			}
		} else {
			// The logger has already been set up, insert the test logger, if not already inserted.
			$logger = tribe( Logger::class );
			$current_handlers = $logger->getHandlers();
			if ( ! in_array( static::$test_log_recorder, $current_handlers, true ) ) {
				$logger->pushHandler( static::$test_log_recorder );
			}
		}
	}

	/**
	 * @after
	 */
	public function log_recorder_reset() {
		$this->log_recorder_stop();
		static::$test_log_recorder->reset();
	}

	private function log_recorder_stop() {
		remove_filter( 'tribe_log_handlers', static::$log_recorder_subscribe, PHP_INT_MAX );
	}

	private function get_log_record( $record_index ) {
		$records = $this->get_log_records();

		Assert::assertArrayHasKey( $record_index, $records, 'No record for key ' . $record_index );

		return $records[ $record_index ];
	}

	private function get_log_records() {
		return static::$test_log_recorder->get_records();
	}
}