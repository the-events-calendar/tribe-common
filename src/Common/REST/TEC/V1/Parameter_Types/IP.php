<?php
/**
 * IP parameter type.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Parameter_Types
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Parameter_Types;

use Closure;

/**
 * IP parameter type.
 *
 * @since TBD
 */
class IP extends Text {

	/**
	 * @inheritDoc
	 */
	public function get_format(): ?string {
		return 'ip';
	}

	/**
	 * @inheritDoc
	 */
	public function get_validator(): ?Closure {
		return $this->validator ?? fn( $value ): bool => (bool) filter_var( $value, FILTER_VALIDATE_IP );
	}

	/**
	 * @inheritDoc
	 */
	public static function get_subitem_format(): array {
		return [
			'type'   => 'string',
			'format' => 'ip',
		];
	}

	/**
	 * @inheritDoc
	 */
	public function get_example(): string {
		return $this->example ?? '127.0.0.1';
	}
}
