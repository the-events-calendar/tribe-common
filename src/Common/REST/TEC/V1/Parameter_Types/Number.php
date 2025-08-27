<?php
/**
 * Number parameter type.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Parameter_Types
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Parameter_Types;

use TEC\Common\REST\TEC\V1\Abstracts\Parameter;
use Closure;
use TEC\Common\REST\TEC\V1\Exceptions\InvalidRestArgumentException;

/**
 * Number parameter type.
 *
 * @since 6.9.0
 */
class Number extends Parameter {

	/**
	 * Constructor.
	 *
	 * @since 6.9.0
	 *
	 * @param string         $name                 The name of the parameter.
	 * @param ?Closure       $description_provider The description provider.
	 * @param mixed          $by_default           The default value.
	 * @param ?int           $minimum              The minimum value.
	 * @param ?int           $maximum              The maximum value.
	 * @param bool           $required             Whether the parameter is required.
	 * @param ?Closure       $validator            The validator.
	 * @param ?Closure       $sanitizer            The sanitizer.
	 * @param int|float|null $multiple_of          The multiple of.
	 * @param string         $location             The parameter location.
	 * @param bool           $deprecated           Whether the parameter is deprecated.
	 * @param ?bool          $nullable             Whether the parameter is nullable.
	 * @param ?bool          $read_only            Whether the parameter is read only.
	 * @param ?bool          $write_only           Whether the parameter is write only.
	 */
	public function __construct(
		string $name = 'example',
		?Closure $description_provider = null,
		$by_default = null,
		?int $minimum = null,
		?int $maximum = null,
		bool $required = false,
		?Closure $validator = null,
		?Closure $sanitizer = null,
		$multiple_of = null,
		string $location = self::LOCATION_QUERY,
		?bool $deprecated = null,
		?bool $nullable = null,
		?bool $read_only = null,
		?bool $write_only = null
	) {
		$this->name                 = $name;
		$this->description_provider = $description_provider;
		$this->required             = $required;
		$this->default              = $by_default;
		$this->minimum              = $minimum;
		$this->maximum              = $maximum;
		$this->validator            = $validator;
		$this->sanitizer            = $sanitizer;
		$this->multiple_of          = $multiple_of;
		$this->location             = $location;
		$this->deprecated           = $deprecated;
		$this->nullable             = $nullable;
		$this->read_only            = $read_only;
		$this->write_only           = $write_only;
	}

	/**
	 * @inheritDoc
	 */
	public function get_type(): string {
		return 'number';
	}

	/**
	 * @inheritDoc
	 */
	public function get_validator(): Closure {
		return $this->validator ?? function ( $value ): bool {
			if ( ! is_float( $value ) && ! is_int( $value ) ) {
				throw InvalidRestArgumentException::create(
					// translators: 1) is the name of the parameter.
					sprintf( __( 'Argument `{%1$s}` must be a number.', 'tribe-common' ), $this->get_name() ),
					$this->get_name(),
					'tec_rest_invalid_number_argument',
					// translators: 1) is the name of the parameter.
					sprintf( __( 'The argument `{%1$s}` is not a number.', 'tribe-common' ), $this->get_name() )
				);
			}

			return true;
		};
	}

	/**
	 * @inheritDoc
	 */
	public function get_sanitizer(): Closure {
		return $this->sanitizer ?? fn( $value ): float => floatval( $value );
	}

	/**
	 * @inheritDoc
	 */
	public function get_default(): ?float {
		return $this->default;
	}

	/**
	 * @inheritDoc
	 */
	public static function get_subitem_format(): array {
		return [
			'type' => 'number',
		];
	}

	/**
	 * @inheritDoc
	 */
	public function get_example() {
		if ( $this->example ) {
			return $this->example;
		}

		if ( $this->get_minimum() || $this->get_maximum() ) {
			return ceil( ( ( $this->get_minimum() ?? 1 ) + ( $this->get_maximum() ?? 1 ) ) / 2 ) + 0.25;
		}

		return 126.75;
	}
}
