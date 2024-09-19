<?php
/**
 * Link element.
 *
 * @since TBD
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

use Tribe\Utils\Element_Attributes as Attributes;
use Tribe\Utils\Element_Classes as Classes;

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
	 * @since TBD
	 *
	 * @param string      $url        The URL for the link.
	 * @param string      $text       The text for the link.
	 * @param ?Classes    $classes    The classes for the link.
	 * @param ?Attributes $attributes The attributes for the link.
	 */
	public function __construct( string $url, string $text, ?Classes $classes = null, ?Attributes $attributes = null ) {
		$this->url  = $url;
		$this->text = $text;

		if ( $classes ) {
			$this->set_classes( $classes );
		}

		if ( $attributes ) {
			$this->set_attributes( $attributes );
		}
	}

	/**
	 * Render the link.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function render() {
		printf(
			'<a href="%s" class="%s" %s>%s</a>',
			esc_url( $this->url ),
			esc_attr( $this->get_classes() ),
			$this->get_attributes(), // phpcs:ignore StellarWP.XSS.EscapeOutput,WordPress.Security.EscapeOutput
			esc_html( $this->text )
		);
	}
}
