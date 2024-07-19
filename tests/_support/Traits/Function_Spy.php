<?php
/**
 * The trait implemented by function spies to provide assertions and set expectations.
 *
 * @since   TBD
 *
 * @package Tribe\Tests\Traits;
 */

namespace Tribe\Tests\Traits;

/**
 * Trait Function_Spy.
 *
 * @since   TBD
 *
 * @package Tribe\Tests\Traits;
 */
trait Function_Spy {
	/**
	 * The name of the function being mocked.
	 *
	 * @var string
	 */
	private string $name;

	/**
	 * The calls that have been made to the function.
	 *
	 * @var array
	 */
	private array $calls = [];

	/**
	 * Whether the function is expected to be called or not.
	 *
	 * @var bool
	 */
	private $expects_calls = true;

	/**
	 * Whether the function has been verified by a query method or not.
	 *
	 * @var bool
	 */
	private bool $was_verified = false;

	/**
	 * Returns the name of the function being mocked.
	 *
	 * @return string
	 */
	public function get_name(): string {
		return $this->name;
	}

	/**
	 * Registers a call to the function.
	 *
	 * @param array<int,mixed> $args
	 *
	 * @return void
	 */
	public function register_call( array $args ): void {
		$this->calls[] = $args;
	}

	/**
	 * Sets whether the function is expected to be called or not.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function should_be_called(): void {
		$this->expects_calls = true;
	}

	/**
	 * Sets whether the function is not expected to be called or not.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function should_not_be_called(): void {
		$this->expects_calls = false;
	}

	/**
	 * Returns whether the function is expected to be called or not.
	 *
	 * @return bool
	 */
	public function expects_calls(): bool {
		return $this->expects_calls;
	}

	/**
	 * Returns whether the function has been called or not.
	 *
	 * @return bool
	 */
	public function was_called(): bool {
		$was_called = count( $this->calls ) > 0;

		if ( $was_called ) {
			$this->was_verified = true;
		}

		return $was_called;
	}

	public function was_called_times_with( int $times, ...$args ): bool {
		if ( count( $this->calls ) < $times ) {
			return false;
		}

		$matching_calls = 0;

		foreach ( $this->calls as $call ) {
			$match = true;
			foreach ( $args as $k => $arg ) {
				if ( $arg == $call[ $k ] ) {
					continue;
				}

				$match = false;
				break;
			}

			$matching_calls += (int) $match;
		}

		return $matching_calls === $times;
	}

	/**
	 * Returns whether the function has been verified by a query method or not.
	 *
	 * @return bool
	 */
	public function was_verified(): bool {
		return $this->was_verified;
	}
}
