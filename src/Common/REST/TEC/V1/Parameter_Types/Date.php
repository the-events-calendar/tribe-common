<?php
/**
 * Date parameter type.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Parameter_Types
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Parameter_Types;

/**
 * Date parameter type.
 *
 * @since TBD
 */
class Date extends Text {

	/**
	 * @inheritDoc
	 */
	public function get_format(): ?string {
		return 'date';
	}

	/**
	 * @inheritDoc
	 */
	public static function get_subitem_format(): array {
		return [
			'type'   => 'string',
			'format' => 'date',
		];
	}

	/**
	 * @inheritDoc
	 */
	public function get_example(): string {
		return $this->example ?? '2025-06-05';
	}
}
