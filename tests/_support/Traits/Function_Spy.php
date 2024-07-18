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
	 * @param array $args
	 *
	 * @return void
	 */
	public function register_call( array $args ): void {
		$this->calls[] = [ $args, microtime( true ) ];
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
		$this->was_verified = true;

		return count( $this->calls ) > 0;
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
