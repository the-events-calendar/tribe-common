<?php
/**
 * Entity parameter type.
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
 * Entity parameter type.
 *
 * @since TBD
 */
class Entity extends Parameter {

	/**
	 * @inheritDoc
	 */
	public function get_type(): string {
		return 'object';
	}

	/**
	 * @inheritDoc
	 */
	public function get_validator(): Closure {
		return $this->validator ?? fn() => true;
	}

	/**
	 * @inheritDoc
	 */
	public function get_sanitizer(): Closure {
		return $this->sanitizer ?? fn( $entity ) => $entity;
	}

	/**
	 * @inheritDoc
	 */
	public function get_default() {
		return $this->default;
	}
}
