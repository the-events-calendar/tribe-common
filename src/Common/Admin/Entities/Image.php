<?php
/**
 * Image element.
 *
 * @since TBD
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

use Tribe\Utils\Element_Attributes as Attributes;

/**
 * Class Image
 *
 * @since TBD
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
	 * @since TBD
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
	 * @since TBD
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
