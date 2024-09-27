<?php
/**
 * Container element.
 *
 * @since 6.1.0
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

/**
 * Interface Entity_With_Children
 *
 * @since 6.1.0
 */
interface Element_With_Children extends Element {

	/**
	 * Get the children of the container.
	 *
	 * @since 6.1.0
	 *
	 * @return Element[]
	 */
	public function get_children(): array;
}
