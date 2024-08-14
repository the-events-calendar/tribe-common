<?php
/**
 * Heading element.
 *
 * @since TBD
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

use Tribe\Utils\Element_Classes;

/**
 * Class Heading
 *
 * @since TBD
 */
class Heading extends Base_Entity {

	use Validate_Elements;

	/**
	 * The heading content.
	 *
	 * @var string
	 */
	private string $content = '';

	/**
	 * The heading level.
	 *
	 * @var int
	 */
	private int $level = 1;

	/**
	 * Heading constructor.
	 *
	 * @param string           $content The content for the heading.
	 * @param int              $level   The level for the heading.
	 * @param ?Element_Classes $classes The classes for the heading.
	 */
	public function __construct( string $content, int $level = 1, ?Element_Classes $classes = null ) {
		$this->content = $content;

		$this->validate_level( $level );
		$this->level = $level;

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
			'<h%1$d class="%2$s">%3$s</h%1$d>',
			$this->level, // phpcs:ignore StellarWP.XSS.EscapeOutput
			esc_attr( $this->get_classes() ),
			esc_html( $this->content )
		);
	}
}
