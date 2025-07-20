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
	public static function get_subitem_format(): array {
		return [
			'type'   => 'string',
			'format' => 'ip',
		];
	}
}
