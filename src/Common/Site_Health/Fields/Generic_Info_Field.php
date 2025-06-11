<?php

namespace TEC\Common\Site_Health\Fields;

use TEC\Common\Site_Health\Info_Field_Abstract;

/**
 * Class Generic_Info_Field
 *
 * @since 5.1.0
 *
 * @package TEC\Common\Site_Health
 */
class Generic_Info_Field extends Info_Field_Abstract {

	/**
	 * Configure all the params for a generic field.
	 *
	 * @param string                           $id
	 * @param string                           $label
	 * @param array<string,string>|string|null $value
	 * @param int                              $priority
	 */
	public function __construct( string $id, string $label, $value = null, int $priority = 50 ) {
		$this->id         = $id;
		$this->label      = $label;
		$this->value      = $value;
		$this->priority   = $priority;
		$this->is_private = true;
		$this->debug      = false;
	}

	/**
	 * Given an array of configurations sets up a new generic field instance.
	 *
	 * @since 5.1.0
	 *
	 * @param array $field
	 *
	 * @return Info_Field_Abstract
	 */
	public static function from_array( array $field ): Info_Field_Abstract {
		return new static( $field['id'], $field['label'], $field['value'], $field['priority'] );
	}

	/**
	 * Given all the arguments create a new Generic Field.
	 *
	 * @since 5.1.0
	 *
	 * @param string      $id
	 * @param string      $label
	 * @param string|null $value
	 * @param int         $priority
	 *
	 * @return Info_Field_Abstract
	 */
	public static function from_args( string $id, string $label, ?string $value, int $priority = 50 ): Info_Field_Abstract {
		return new static( $id, $label, $value, $priority );
	}
}
