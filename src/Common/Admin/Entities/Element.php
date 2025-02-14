<?php
/**
 * Element Interface.
 *
 * @since 6.1.0
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

/**
 * Interface Element
 *
 * @since 6.1.0
 */
interface Element {

	/**
	 * Render the element.
	 *
	 * @since 6.1.0
	 *
	 * @return void
	 */
	public function render();
}
