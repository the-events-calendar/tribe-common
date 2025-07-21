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
