<?php
/**
 * Date_Time parameter type.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Parameter_Types
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Parameter_Types;

use Closure;

/**
 * Date_Time parameter type.
 *
 * @since TBD
 */
class Date_Time extends Text {

	/**
	 * @inheritDoc
	 */
	public function get_format(): ?string {
		return 'date-time';
	}

	/**
	 * @inheritDoc
	 */
	public function get_validator(): ?Closure {
		return $this->validator ?? fn( $value ): bool => is_numeric( $value ) || ( is_string( $value ) && strtotime( $value ) );
	}

	/**
	 * @inheritDoc
	 */
	public static function get_subitem_format(): array {
		return [
			'type'   => 'string',
			'format' => 'date-time',
		];
	}

	/**
	 * @inheritDoc
	 */
	public function get_example(): string {
		return '2021-01-01T00:00:00Z';
	}
}
