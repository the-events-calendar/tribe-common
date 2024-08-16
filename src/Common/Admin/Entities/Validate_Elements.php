<?php
/**
 * Validate elements trait.
 *
 * @since TBD
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

use InvalidArgumentException;

/**
 * Trait Validate_Heading_Level
 *
 * @since TBD
 */
trait Validate_Elements {

	/**
	 * Validate the heading level.
	 *
	 * @param int $level The heading level.
	 *
	 * @return void
	 * @throws InvalidArgumentException If the heading level is invalid.
	 */
	protected function validate_level( int $level ) {
		if ( $level < 1 || $level > 6 ) {
			throw new InvalidArgumentException( esc_html__( 'Heading level must be between 1 and 6', 'tribe-common' ) );
		}
	}

	/**
	 * Validate that an object is an instance of a class.
	 *
	 * @param object $thing     The object to validate.
	 * @param string $classname The class name to validate against.
	 *
	 * @return void
	 * @throws InvalidArgumentException If the object is not an instance of the class.
	 */
	protected function validate_instanceof( $thing, $classname ) {
		if ( ! $thing instanceof $classname ) {
			throw new InvalidArgumentException( esc_html__( 'Invalid class instance.', 'tribe-common' ) );
		}
	}
}
