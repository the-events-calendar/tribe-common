<?php
/**
 * An array whose elements will be lazily fetched.
 *
 * @since   TBD
 * @package Tribe\Utils
 */

namespace Tribe\Utils;

/**
 * Class Array_Promise
 * @since   TBD
 * @package Tribe\Utils
 */
class Lazy_Collection implements Collection_Interface {
	use Collection_Trait;

	/**
	 * The callback in charge of providing the elements.
	 *
	 * @var callable
	 */
	protected $callback;

	/**
	 * The elements of the array.
	 *
	 * @var array
	 */
	protected $items;

	/**
	 * Array_Promise constructor.
	 *
	 * @since TBD
	 *
	 * @param callable $callback The callback that will be used to populate the elements.
	 */
	public function __construct( callable $callback ) {
		$this->callback = $callback;
	}

	/**
	 * Fetches the array items and returns them.
	 *
	 * @since TBD
	 *
	 * @return array The array items.
	 */
	public function all() {
		$this->resolve();

		return $this->items;
	}

	/**
	 * Fills the array elements from the callback if required.
	 *
	 * @since TBD
	 */
	protected function resolve() {
		if ( null !== $this->items ) {
			return;
		}

		$items       = call_user_func( $this->callback );
		$this->items = (array) $items;
	}
}
