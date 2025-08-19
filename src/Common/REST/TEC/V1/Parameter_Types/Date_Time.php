<?php
/**
 * Date_Time parameter type.
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
 * Date_Time parameter type.
 *
 * @since 6.9.0
 */
class Date_Time extends Text {

	/**
	 * @inheritDoc
	 */
	public function get_format(): ?string {
		return 'date-time';
	}

	/**
	 * @inheritDoc
	 */
	public function get_validator(): ?Closure {
		return $this->validator ?? function ( $value ): bool {
			if ( null !== $this->get_pattern() && ! preg_match( '/' . $this->get_pattern() . '/', (string) $value ) ) {
				// translators: 1) is the name of the parameter.
				$exception = new InvalidRestArgumentException( sprintf( __( 'Parameter `{%1$s}` must match the pattern.', 'the-events-calendar' ), $this->get_name() ) );

				$exception->set_argument( $this->get_name() );
				$exception->set_internal_error_code( 'tec_rest_invalid_' . str_replace( '-', '_', $this->get_format() ) . '_parameter' );

				// translators: 1) is the name of the parameter, 2) is the pattern.
				$exception->set_details( sprintf( __( 'The parameter `{%1$s}` does not match the pattern `%2$s`.', 'the-events-calendar' ), $this->get_name(), $this->get_pattern() ) );
				throw $exception;
			}

			if ( ! is_numeric( $value ) && ! ( is_string( $value ) && strtotime( $value ) ) ) {
				// translators: 1) is the name of the parameter.
				$exception = new InvalidRestArgumentException( sprintf( __( 'Parameter `{%1$s}` must be a date-time.', 'the-events-calendar' ), $this->get_name() ) );

				$exception->set_argument( $this->get_name() );
				$exception->set_internal_error_code( 'tec_rest_invalid_' . str_replace( '-', '_', $this->get_format() ) . '_parameter' );

				// translators: 1) is the name of the parameter, 2) is the format of the parameter (date or date-time).
				$exception->set_details( sprintf( __( 'We cannot parse the parameter `{%1$s}` as a %2$s.', 'the-events-calendar' ), $this->get_name(), $this->get_format() ) );
				throw $exception;
			}

			return true;
		};
	}

	/**
	 * @inheritDoc
	 */
	public static function get_subitem_format(): array {
		return [
			'type'   => 'string',
			'format' => 'date-time',
		];
	}

	/**
	 * @inheritDoc
	 */
	public function get_example(): string {
		return $this->example ?? '2025-06-05T12:00:00Z';
	}
}
