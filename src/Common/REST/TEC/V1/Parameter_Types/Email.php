<?php
/**
 * Email parameter type.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Parameter_Types
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Parameter_Types;

use Closure;

/**
 * Email parameter type.
 *
 * @since TBD
 */
class Email extends Text {

	/**
	 * @inheritDoc
	 */
	public function get_format(): ?string {
		return 'email';
	}

	/**
	 * @inheritDoc
	 */
	public function get_validator(): ?Closure {
		return $this->validator ?? fn( $value ): bool => is_email( $value );
	}

	/**
	 * @inheritDoc
	 */
	public static function get_subitem_format(): array {
		return [
			'type'   => 'string',
			'format' => 'email',
		];
	}

	/**
	 * @inheritDoc
	 */
	public function get_example(): string {
		return $this->example ?? 'example@theeventscalendar.com';
	}
}
