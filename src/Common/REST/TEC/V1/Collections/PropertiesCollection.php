<?php
/**
 * Properties collection.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Collections
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Collections;

use TEC\Common\REST\TEC\V1\Contracts\Parameter;

// phpcs:disable StellarWP.Classes.ValidClassName.NotSnakeCase

/**
 * Properties collection.
 *
 * @since TBD
 */
class PropertiesCollection extends Collection {
	/**
	 * Returns the collection as an array.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function to_array(): array {
		return array_map(
			function ( array $header ): array {
				unset(
					$header['name'],
					$header['in'],
					$header['example'],
					$header['explode'],
					$header['schema']['uniqueItems'],
				);

				return $header;
			},
			array_merge( ...$this->map( fn( Parameter $header ) => [ $header->get_name() => $header->to_array() ] ) )
		);
	}
}
