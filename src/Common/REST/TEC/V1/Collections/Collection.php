<?php
/**
 * Collection.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Collections
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Collections;

use TEC\Common\REST\TEC\V1\Contracts\Parameter;
use JsonSerializable;
use ArrayAccess;
use Countable;
use Iterator;

/**
 * Collection.
 *
 * @since 6.9.0
 */
abstract class Collection implements ArrayAccess, Iterator, Countable, JsonSerializable {

	/**
	 * Collection of items.
	 *
	 * @since 6.9.0
	 *
	 * @var array<Parameter>
	 */
	protected array $resources = [];

	/**
	 * Constructor.
	 *
	 * @since 6.9.0
	 *
	 * @param array<Parameter> $resources An array of items.
	 */
	public function __construct( array $resources = [] ) {
		foreach ( $resources as $offset => $value ) {
			$this->set( (string) $offset, $value );
		}
	}

	/**
	 * Sets a value in the collection.
	 *
	 * @since 6.9.0
	 *
	 * @param string    $offset The offset to set.
	 * @param Parameter $value  The value to set.
	 */
	protected function set( string $offset, Parameter $value ): void {
		$this->resources[ $offset ] = $value;
	}

	/**
	 * @inheritDoc
	 */
	public function current(): Parameter {
		return current( $this->resources );
	}

	/**
	 * @inheritDoc
	 */
	public function key(): ?string {
		return (string) key( $this->resources );
	}

	/**
	 * @inheritDoc
	 */
	public function next(): void {
		next( $this->resources );
	}

	/**
	 * @inheritDoc
	 *
	 * @param TKey $offset The offset to check.
	 *
	 * @return bool
	 */
	public function offsetExists( $offset ): bool {
		return array_key_exists( $offset, $this->resources );
	}

	/**
	 * @inheritDoc
	 *
	 * @param string $offset The offset to get.
	 *
	 * @return Parameter|null
	 */
	public function offsetGet( $offset ): ?Parameter {
		return $this->resources[ $offset ] ?? null;
	}

	/**
	 * @inheritDoc
	 *
	 * @param string    $offset The offset to set.
	 * @param Parameter $value  The value to set.
	 */
	public function offsetSet( $offset, $value ): void {
		if ( ! $offset ) {
			$offset = (string) count( $this->resources );
		}
		$this->set( $offset, $value );
	}

	/**
	 * @inheritDoc
	 *
	 * @param string $offset The offset to unset.
	 */
	public function offsetUnset( $offset ): void {
		unset( $this->resources[ $offset ] );
	}

	/**
	 * @inheritDoc
	 */
	public function rewind(): void {
		reset( $this->resources );
	}

	/**
	 * @inheritDoc
	 */
	public function valid(): bool {
		return key( $this->resources ) !== null;
	}

	/**
	 * @inheritDoc
	 */
	public function count(): int {
		return count( $this->resources );
	}

	/**
	 * Returns the collection as an array.
	 *
	 * @since 6.9.0
	 *
	 * @return array
	 */
	public function to_array(): array {
		return array_merge( ...array_map( fn( Parameter $param ) => [ $param->get_name() => $param->to_array() ], $this->resources ) );
	}

	/**
	 * Returns the collection as an array.
	 *
	 * @since 6.9.0
	 *
	 * @return array
	 */
	public function jsonSerialize(): array {
		return $this->to_array();
	}

	/**
	 * Maps the collection to an array.
	 *
	 * @since 6.9.0
	 *
	 * @param callable $callback The callback to map the collection to an array.
	 *
	 * @return array
	 */
	public function map( callable $callback ): array {
		return array_map( $callback, $this->resources );
	}

	/**
	 * Filters the collection.
	 *
	 * @since 6.9.0
	 *
	 * @param callable $callback The callback to filter the collection.
	 *
	 * @return Collection
	 */
	public function filter( callable $callback ): Collection {
		return new static( array_filter( $this->resources, $callback ) );
	}

	/**
	 * Gets a parameter from the collection.
	 *
	 * @since 6.10.0
	 *
	 * @param string $parameter_name The name of the parameter to get.
	 *
	 * @return ?Parameter
	 */
	public function get( string $parameter_name ): ?Parameter {
		foreach ( $this->resources as $parameter ) {
			if ( $parameter->get_name() === $parameter_name ) {
				return $parameter;
			}
		}
		return null;
	}
}
