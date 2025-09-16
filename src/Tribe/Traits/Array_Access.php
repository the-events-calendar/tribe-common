<?php
/**
 * Array Access trait.
 *
 * @since 6.1.0
 *
 * phpcs:disable WordPress.NamingConventions.ValidFunctionName
 */

declare( strict_types=1 );

namespace Tribe\Traits;

use ReturnTypeWillChange;

/**
 * Trait Array_Access
 *
 * @since 6.1.0
 */
trait Array_Access {

	/**
	 * The data managed by this object.
	 *
	 * @var array
	 */
	protected $data = [];

	/**
	 * Check if an offset exists.
	 *
	 * @link http://php.net/manual/en/arrayaccess.offsetexists.php
	 *
	 * @param mixed $offset The offset to check.
	 *
	 * @return bool
	 */
	public function offsetExists( $offset ): bool {
		return isset( $this->data[ $offset ] );
	}

	/**
	 * Get an offset.
	 *
	 * @link http://php.net/manual/en/arrayaccess.offsetget.php
	 *
	 * @param mixed $offset The offset to get.
	 *
	 * @return mixed The offset value, or null if it does not exist.
	 */
	#[ReturnTypeWillChange]
	public function offsetGet( $offset ) {
		return $this->data[ $offset ] ?? null;
	}

	/**
	 * Set an offset.
	 *
	 * @link http://php.net/manual/en/arrayaccess.offsetset.php
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
	 * @link http://php.net/manual/en/arrayaccess.offsetunset.php
	 *
	 * @param mixed $offset The offset to unset.
	 *
	 * @return void
	 */
	public function offsetUnset( $offset ): void {
		unset( $this->data[ $offset ] );
	}
}
