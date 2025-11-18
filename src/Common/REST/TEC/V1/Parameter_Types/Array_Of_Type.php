<?php
/**
 * Array parameter type.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Parameter_Types
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Parameter_Types;

use TEC\Common\REST\TEC\V1\Abstracts\Parameter;
use TEC\Common\REST\TEC\V1\Contracts\Parameter as Parameter_Contract;
use TEC\Common\REST\TEC\V1\Contracts\Definition_Interface as Definition;
use Closure;

/**
 * Array parameter type.
 *
 * @since 6.9.0
 */
class Array_Of_Type extends Parameter {

	/**
	 * Constructor.
	 *
	 * @since 6.9.0
	 *
	 * @param string         $name                 The name of the parameter.
	 * @param ?Closure       $description_provider The description provider.
	 * @param ?string        $items_type           The items type.
	 * @param ?array         $available_enum       The enum values.
	 * @param mixed          $by_default           The default value.
	 * @param ?Closure       $validator            The validator.
	 * @param bool           $required             Whether the parameter is required.
	 * @param ?string        $format               The format.
	 * @param ?string        $pattern              The pattern.
	 * @param ?bool          $explode              Whether to explode the parameter.
	 * @param int|float|null $multiple_of          The multiple of.
	 * @param ?int           $min_items            The minimum items.
	 * @param ?int           $max_items            The maximum items.
	 * @param string         $location             The parameter location.
	 * @param ?bool          $nullable             Whether the parameter is nullable.
	 * @param ?bool          $read_only            Whether the parameter is read only.
	 * @param ?bool          $write_only           Whether the parameter is write only.
	 * @param bool           $deprecated           Whether the parameter is deprecated.
	 * @param ?Closure       $sanitizer            The sanitizer.
	 */
	public function __construct(
		string $name = 'example',
		?Closure $description_provider = null,
		?string $items_type = null,
		?array $available_enum = null,
		$by_default = null,
		?Closure $validator = null,
		bool $required = false,
		?string $format = null,
		?string $pattern = null,
		?bool $explode = null,
		$multiple_of = null,
		?int $min_items = null,
		?int $max_items = null,
		string $location = self::LOCATION_QUERY,
		?bool $nullable = null,
		?bool $read_only = null,
		?bool $write_only = null,
		?bool $deprecated = null,
		?Closure $sanitizer = null
	) {
		$this->name                 = $name;
		$this->description_provider = $description_provider;
		$this->required             = $required;
		$this->enum                 = $available_enum;
		$this->default              = $by_default;
		$this->validator            = $validator;
		$this->sanitizer            = $sanitizer;
		$this->items_type           = $items_type;
		$this->format               = $format;
		$this->pattern              = $pattern;
		$this->explode              = $explode;
		$this->multiple_of          = $multiple_of;
		$this->min_items            = $min_items;
		$this->max_items            = $max_items;
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
		return 'array';
	}

	/**
	 * @inheritDoc
	 */
	public function get_validator(): Closure {
		return $this->validator ?? function ( $value ): bool {
			$its_ok = ( is_string( $value ) && strstr( $value, ',' ) ) || is_array( $value );

			if ( ! $its_ok ) {
				return false;
			}

			if ( is_string( $value ) ) {
				$value = explode( ',', $value );
			}

			if ( empty( $value ) ) {
				return true;
			}

			if ( ! class_exists( $this->items_type ) ) {
				return true;
			}

			$class = $this->get_class_of_subtype();

			if ( ! $class instanceof Parameter_Contract ) {
				return true;
			}

			$item_type_validator = $class->get_validator();

			if ( null === $item_type_validator ) {
				return true;
			}

			return array_filter( $value, $item_type_validator ) === $value;
		};
	}

	/**
	 * @inheritDoc
	 */
	public function get_sanitizer(): Closure {
		return $this->sanitizer ?? function ( $value ): array {
			$value = ( is_string( $value ) && strstr( $value, ',' ) ) ? explode( ',', $value ) : (array) $value;

			if ( ! class_exists( $this->items_type ) ) {
				return $value;
			}

			$class = $this->get_class_of_subtype();

			if ( ! $class instanceof Parameter_Contract ) {
				return $value;
			}

			$item_type_sanitizer = $class->get_sanitizer();

			if ( null === $item_type_sanitizer ) {
				return $value;
			}

			return array_map( $item_type_sanitizer, $value );
		};
	}

	/**
	 * @inheritDoc
	 */
	public function get_default(): ?array {
		return $this->default;
	}

	/**
	 * @inheritDoc
	 */
	public function get_example(): array {
		if ( $this->example ) {
			return $this->example;
		}

		if ( ! class_exists( $this->items_type ) ) {
			return [ 'string1', 'string2' ];
		}

		$class = $this->get_class_of_subtype();

		if ( ! $class instanceof Parameter_Contract ) {
			return [ 'string1', 'string2' ];
		}

		return [ $class->get_example() ];
	}

	/**
	 * @inheritDoc
	 */
	public function get_items(): array {
		$enums = $this->get_enum();

		$return = [];

		if ( class_exists( $this->items_type ) ) {
			if ( is_callable( [ $this->items_type, 'get_subitem_format' ] ) ) {
				$return = $this->items_type::get_subitem_format();
			}

			$item = $this->get_an_item();

			if ( $item instanceof Definition ) {

				$return = [
					'$ref' => '#/components/schemas/' . $item->get_type(),
				];
			}
		}

		if ( ! is_array( $return ) || empty( $return ) ) {
			$return = [
				'type' => $this->items_type,
			];
		}

		if ( is_array( $return ) ) {
			$return = array_merge(
				$return,
				array_filter(
					[
						'pattern' => $this->get_pattern(),
					],
					static fn( $value ) => null !== $value,
				)
			);

			$this->pattern = null;
		}

		if ( $enums ) {
			$return['enum'] = $enums;
		}

		return $return;
	}

	/**
	 * Returns the class of the subtype.
	 *
	 * @since 6.9.0
	 *
	 * @return ?Parameter_Contract
	 */
	public function get_class_of_subtype(): ?Parameter_Contract {
		$class_string = $this->items_type;

		switch ( $class_string ) {
			case Positive_Integer::class:
			case Integer::class:
			case Number::class:
				$class = new $class_string(
					'example',
					null,
					null,
					$this->get_minimum(),
					$this->get_maximum(),
					false,
					null,
					null,
					$this->get_multiple_of(),
				);

				break;
			case Text::class:
			case Date_Time::class:
			case Email::class:
			case URI::class:
			case UUID::class:
			case Hex_Color::class:
			case IP::class:
				$class = new $class_string(
					'example',
					null,
					null,
					$this->get_enum(),
					$this->get_min_length(),
					$this->get_max_length(),
					false,
					null,
					null,
					$this->get_format(),
					$this->get_pattern(),
				);

				break;
			case Boolean::class:
				$class = new Boolean();

				break;
			case Entity::class:
				$class = new Entity(
					'example',
					null
				);

				break;
			default:
				$class = null;
				break;
		}

		return $class;
	}
}
