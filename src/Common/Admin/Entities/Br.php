<?php
/**
 * Br element.
 *
 * @since 6.1.0
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

/**
 * Class Br
 *
 * @since 6.1.0
 */
class Br extends Base_Entity {

	/**
	 * Render the element.
	 *
	 * @since 6.1.0
	 *
	 * @return void
	 */
	public function render() {
		echo '<br>';
	}
}
