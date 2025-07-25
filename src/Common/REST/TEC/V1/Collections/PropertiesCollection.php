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
use TEC\Common\REST\TEC\V1\Parameter_Types\Entity;

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
			function ( array $property ): array {
				unset(
					$property['validate_callback'],
					$property['sanitize_callback'],
					$property['explode'],
				);

				return $property;
			},
			array_merge(
				...$this->map(
					fn( Parameter $property ) => [
						$property->get_name() => array_merge(
							$property->to_array(),
							$property instanceof Entity ? [] : array_filter( [ 'example' => $property->get_example() ], static fn( $value ) => null !== $value )
						),
					]
				)
			)
		);
	}
}
