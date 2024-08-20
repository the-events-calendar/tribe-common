<?php
/**
 * Link element.
 *
 * @since TBD
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

use Tribe\Utils\Element_Classes;

/**
 * Class Link
 *
 * @since TBD
 */
class Link extends Base_Entity {

	/**
	 * The URL for the link.
	 *
	 * @var string
	 */
	private string $url = '';

	/**
	 * The text for the link.
	 *
	 * @var string
	 */
	private string $text = '';

	/**
	 * Link constructor.
	 *
	 * @param string           $url     The URL for the link.
	 * @param string           $text    The text for the link.
	 * @param ?Element_Classes $classes The classes for the link.
	 */
	public function __construct( string $url, string $text, ?Element_Classes $classes = null ) {
		$this->url  = $url;
		$this->text = $text;

		if ( $classes ) {
			$this->set_classes( $classes );
		}
	}

	/**
	 * Render the link.
	 *
	 * @return void
	 */
	public function render() {
		printf(
			'<a href="%s" class="%s">%s</a>',
			esc_url( $this->url ),
			esc_attr( $this->get_classes() ),
			esc_html( $this->text )
		);
	}
}
