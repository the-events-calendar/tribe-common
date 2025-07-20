<?php
/**
 * Positive integer parameter type.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Parameter_Types
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Parameter_Types;

use Closure;

/**
 * Positive integer parameter type.
 *
 * @since TBD
 */
class Positive_Integer extends Integer {

	/**
	 * @inheritDoc
	 */
	public function get_validator(): Closure {
		return $this->validator ?? fn( $value ): bool => is_int( $value ) && $value > 0;
	}

	/**
	 * @inheritDoc
	 */
	public function get_sanitizer(): Closure {
		return $this->sanitizer ?? fn( $value ): int => absint( $value );
	}
}
