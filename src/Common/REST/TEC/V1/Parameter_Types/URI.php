<?php
/**
 * URI parameter type.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Parameter_Types
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Parameter_Types;

use Closure;

/**
 * URI parameter type.
 *
 * @since TBD
 */
class URI extends Text {

	/**
	 * @inheritDoc
	 */
	public function get_format(): ?string {
		return 'uri';
	}

	/**
	 * @inheritDoc
	 */
	public function get_validator(): ?Closure {
		return $this->validator ?? fn( $value ): bool => filter_var( $value, FILTER_VALIDATE_URL );
	}

	/**
	 * @inheritDoc
	 */
	public static function get_subitem_format(): array {
		return [
			'type'   => 'string',
			'format' => 'uri',
		];
	}

	/**
	 * @inheritDoc
	 */
	public function get_example(): string {
		return $this->example ?? 'https://example.com';
	}
}
