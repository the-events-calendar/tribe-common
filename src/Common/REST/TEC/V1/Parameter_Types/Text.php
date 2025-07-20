<?php
/**
 * String parameter type.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Parameter_Types
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Parameter_Types;

use TEC\Common\REST\TEC\V1\Abstracts\Parameter;

/**
 * String parameter type.
 *
 * @since TBD
 */
class Text extends Parameter {

	/**
	 * @inheritDoc
	 */
	public function get_type(): string {
		return 'string';
	}

	/**
	 * @inheritDoc
	 */
	public function get_default(): ?string {
		return $this->default;
	}

	/**
	 * @inheritDoc
	 */
	public static function get_subitem_format(): array {
		return [
			'type' => 'string',
		];
	}
}
