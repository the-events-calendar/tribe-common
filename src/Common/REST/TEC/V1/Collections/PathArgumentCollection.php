<?php
/**
 * Path argument collection.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Collections
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Collections;

use TEC\Common\REST\TEC\V1\Contracts\Parameter as Parameter_Contract;
use TEC\Common\REST\TEC\V1\Abstracts\Parameter;

// phpcs:disable StellarWP.Classes.ValidClassName.NotSnakeCase

/**
 * Path argument collection.
 *
 * @since 6.9.0
 */
class PathArgumentCollection extends Collection {
	/**
	 * Sets a value in the collection.
	 *
	 * @since 6.9.0
	 *
	 * @param string             $offset The offset to set.
	 * @param Parameter_Contract $value  The value to set.
	 */
	protected function set( string $offset, Parameter_Contract $value ): void {
		$value->set_location( Parameter::LOCATION_PATH )->set_required( true );
		$this->resources[ $offset ] = $value;
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
}
