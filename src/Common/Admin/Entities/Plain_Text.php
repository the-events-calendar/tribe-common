<?php
/**
 * Plain text element.
 *
 * @since TBD
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

/**
 * Class Plain_Text
 *
 * @since TBD
 */
class Plain_Text extends Base_Entity {

	/**
	 * The text content.
	 *
	 * @var string
	 */
	private string $content = '';

	/**
	 * Plain_Text constructor.
	 *
	 * @param string $content The content for the text.
	 */
	public function __construct( string $content ) {
		$this->content = $content;
	}

	/**
	 * Render the text content.
	 *
	 * @return void
	 */
	public function render() {
		echo esc_html( $this->content );
	}
}
