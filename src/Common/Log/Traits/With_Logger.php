<?php
/**
 * Logs messages through the `tribe_log` action.
 *
 * @since TBD
 *
 * @package TEC\Common\Log\Traits
 */

namespace TEC\Common\Log\Traits;

/**
 * Trait With_Logger.
 *
 * @since TBD
 *
 * @package TEC\Common\Log\Traits
 */
trait With_Logger {
	/**
	 * Logs a message tagged with the class using the trait.
	 *
	 * A `source` key in the context replaces the class tag, so the classes of one feature can share a tag.
	 *
	 * @since TBD
	 *
	 * @param string              $level   The log level, one of the `Tribe__Log` level constants.
	 * @param string              $message The message to log.
	 * @param array<string,mixed> $context The context to log with the message.
	 *
	 * @return void
	 */
	private function log( string $level, string $message, array $context = [] ): void {
		do_action( 'tribe_log', $level, $message, array_merge( [ 'source' => static::class ], $context ) );
	}
}
