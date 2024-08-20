<?php
/**
 * Heading element.
 *
 * @since TBD
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

use InvalidArgumentException;
use LogicException;
use Tribe\Utils\Element_Classes;

/**
 * Class Heading
 *
 * @since TBD
 */
class Heading extends Base_Entity {

	use Validate_Elements;

	/**
	 * The heading content.
	 *
	 * @var string
	 */
	protected string $content = '';

	/**
	 * The heading level.
	 *
	 * @var int
	 */
	protected int $level = 1;

	/**
	 * The maximum heading level.
	 *
	 * @var int
	 */
	protected int $max_level = 6;

	/**
	 * Heading constructor.
	 *
	 * @param string           $content The content for the heading.
	 * @param int              $level   The level for the heading.
	 * @param ?Element_Classes $classes The classes for the heading.
	 */
	public function __construct( string $content, int $level = 1, ?Element_Classes $classes = null ) {
		$this->content = $content;

		$this->validate_level( $level );
		$this->level = $level;

		if ( $classes ) {
			$this->set_classes( $classes );
		}
	}

	/**
	 * Render the element.
	 *
	 * @return void
	 */
	public function render() {
		/*
		 * The heading level is dynamic and should not be escaped. The reasons that the heading level is
		 * not escaped are as follows:
		 *
		 * 1. The heading level is type-cast as an integer in the constructor. Non-integers will generate an error.
		 * 2. The heading level is validated in the constructor. The validation ensures that the heading level is
		 *    within the range of 1-6.
		 * 3. The tag_escape() function (which should be used for escaping HTML tags) operates on strings, not integers.
		 */
		printf(
			'<h%1$d class="%2$s">%3$s</h%1$d>',
			$this->level, // phpcs:ignore StellarWP.XSS.EscapeOutput,WordPress.Security.EscapeOutput.OutputNotEscaped
			esc_attr( $this->get_classes() ),
			esc_html( $this->content )
		);
	}

	/**
	 * Validate the heading level.
	 *
	 * @param int $level The heading level.
	 *
	 * @return void
	 * @throws InvalidArgumentException If the heading level is invalid.
	 * @throws LogicException If the maximum heading level is greater than 6.
	 */
	private function validate_level( int $level ) {
		if ( $this->max_level > 6 ) {
			throw new LogicException( esc_html__( 'The maximum heading level must be 6 or less', 'tribe-common' ) );
		}

		if ( $level < 1 || $level > $this->max_level ) {
			throw new InvalidArgumentException(
				sprintf(
					/* translators: %d: The maximum heading level. */
					esc_html__( 'Heading level must be between 1 and %d', 'tribe-common' ),
					$this->max_level
				)
			);
		}
	}
}
