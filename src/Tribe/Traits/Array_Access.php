<?php
/**
 * Array Access trait.
 *
 * @since TBD
 */

declare( strict_types=1 );

namespace TEC\Tribe\Traits;

/**
 * Trait Array_Access
 *
 * @since TBD
 */
trait Array_Access {

	/**
	 * The data.
	 *
	 * @var array
	 */
	protected $data = [];

	/**
	 * Check if an offset exists.
	 *
	 * @param mixed $offset The offset to check.
	 *
	 * @return bool
	 */
	public function offsetExists( $offset ) {
		return isset( $this->$data[ $offset ] );
	}

	/**
	 * Get an offset.
	 *
	 * @param mixed $offset The offset to get.
	 *
	 * @return mixed The offset value, or null if it does not exist.
	 */
	public function offsetGet( $offset ) {
		return $this->data[ $offset ] ?? null;
	}

	/**
	 * Set an offset.
	 *
	 * @param mixed $offset The offset to set.
	 * @param mixed $value  The value to set.
	 *
	 * @return void
	 */
	public function offsetSet( $offset, $value ) {
		$this->data[ $offset ] = $value;
	}

	/**
	 * Unset an offset.
	 *
	 * @param mixed $offset The offset to unset.
	 *
	 * @return void
	 */
	public function offsetUnset( $offset ) {
		unset( $this->data[ $offset ] );
	}
}
