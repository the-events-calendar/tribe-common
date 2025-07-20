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
	public static function get_subitem_format(): array {
		return [
			'type'   => 'string',
			'format' => 'date-time',
		];
	}
}
