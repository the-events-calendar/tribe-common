<?php
/**
 * Plain text element.
 *
 * @since 6.1.0
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

/**
 * Class Plain_Text
 *
 * @since 6.1.0
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
	 * @since 6.1.0
	 *
	 * @param string $content The content for the text.
	 */
	public function __construct( string $content ) {
		$this->content = $content;
	}

	/**
	 * Render the text content.
	 *
	 * @since 6.1.0
	 *
	 * @return void
	 */
	public function render() {
		echo esc_html( $this->content );
	}
}
