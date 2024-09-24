<?php
/**
 * Validate elements trait.
 *
 * @since 6.1.0
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

use InvalidArgumentException;

/**
 * Trait Validate_Elements
 *
 * @since 6.1.0
 */
trait Validate_Elements {

	/**
	 * Validate that an object is an instance of a class.
	 *
	 * @since 6.1.0
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
