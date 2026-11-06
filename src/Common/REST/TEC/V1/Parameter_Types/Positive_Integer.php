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
			if ( ! is_numeric( $value ) || (int) $value != $value || (int) $value <= 0 ) {
				// phpcs:disable WordPress.Security.EscapeOutput.ExceptionNotEscaped -- REST error: the message becomes a WP_Error in a JSON response, not HTML output.
				throw InvalidRestArgumentException::create(
					// translators: 1) is the name of the parameter.
					sprintf( __( 'Argument `{%1$s}` must be a positive integer.', 'tribe-common' ), $this->get_name() ),
					$this->get_name(),
					'tec_rest_invalid_positive_integer_argument',
					// translators: 1) is the name of the parameter.
					sprintf( __( 'The argument `{%1$s}` is not a positive integer.', 'tribe-common' ), $this->get_name() )
				);
				// phpcs:enable WordPress.Security.EscapeOutput.ExceptionNotEscaped
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
