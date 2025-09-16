<?php
/**
 * Separator element.
 *
 * @since 6.1.0
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

use Tribe\Utils\Element_Attributes as Attributes;
use Tribe\Utils\Element_Classes as Classes;

/**
 * Class Separator
 *
 * @since 6.1.0
 */
class Separator extends Base_Entity {

	/**
	 * Separator constructor.
	 *
	 * @since 6.1.0
	 *
	 * @param ?Classes    $classes    The classes for the separator.
	 * @param ?Attributes $attributes The attributes for the separator.
	 */
	public function __construct( ?Classes $classes = null, ?Attributes $attributes = null ) {
		if ( $classes ) {
			$this->set_classes( $classes );
		}

		if ( $attributes ) {
			$this->set_attributes( $attributes );
		}
	}

	/**
	 * Render the element.
	 *
	 * @since 6.1.0
	 *
	 * @return void
	 */
	public function render() {
		printf(
			'<hr class="%s" %s />',
			esc_attr( $this->get_classes() ),
			$this->get_attributes() // phpcs:ignore StellarWP.XSS.EscapeOutput,WordPress.Security.EscapeOutput
		);
	}
}
