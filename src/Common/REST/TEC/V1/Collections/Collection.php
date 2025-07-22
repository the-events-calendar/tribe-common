<?php
/**
 * Collection.
 *
 * @since TBD
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
 * @since TBD
 */
abstract class Collection implements ArrayAccess, Iterator, Countable, JsonSerializable {

	/**
	 * Collection of items.
	 *
	 * @since TBD
	 *
	 * @var array<Parameter>
	 */
	private array $resources = [];

	/**
	 * Constructor.
	 *
	 * @since TBD
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
	 * @since TBD
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
		return key( $this->resources );
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
	 * @since TBD
	 *
	 * @return array
	 */
	public function to_array(): array {
		return array_merge( ...array_map( fn( Parameter $param ) => [ $param->get_name() => $param->to_array() ], $this->resources ) );
	}

	/**
	 * @inheritDoc
	 */
	public function jsonSerialize(): array {
		return $this->resources;
	}

	/**
	 * Maps the collection to an array.
	 *
	 * @since TBD
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
	 * @since TBD
	 *
	 * @param callable $callback The callback to filter the collection.
	 *
	 * @return Collection
	 */
	public function filter( callable $callback ): Collection {
		return new Collection( array_filter( $this->resources, $callback ) );
	}
}
