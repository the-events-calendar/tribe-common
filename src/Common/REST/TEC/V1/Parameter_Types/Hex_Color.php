<?php
/**
 * Hex_Color parameter type.
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
 * Hex_Color parameter type.
 *
 * @since 6.9.0
 */
class Hex_Color extends Text {

	/**
	 * @inheritDoc
	 */
	public function get_format(): ?string {
		return 'hex-color';
	}

	/**
	 * @inheritDoc
	 */
	public function get_validator(): ?Closure {
		return $this->validator ?? function ( $value ): bool {
			if ( ! is_string( $value ) ) {
				// translators: 1) is the name of the parameter.
				$exception = new InvalidRestArgumentException( sprintf( __( 'Parameter `{%1$s}` must be a string.', 'the-events-calendar' ), $this->get_name() ) );
				$exception->set_argument( $this->get_name() );
				$exception->set_internal_error_code( 'tec_rest_invalid_hex_color_parameter' );

				// translators: 1) is the name of the parameter.
				$exception->set_details( sprintf( __( 'The parameter `{%1$s}` is not a string.', 'the-events-calendar' ), $this->get_name() ) );
				throw $exception;
			}

			if ( ! ( preg_match( '/^#([0-9a-fA-F]{3})$/', (string) $value ) || preg_match( '/^#([0-9a-fA-F]{6})$/', (string) $value ) ) ) {
				// translators: 1) is the name of the parameter.
				$exception = new InvalidRestArgumentException( sprintf( __( 'Parameter `{%1$s}` must be a valid hex color.', 'the-events-calendar' ), $this->get_name() ) );
				$exception->set_argument( $this->get_name() );
				$exception->set_internal_error_code( 'tec_rest_invalid_hex_color_parameter' );

				// translators: 1) is the name of the parameter.
				$exception->set_details( sprintf( __( 'The parameter `{%1$s}` is not a valid hex color. It should be a string like `#000000` or `#000`.', 'the-events-calendar' ), $this->get_name() ) );
				throw $exception;
			}

			return true;
		};
	}

	/**
	 * @inheritDoc
	 */
	public function get_sanitizer(): ?Closure {
		return $this->sanitizer ?? fn( string $value ): string => strtolower( $value );
	}

	/**
	 * @inheritDoc
	 */
	public static function get_subitem_format(): array {
		return [
			'type'   => 'string',
			'format' => 'hex-color',
		];
	}

	/**
	 * @inheritDoc
	 */
	public function get_example(): string {
		return $this->example ?? '#000000';
	}
}
