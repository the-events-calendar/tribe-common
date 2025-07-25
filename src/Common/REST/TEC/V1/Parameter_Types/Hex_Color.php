<?php
/**
 * Hex_Color parameter type.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Parameter_Types
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Parameter_Types;

/**
 * Hex_Color parameter type.
 *
 * @since TBD
 */
class Hex_Color extends Text {

	/**
	 * @inheritDoc
	 */
	public function get_format(): ?string {
		return 'hex-color';
	}

	/**
	 * @inheritDoc
	 */
	public static function get_subitem_format(): array {
		return [
			'type'   => 'string',
			'format' => 'hex-color',
		];
	}

	/**
	 * @inheritDoc
	 */
	public function get_example(): string {
		return $this->example ?? '#000000';
	}
}
