<?php
/**
 * Positive integer parameter type.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Parameter_Types
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Parameter_Types;

use Closure;
use TEC\Common\REST\TEC\V1\Exceptions\InvalidRestArgumentException;

/**
 * Positive integer parameter type.
 *
 * @since 6.9.0
 */
class Positive_Integer extends Integer {

	/**
	 * @inheritDoc
	 */
	public function get_validator(): Closure {
		return $this->validator ?? function ( $value ): bool {
			if ( ! is_int( $value ) || $value <= 0 ) {
				// translators: 1) is the name of the parameter.
				$exception = new InvalidRestArgumentException( sprintf( __( 'Parameter `{%1$s}` must be a positive integer.', 'tribe-common' ), $this->get_name() ) );
				$exception->set_argument( $this->get_name() );
				$exception->set_internal_error_code( 'tec_rest_invalid_positive_integer_parameter' );

				// translators: 1) is the name of the parameter.
				$exception->set_details( sprintf( __( 'The parameter `{%1$s}` is not a positive integer.', 'tribe-common' ), $this->get_name() ) );
				throw $exception;
			}

			return true;
		};
	}

	/**
	 * @inheritDoc
	 */
	public function get_sanitizer(): Closure {
		return $this->sanitizer ?? fn( $value ): int => absint( $value );
	}

	/**
	 * @inheritDoc
	 */
	public function get_example(): int {
		if ( $this->example ) {
			return $this->example;
		}

		if ( $this->get_minimum() || $this->get_maximum() ) {
			return (int) ceil( ( ( $this->get_minimum() ?? 1 ) + ( $this->get_maximum() ?? 1 ) ) / 2 );
		}

		return 126;
	}
}
