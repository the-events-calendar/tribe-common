<?php

namespace TEC\Common\Contracts;

use TEC\Common\StellarWP\ContainerContract\ContainerInterface;
use TEC\Common\Exceptions\Not_Bound_Exception;

use TEC\Common\lucatume\DI52\Container as DI52_Container;

class Container extends DI52_Container implements ContainerInterface {
	/**
	 * Finds an entry of the container by its identifier and returns it.
	 *
	 * @since 5.1.1.2
	 *
	 * @throws Not_Bound_Exception Error while retrieving the entry.
	 *
	 * @param string $id A fully qualified class or interface name or an already built object.
	 *
	 * @return mixed The entry for an id.
	 */
	public function get( $id ) {
		try {
			return parent::get( $id );
		} catch ( \Exception $e ) {
			// Do not chain the previous exception into ours, as it makes the error log confusing.
			throw new Not_Bound_Exception( $e->getMessage(), $e->getCode() );
		}
	}
}
