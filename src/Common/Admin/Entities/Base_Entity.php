<?php
/**
 * Base Entity.
 *
 * @since 6.1.0
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

use ArrayAccess;
use Tribe\Traits\Array_Access;
use Tribe\Utils\Element_Attributes as Attributes;
use Tribe\Utils\Element_Classes as Classes;

/**
 * Class Base_Entity
 *
 * @since 6.1.0
 */
abstract class Base_Entity implements ArrayAccess, Element {

	use Array_Access;

	/**
	 * The attributes for the entity.
	 *
	 * @var Attributes|null
	 */
	protected ?Attributes $attributes = null;

	/**
	 * The classes for the entity.
	 *
	 * @var ?Classes
	 */
	protected ?Classes $classes = null;

	/**
	 * Convert the entity output to a string.
	 *
	 * @since 6.1.0
	 *
	 * @return string
	 */
	public function __toString() {
		ob_start();
		$this->render();

		return ob_get_clean();
	}

	/**
	 * Get the attributes for the element.
	 *
	 * The attributes retrieved from Element_Attributes::get_attributes_as_string() are already
	 * escaped, so it is not necessary to further escape these attributes.
	 *
	 * @see Element_Attributes::get_attributes_as_string()
	 * @since 6.1.0
	 *
	 * @return string
	 */
	protected function get_attributes(): string {
		return null === $this->attributes
			? ''
			: $this->attributes->get_attributes_as_string();
	}

	/**
	 * Get the classes for the entity.
	 *
	 * @since 6.1.0
	 *
	 * @return string
	 */
	protected function get_classes(): string {
		return null === $this->classes
			? ''
			: $this->classes->get_classes_as_string();
	}

	/**
	 * Get the classes as an attribute.
	 *
	 * @since 6.1.0
	 *
	 * @return string
	 */
	protected function get_class_attribute(): string {
		return null === $this->classes
			? ''
			: $this->classes->get_attribute();
	}

	/**
	 * Set the attributes for the entity.
	 *
	 * @since 6.1.0
	 *
	 * @param Attributes $attributes The attributes to set.
	 *
	 * @return void
	 */
	protected function set_attributes( Attributes $attributes ) {
		$this->attributes = $attributes;
	}

	/**
	 * Set the classes for the entity.
	 *
	 * @since 6.1.0
	 *
	 * @param Classes $classes The classes for the entity.
	 *
	 * @return void
	 */
	protected function set_classes( Classes $classes ) {
		$this->classes = $classes;
	}
}
