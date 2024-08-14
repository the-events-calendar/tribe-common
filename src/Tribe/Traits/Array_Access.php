<?php
/**
 * Array Access trait.
 *
 * @since TBD
 */

declare( strict_types=1 );

namespace Tribe\Traits;

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
	public function offsetExists( $offset ): bool {
		return isset( $this->$data[ $offset ] );
	}

	/**
	 * Get an offset.
	 *
	 * @param mixed $offset The offset to get.
	 *
	 * @return mixed The offset value, or null if it does not exist.
	 */
	public function offsetGet( $offset ): mixed {
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
	public function offsetSet( $offset, $value ): void {
		$this->data[ $offset ] = $value;
	}

	/**
	 * Unset an offset.
	 *
	 * @param mixed $offset The offset to unset.
	 *
	 * @return void
	 */
	public function offsetUnset( $offset ): void {
		unset( $this->data[ $offset ] );
	}
}
