<?php
/**
 * String parameter type.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Parameter_Types
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Parameter_Types;

use TEC\Common\REST\TEC\V1\Abstracts\Parameter;
use Closure;

/**
 * String parameter type.
 *
 * @since TBD
 */
class Text extends Parameter {

	/**
	 * Constructor.
	 *
	 * @since TBD
	 *
	 * @param string   $name                 The name of the parameter.
	 * @param ?Closure $description_provider The description provider.
	 * @param ?string  $by_default           The default value.
	 * @param ?array   $available_enum       The enum values.
	 * @param ?int     $min_length           The min length.
	 * @param ?int     $max_length           The max length.
	 * @param bool     $required             Whether the parameter is required.
	 * @param ?Closure $validator            The validator.
	 * @param ?Closure $sanitizer            The sanitizer.
	 * @param ?string  $format               The format.
	 * @param ?string  $pattern              The pattern.
	 * @param string   $location             The parameter location.
	 * @param bool     $deprecated           Whether the parameter is deprecated.
	 * @param ?bool    $nullable             Whether the parameter is nullable.
	 * @param ?bool    $read_only            Whether the parameter is read only.
	 * @param ?bool    $write_only           Whether the parameter is write only.
	 */
	public function __construct(
		string $name = 'example',
		?Closure $description_provider = null,
		?string $by_default = null,
		?array $available_enum = null,
		?int $min_length = null,
		?int $max_length = null,
		bool $required = false,
		?Closure $validator = null,
		?Closure $sanitizer = null,
		?string $format = null,
		?string $pattern = null,
		string $location = self::LOCATION_QUERY,
		?bool $deprecated = null,
		?bool $nullable = null,
		?bool $read_only = null,
		?bool $write_only = null
	) {
		$this->name                 = $name;
		$this->description_provider = $description_provider;
		$this->required             = $required;
		$this->enum                 = $available_enum;
		$this->default              = $by_default;
		$this->min_length           = $min_length;
		$this->max_length           = $max_length;
		$this->validator            = $validator;
		$this->sanitizer            = $sanitizer;
		$this->format               = $format;
		$this->pattern              = $pattern;
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
		return 'string';
	}

	/**
	 * @inheritDoc
	 */
	public function get_default(): ?string {
		return $this->default;
	}

	/**
	 * @inheritDoc
	 */
	public static function get_subitem_format(): array {
		return [
			'type' => 'string',
		];
	}

	/**
	 * @inheritDoc
	 */
	public function get_validator(): ?Closure {
		if ( null !== $this->validator ) {
			return $this->validator;
		}

		if ( null === $this->get_enum() ) {
			return $this->validator;
		}

		return fn( $value ): bool => in_array( $value, $this->get_enum(), true );
	}

	/**
	 * @inheritDoc
	 */
	public function get_example(): string {
		if ( $this->example ) {
			return $this->example;
		}

		if ( $this->get_enum() ) {
			return array_values( $this->get_enum() )[0];
		}

		if ( $this->get_min_length() || $this->get_max_length() ) {
			$min = $this->get_min_length() ?? 1;
			$max = $this->get_max_length() ?? 10;

			return substr( 'Example', $min, $max );
		}

		return 'Example';
	}
}
