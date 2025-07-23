<?php
/**
 * Request body collection.
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
 * Request body collection.
 *
 * @since TBD
 */
class RequestBodyCollection extends Collection {
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
}
