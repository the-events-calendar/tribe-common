<?php
/**
 * A boolean value lazily built, suited to any value that might be costly to be built.
 *
 * @since   TBD
 *
 * @package Tribe\Utils
 */


namespace Tribe\Utils;


class Lazy_Boolean {
	use Lazy_Events;

	/**
	 * The value produced by the callback, cached.
	 *
	 * @since TBD
	 *
	 * @var boolean
	 */
	protected $value;

	/**
	 * The callback that will be used to set the value when called the first time.
	 *
	 * @since TBD
	 *
	 * @var callable
	 */
	protected $value_callback;

	/**
	 * Lazy_Boolean constructor.
	 *
	 * @param callable $callback The callback that will be used to populate the value on the first fetch.
	 */
	public function __construct( callable $callback ) {
		$this->value_callback  = $callback;
	}

	/**
	 * Inits, and returns, the boolean value.
	 *
	 * @since TBD
	 *
	 * @return boolean The value.
	 */
	public function __toBool() {
		if ( null !== $this->value ) {
			return $this->value;
		}

		$this->value = call_user_func( $this->value_callback );
		// ensure we have a boolean.
		$this->value = tribe_is_truthy( $this->value );
		$this->resolved();

		return $this->value;
	}

	/**
	 * Returns the value, just a proxy of the `__toBool` method.
	 *
	 * @since TBD
	 *
	 * @return string The value.
	 */
	public function value() {
		return $this->__toBool();
	}
}
