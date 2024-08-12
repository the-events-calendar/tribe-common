<?php
/**
 *
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

/**
 * Class Link
 *
 * @since TBD
 */
class Link implements Element {

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
	 * @param string $url  The URL for the link.
	 * @param string $text The text for the link.
	 */
	public function __construct( string $url, string $text ) {
		$this->url  = $url;
		$this->text = $text;
	}

	/**
	 * Render the link.
	 *
	 * @return void
	 */
	public function render() {
		printf(
			'<a href="%s">%s</a>',
			esc_url( $this->url ),
			esc_html( $this->text )
		);
	}

	/**
	 * The __toString method allows a class to decide how it will react when it is converted to a string.
	 *
	 * @link https://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
	 *
	 * @return string
	 */
	public function __toString(): string {
		ob_start();
		$this->render();

		return ob_get_clean();
	}
}
