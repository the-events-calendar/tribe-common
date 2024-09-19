<?php
/**
 * Br element.
 *
 * @since TBD
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

/**
 * Class Br
 *
 * @since TBD
 */
class Br extends Base_Entity {

	/**
	 * Render the element.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function render() {
		echo '<br>';
	}
}
