<?php
/**
 * UUID parameter type.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Parameter_Types
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Parameter_Types;

/**
 * UUID parameter type.
 *
 * @since TBD
 */
class UUID extends Text {

	/**
	 * @inheritDoc
	 */
	public function get_format(): ?string {
		return 'uuid';
	}

	/**
	 * @inheritDoc
	 */
	public static function get_subitem_format(): array {
		return [
			'type'   => 'string',
			'format' => 'uuid',
		];
	}
}
