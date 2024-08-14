<?php
/**
 * Container element.
 *
 * @since TBD
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

use InvalidArgumentException;

/**
 * Class Container.
 *
 * This class is a generic container for elements that contain children.
 *
 * @since TBD
 */
abstract class Container extends Base_Entity {

	use Validate_Elements;

	/**
	 * The children of the container.
	 *
	 * @var Element[]
	 */
	protected array $children = [];

	/**
	 * The type of child elements that the container can contain.
	 *
	 * @var string
	 */
	protected string $child_type = Element::class;

	/**
	 * Add a child to the container.
	 *
	 * @param Element $child The child to add.
	 *
	 * @return static
	 * @throws InvalidArgumentException If the child is not an instance of the child type.
	 */
	public function add_child( $child ) {
		$this->validate_instanceof( $child, $this->child_type );

		$this->children[] = $child;

		return $this;
	}

	/**
	 * Add multiple children to the container.
	 *
	 * @param Element[] $children The children to add.
	 *
	 * @return static
	 */
	public function add_children( array $children ) {
		foreach ( $children as $child ) {
			$this->add_child( $child );
		}

		return $this;
	}

	/**
	 * Render the children of the container.
	 *
	 * @return void
	 */
	protected function render_children() {
		foreach ( $this->children as $child ) {
			$child->render();
		}
	}
}
