<?php
/**
 * Query argument collection.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Collections
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Collections;

use TEC\Common\REST\TEC\V1\Contracts\Parameter;

// phpcs:disable StellarWP.Classes.ValidClassName.NotSnakeCase

/**
 * Query argument collection.
 *
 * @since 6.9.0
 */
class QueryArgumentCollection extends Collection {
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
	 * @inheritDoc
	 */
	public function jsonSerialize(): array {
		return $this->resources;
	}
}
