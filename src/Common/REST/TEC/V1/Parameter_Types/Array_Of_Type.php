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
		return $this->validator ?? fn( $value ): bool => ( is_string( $value ) && strstr( $value, ',' ) ) || is_array( $value );
	}

	/**
	 * @inheritDoc
	 */
	public function get_sanitizer(): Closure {
		return $this->sanitizer ?? fn( $value ): array => ( is_string( $value ) && strstr( $value, ',' ) ) ? explode( ',', $value ) : (array) $value;
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
