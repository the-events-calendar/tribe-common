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
	public static function get_subitem_format(): array {
		return [
			'type'   => 'string',
			'format' => 'email',
		];
	}
}
