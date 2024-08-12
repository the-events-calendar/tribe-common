<?php
/**
 *
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

/**
 * Class Paragraph
 *
 * @since TBD
 */
class Paragraph implements Element {

	/**
	 * The paragraph content.
	 *
	 * @var string
	 */
	private string $content = '';

	/**
	 * @var string[]
	 */
	private array $classes;

	/**
	 * Paragraph constructor.
	 *
	 * @param string $content The content for the paragraph.
	 */
	public function __construct( string $content, $classes = [] ) {
		$this->content = $content;
		$this->classes = array_filter(
			$classes,
			function( $class ) {
				return is_string( $class );
			}
		);
	}

	/**
	 * Render the paragraph content.
	 *
	 * @return void
	 */
	public function render() {
		printf(
			'<p class="%s">%s</p>',
			esc_attr( implode( ' ', $this->classes ) ),
			esc_html( $this->content )
		);
	}
}
