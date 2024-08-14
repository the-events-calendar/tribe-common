<?php
/**
 * Image element.
 *
 * @since TBD
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

use Tribe\Utils\Element_Attributes;

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
	 * Attributes for the image element.
	 *
	 * @var ?Element_Attributes
	 */
	protected ?Element_Attributes $attributes = null;

	/**
	 * Image constructor.
	 *
	 * @param string              $url        The URL for the image.
	 * @param ?Element_Attributes $attributes The attributes for the image element.
	 */
	public function __construct( string $url, ?Element_Attributes $attributes = null ) {
		$this->url = $url;

		if ( $attributes ) {
			$this->attributes = $attributes;
		}
	}

	/**
	 * Get the attributes for the image element.
	 *
	 * @return string
	 */
	protected function get_attributes(): string {
		return $this->attributes?->get_attributes_as_string() ?? '';
	}

	/**
	 * Render the image.
	 *
	 * @return void
	 */
	public function render() {
		printf(
			'<img src="%s" %s />',
			esc_url( $this->url ),
			$this->get_attributes() // phpcs:ignore StellarWP.XSS.EscapeOutput
		);
	}
}
