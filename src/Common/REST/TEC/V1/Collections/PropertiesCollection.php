<?php
/**
 * Properties collection.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Collections
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Collections;

use TEC\Common\REST\TEC\V1\Contracts\Parameter;
use TEC\Common\REST\TEC\V1\Parameter_Types\Entity;
use TEC\Common\REST\TEC\V1\Parameter_Types\Array_Of_Type;
use TEC\Common\REST\TEC\V1\Parameter_Types\Definition_Parameter;
use TEC\Common\REST\TEC\V1\Contracts\Definition_Interface as Definition;

// phpcs:disable StellarWP.Classes.ValidClassName.NotSnakeCase

/**
 * Properties collection.
 *
 * @since 6.9.0
 */
class PropertiesCollection extends Collection {
	/**
	 * Returns the collection as an array.
	 *
	 * @since 6.9.0
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
					$property['required'],
				);

				return $property;
			},
			array_merge(
				...$this->map(
					fn( Parameter $property ) => [
						$property->get_name() => array_merge(
							$property->to_array(),
							$property instanceof Entity ||
							(
								$property instanceof Array_Of_Type && (
									$property->get_an_item() instanceof Entity ||
									$property->get_an_item() instanceof Definition ||
									$property->get_an_item() instanceof Definition_Parameter
								)
							) ? [] : array_filter( [ 'example' => $property->get_example() ], static fn( $value ) => null !== $value )
						),
					]
				)
			)
		);
	}
}
