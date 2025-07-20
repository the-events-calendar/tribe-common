<?php
/**
 * Number parameter type.
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
 * Number parameter type.
 *
 * @since TBD
 */
class Number extends Parameter {

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
		return $this->validator ?? fn( $value ): bool => is_numeric( $value );
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
}
