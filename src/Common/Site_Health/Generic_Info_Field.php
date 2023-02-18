<?php

namespace TEC\Common\Site_Health;

/**
 * Class Generic_Info_Field
 *
 * @since   TBD
 *
 * @package TEC\Common\Site_Health
 */
class Generic_Info_Field extends Info_Field_Abstract {

	public function __construct( string $id, string $label, ?string $value, int $priority = 50 ) {
		$this->id       = $id;
		$this->label    = $label;
		$this->value    = $value;
		$this->priority = $priority;
	}

	public static function from_array( array $field ): Info_Field_Abstract {
		return new static( $field['id'], $field['label'], $field['value'], $field['priority'] );
	}

	public static function from_args( string $id, string $label, ?string $value, int $priority = 50 ): Info_Field_Abstract {
		return new static( $id, $label, $value, $priority );
	}
}