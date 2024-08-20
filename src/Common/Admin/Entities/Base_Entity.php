<?php
/**
 * Base Entity.
 *
 * @since TBD
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

use ArrayAccess;
use Stringable;
use Tribe\Traits\Array_Access;
use Tribe\Utils\Element_Classes;

/**
 * Class Base_Entity
 *
 * @since TBD
 */
abstract class Base_Entity implements ArrayAccess, Element, Stringable {

	use Array_Access;

	/**
	 * The classes for the entity.
	 *
	 * @var ?Element_Classes
	 */
	protected ?Element_Classes $classes = null;

	/**
	 * Convert the entity output to a string.
	 *
	 * @return string
	 */
	public function __toString() {
		ob_start();
		$this->render();

		return ob_get_clean();
	}

	/**
	 * Get the classes for the entity.
	 *
	 * @return string
	 */
	protected function get_classes(): string {
		if ( ! $this->classes ) {
			return '';
		}

		return $this->classes->get_classes_as_string();
	}

	/**
	 * Set the classes for the entity.
	 *
	 * @param Element_Classes $classes The classes for the entity.
	 *
	 * @return void
	 */
	protected function set_classes( Element_Classes $classes ) {
		$this->classes = $classes;
	}
}
