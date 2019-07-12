<?php
/**
 * Implements all the methods required by the `Tribe\Utils\Collection` interface minus the `all` one.
 *
 * The trait will also implement the `ArrayAccess`, `Iterator` and `Countable` interface methods.
 *
 * @since   TBD
 * @package Tribe\Utils
 */

namespace Tribe\Utils;

/**
 * Trait Collection_Trait
 * @since   TBD
 * @package Tribe\Utils
 */
trait Collection_Trait {
	/**
	 * Returns the first item in the collection.
	 *
	 * @since TBD
	 *
	 * @return mixed The first item in the collection.
	 */
	public function first() {
		$items = $this->all();

		return reset( $items );
	}

	/**
	 * Returns the last item in the collection.
	 *
	 * @since TBD
	 *
	 * @return mixed The last item in the collection.
	 */
	public function last() {
		$items = $this->all();

		return end( $items );
	}

	/**
	 * Returns the nth item in the collection.
	 *
	 * @since TBD
	 *
	 * @param int $n The 1-based index of the item to return. It's not 0-based, `1` will return the first item.
	 *
	 * @return mixed|null The nth item in the collection or `null` if not set.
	 */
	public function nth( $n ) {
		$items = array_values( $this->all() );

		return isset( $items[ $n ] );
	}

	/**
	 * {@inheritDoc}
	 */
	public function offsetExists( $offset ) {
		$items = $this->all();

		return isset( $items[ $offset ] );
	}

	/**
	 * {@inheritDoc}
	 */
	public function offsetGet( $offset ) {
		$items = $this->all();

		return isset( $items[ $offset ] )
			? $items[ $offset ]
			: null;
	}

	/**
	 * {@inheritDoc}
	 */
	public function offsetSet( $offset, $value ) {
		$this->items = $this->all();

		$this->items[ $offset ] = $value;
	}

	/**
	 * {@inheritDoc}
	 */
	public function offsetUnset( $offset ) {
		$this->items = $this->all();

		unset( $this->items[ $offset ] );
	}

	/**
	 * {@inheritDoc}
	 */
	public function next() {
		$this->items = $this->all();

		return next( $this->items );
	}

	/**
	 * {@inheritDoc}
	 */
	public function valid() {
		$this->items = $this->all();

		return ( isset( $this->items[ $this->key() ] ) );
	}

	/**
	 * {@inheritDoc}
	 */
	public function key() {
		$this->items = $this->all();

		return isset( $this->items[ $this->current() ] )
			? array_search( $this->current(), $this->items, true )
			: null;
	}

	/**
	 * {@inheritDoc}
	 */
	public function current() {
		$this->items = $this->all();

		return current( $this->items );
	}

	/**
	 * {@inheritDoc}
	 */
	public function rewind() {
		$this->items = $this->all();

		reset( $this->items );
	}

	/**
	 * {@inheritDoc}
	 */
	public function count() {
		$this->items = $this->all();

		return count( $this->items );
	}
}
