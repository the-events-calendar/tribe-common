<?php
/**
 * Separator element.
 *
 * @since TBD
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

use Tribe\Utils\Element_Classes;

/**
 * Class Separator
 *
 * @since TBD
 */
class Separator extends Base_Entity {

	/**
	 * Separator constructor.
	 *
	 * @param Element_Classes|null $classes The classes for the separator.
	 */
	public function __construct( ?Element_Classes $classes = null ) {
		if ( $classes ) {
			$this->set_classes( $classes );
		}
	}

	/**
	 * Render the element.
	 *
	 * @return void
	 */
	public function render() {
		printf(
			'<hr class="%s">',
			esc_attr( $this->get_classes() )
		);
	}
}
