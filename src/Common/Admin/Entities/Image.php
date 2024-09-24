<?php
/**
 * Image element.
 *
 * @since 6.1.0
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

use Tribe\Utils\Element_Attributes as Attributes;

/**
 * Class Image
 *
 * @since 6.1.0
 */
class Image extends Base_Entity {

	/**
	 * The URL for the image.
	 *
	 * @var string
	 */
	protected string $url = '';

	/**
	 * Image constructor.
	 *
	 * @since 6.1.0
	 *
	 * @param string      $url        The URL for the image.
	 * @param ?Attributes $attributes The attributes for the image element.
	 */
	public function __construct( string $url, ?Attributes $attributes = null ) {
		$this->url = $url;

		if ( $attributes ) {
			$this->attributes = $attributes;
		}
	}

	/**
	 * Render the image.
	 *
	 * @since 6.1.0
	 *
	 * @return void
	 */
	public function render() {
		printf(
			'<img src="%s" %s />',
			esc_url( $this->url ),
			$this->get_attributes() // phpcs:ignore StellarWP.XSS.EscapeOutput,WordPress.Security.EscapeOutput
		);
	}
}
