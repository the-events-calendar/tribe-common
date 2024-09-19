<?php
/**
 * Container element.
 *
 * @since TBD
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

/**
 * Interface Entity_With_Children
 *
 * @since TBD
 */
interface Element_With_Children extends Element {

	/**
	 * Get the children of the container.
	 *
	 * @return Element[]
	 */
	public function get_children(): array;
}
