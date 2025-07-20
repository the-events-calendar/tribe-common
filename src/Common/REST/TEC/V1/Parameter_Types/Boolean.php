<?php
/**
 * Boolean parameter type.
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
 * Boolean parameter type.
 *
 * @since TBD
 */
class Boolean extends Parameter {

	/**
	 * @inheritDoc
	 */
	public function get_type(): string {
		return 'boolean';
	}

	/**
	 * @inheritDoc
	 */
	public function get_validator(): Closure {
		// Anything is accepted and will be converted to a boolean.
		return $this->validator ?? fn(): bool => true;
	}

	/**
	 * @inheritDoc
	 */
	public function get_sanitizer(): Closure {
		return $this->sanitizer ?? fn( $value ): bool => (bool) $value;
	}

	/**
	 * @inheritDoc
	 */
	public function get_default(): ?bool {
		return $this->default;
	}
}
