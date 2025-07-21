<?php
/**
 * Array parameter type.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Parameter_Types
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Parameter_Types;

use TEC\Common\REST\TEC\V1\Abstracts\Parameter;
use TEC\Common\REST\TEC\V1\Contracts\Parameter as Parameter_Contract;
use Closure;

/**
 * Array parameter type.
 *
 * @since TBD
 */
class Array_Of_Type extends Parameter {

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

			$class_string = $this->items_type;

			$class = new $class_string(
				'example',
				null,
				false,
				null,
				null,
				null,
				$this->get_enum(),
				$this->get_maximum(),
				$this->get_minimum(),
				$this->get_min_length(),
				$this->get_max_length(),
				null,
				null,
				$this->get_format(),
				$this->get_pattern(),
				null,
				$this->get_multiple_of(),
				null,
				null,
				self::LOCATION_QUERY,
				null,
				$this->is_nullable(),
			);

			if ( ! $class instanceof Parameter_Contract ) {
				return true;
			}

			$item_type_validator = $class->get_validator();

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

			$class_string = $this->items_type;

			$class = new $class_string(
				'example',
				null,
				false,
				null,
				null,
				null,
				$this->get_enum(),
				$this->get_maximum(),
				$this->get_minimum(),
				$this->get_min_length(),
				$this->get_max_length(),
				null,
				null,
				$this->get_format(),
				$this->get_pattern(),
				null,
				$this->get_multiple_of(),
				null,
				null,
				self::LOCATION_QUERY,
				null,
				$this->is_nullable(),
			);

			if ( ! $class instanceof Parameter_Contract ) {
				return $value;
			}

			$item_type_sanitizer = $class->get_sanitizer();

			return array_filter( $value, $item_type_sanitizer );
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
		if ( ! class_exists( $this->items_type ) ) {
			return [ 'string1', 'string2' ];
		}

		$class_string = $this->items_type;

		$class = new $class_string(
			'example',
			null,
			false,
			null,
			null,
			null,
			$this->get_enum(),
			$this->get_maximum(),
			$this->get_minimum(),
			$this->get_min_length(),
			$this->get_max_length(),
			null,
			null,
			$this->get_format(),
			$this->get_pattern(),
			null,
			$this->get_multiple_of(),
			null,
			null,
			self::LOCATION_QUERY,
			null,
			$this->is_nullable(),
		);

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

		if ( class_exists( $this->items_type ) && is_callable( [ $this->items_type, 'get_subitem_format' ] ) ) {
			$return = $this->items_type::get_subitem_format();
		}

		if ( ! is_array( $return ) || empty( $return ) ) {
			$return = [
				'type' => $this->items_type,
			];
		}

		if ( $enums ) {
			$return['enum'] = $enums;
		}

		return $return;
	}
}
