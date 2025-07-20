<?php
/**
 * Integer parameter type.
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
 * Integer parameter type.
 *
 * @since TBD
 */
class Integer extends Parameter {

	/**
	 * @inheritDoc
	 */
	public function get_type(): string {
		return 'integer';
	}

	/**
	 * @inheritDoc
	 */
	public function get_validator(): Closure {
		return $this->validator ?? fn( $value ): bool => is_int( $value );
	}

	/**
	 * @inheritDoc
	 */
	public function get_sanitizer(): Closure {
		return $this->sanitizer ?? fn( $value ): int => intval( $value );
	}

	/**
	 * @inheritDoc
	 */
	public function get_default(): ?int {
		return $this->default;
	}

	/**
	 * @inheritDoc
	 */
	public static function get_subitem_format(): array {
		return [
			'type' => 'integer',
		];
	}
}
