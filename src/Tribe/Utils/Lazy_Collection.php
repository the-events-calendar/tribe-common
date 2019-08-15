<?php
/**
 * An array whose elements will be lazily fetched.
 *
 * Example usage:
 * ```php
 * $callback = static function(){
 *      $posts = costly_get_posts_call();
 *
 *      return $posts;
 * };
 *
 * // The costly query is not ran now!
 * $collection = new Lazy_Collection( $callback );
 *
 * // If we need to get the elements, then the costly query is made.
 * if( $really_needs_the_posts ){
 *      $posts = $collection->all;
 * }
 * ````
 *
 * @since   TBD
 * @package Tribe\Utils
 */

namespace Tribe\Utils;

/**
 * Class Lazy_Collection
 *
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

	/**
	 * Allows accessing the collection methods using properties.
	 *
	 * E.g. `$collection->first` is equivalent to `$collection->first()`.
	 *
	 * @since TBD
	 *
	 * @param string $property The name of the property to access.
	 *
	 * @return mixed|null The return value of the collection corresponding method or `null` if the collection does not
	 *                    have that method.
	 */
	public function __get( $property ) {
		if ( method_exists( $this, $property ) ) {
			return call_user_func( [ $this, $property ] );
		}

		return null;
	}
}
