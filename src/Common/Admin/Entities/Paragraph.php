<?php
/**
 * Paragraph element.
 *
 * @since TBD
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

use Tribe\Utils\Element_Classes;

/**
 * Class Paragraph
 *
 * @since TBD
 */
class Paragraph extends Base_Entity {

	/**
	 * The paragraph content.
	 *
	 * @var string
	 */
	private string $content = '';

	/**
	 * Paragraph constructor.
	 *
	 * @param string           $content The content for the paragraph.
	 * @param ?Element_Classes $classes The classes for the paragraph.
	 */
	public function __construct( string $content, ?Element_Classes $classes = null ) {
		$this->content = $content;

		if ( $classes ) {
			$this->set_classes( $classes );
		}
	}

	/**
	 * Render the paragraph content.
	 *
	 * @return void
	 */
	public function render() {
		printf(
			'<p class="%s">%s</p>',
			esc_attr( $this->get_classes() ),
			esc_html( $this->content )
		);
	}
}
