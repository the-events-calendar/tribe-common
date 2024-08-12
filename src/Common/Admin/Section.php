<?php
/**
 * Section.
 *
 * @since TBD
 */

declare( strict_types=1 );

namespace TEC\Common\Admin;

use InvalidArgumentException;

/**
 * Class Section
 *
 * @since TBD
 */
abstract class Section {

	/**
	 * Title for the sidebar.
	 *
	 * @var string
	 */
	protected string $title = '';

	/**
	 * Classes for the title element.
	 *
	 * @var array
	 */
	protected array $title_classes = [];

	/**
	 * Level for the title element.
	 *
	 * @var int
	 */
	protected int $title_level = 2;

	/**
	 * Set the title for the sidebar.
	 *
	 * @param string $title The title for the sidebar.
	 * @param int    $level The level for the title element.
	 *
	 * @return static
	 */
	public function set_title( string $title, int $level = 2 ) {
		$this->validate_title_level( $level );
		$this->title       = $title;
		$this->title_level = $level;

		return $this;
	}

	/**
	 * Set the classes for the title element.
	 *
	 * @param array $classes The classes for the title element.
	 *
	 * @return static
	 */
	public function set_title_classes( array $classes ) {
		$classes = array_filter(
			$classes,
			function( $class ) {
				return is_string( $class );
			}
		);

		$this->title_classes = $classes;

		return $this;
	}

	/**
	 * Validate the level for the title element.
	 *
	 * @param int $level The level for the title element.
	 *
	 * @return void
	 * @throws InvalidArgumentException If the level is not between 1 and 6.
	 */
	private function validate_title_level( int $level ) {
		if ( $level < 1 || $level > 6 ) {
			throw new InvalidArgumentException( esc_html__( 'Title level must be between 1 and 6', 'tribe-common' ) );
		}
	}

	/**
	 * Render the title for the sidebar.
	 *
	 * @return void
	 */
	protected function render_title() {
		if ( empty( $this->title ) ) {
			return;
		}

		printf(
			'<h%1$d class="%2$s">%3$s</h%1$d>',
			$this->title_level,
			esc_attr( implode( ' ', $this->title_classes ) ),
			esc_html( $this->title )
		);
	}

	/**
	 * Render the section content.
	 *
	 * @return void
	 */
	abstract public function render();
}
