<?php

namespace TEC\Common\Site_Health;

/**
 * Class Factory
 *
 * @since TBD
 *
 * @package TEC\Common\Site_Health
 */
class Factory {
	public function generate_generic_field( string $id, string $label, ?string $value, int $priority = 50 ): ?Info_Field_Abstract {
		return Generic_Info_Field::from_args( $id, $label, $value, $priority );
	}
}