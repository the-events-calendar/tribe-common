<?php
/**
 * Container element.
 *
 * @since TBD
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

/**
 * Class Container.
 *
 * This class is a generic container for elements that contain children.
 *
 * @since TBD
 */
abstract class Container extends Base_Entity {

	/**
	 * The children of the container.
	 *
	 * @var Element[]
	 */
	protected array $children = [];

	/**
	 * Add a child to the container.
	 *
	 * @param Element $child The child to add.
	 *
	 * @return static
	 */
	public function add_child( Element $child ) {
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
