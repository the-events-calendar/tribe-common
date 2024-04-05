<?php
/**
 * Abstract Manager class for Integrations.
 *
 * @since   TBD
 *
 * @package TEC\Integrations\Contracts
 */

namespace TEC\Integrations\Contracts;

/**
 * Class Manager_Abstract
 *
 * @since   TBD
 *
 * @package Tribe\Events\Integrations\plugins\Elementor
 */
abstract class Manager_Abstract {
	/**
	 * @var string Type of object.
	 */
	protected $type;

	/**
	 * @var array Collection of objects to register.
	 */
	protected $objects;

	/**
	 * Returns an associative array of objects to be registered.
	 *
	 * @since  TBD
	 *
	 * @return array An array in the shape `[ <slug> => <class> ]`.
	 */
	public function get_registered_objects() {
		/**
		 * Filters the list of objects available and registered.
		 *
		 * Both classes and built objects can be associated with a slug; if bound in the container the classes
		 * will be built according to the binding rules; objects will be returned as they are.
		 *
		 * @since TBD
		 *
		 * @param array $widgets An associative array of objects in the shape `[ <slug> => <class> ]`.
		 */
		return (array) apply_filters( "tec_registered_{$this->type}", $this->objects, $this );
	}

	/**
	 * Registers the objects.
	 *
	 * @since TBD
	 */
	abstract public function register();
}
