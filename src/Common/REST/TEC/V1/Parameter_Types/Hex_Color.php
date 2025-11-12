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
				throw InvalidRestArgumentException::create(
					// translators: 1) is the name of the parameter.
					sprintf( __( 'Argument `{%1$s}` must be a string.', 'tribe-common' ), $this->get_name() ),
					$this->get_name(),
					'tec_rest_invalid_hex_color_argument',
					// translators: 1) is the name of the parameter.
					sprintf( __( 'The argument `{%1$s}` is not a string.', 'tribe-common' ), $this->get_name() )
				);
			}

			if ( ! ( preg_match( '/^#([0-9a-fA-F]{3})$/', (string) $value ) || preg_match( '/^#([0-9a-fA-F]{6})$/', (string) $value ) ) ) {
				throw InvalidRestArgumentException::create(
					// translators: 1) is the name of the parameter.
					sprintf( __( 'Argument `{%1$s}` must be a valid hex color.', 'tribe-common' ), $this->get_name() ),
					$this->get_name(),
					'tec_rest_invalid_hex_color_argument',
					// translators: 1) is the name of the parameter.
					sprintf( __( 'The argument `{%1$s}` is not a valid hex color. It should be a string like `#000000` or `#000`.', 'tribe-common' ), $this->get_name() )
				);
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
