<?php
/**
 * File: Generic_Info_Field.php
 *
 * @since 5.1.0
 *
 * @package TEC\Common\Site_Health
 */

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
	 * @param string                           $id       The id of the field.
	 * @param string                           $label    The label of the field.
	 * @param array<string,string>|string|null $value    The value of the field.
	 * @param int                              $priority The priority of the field.
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
	 * @param array $field The field to create an instance from.
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
	 * @param string      $id       The id of the field.
	 * @param string      $label    The label of the field.
	 * @param string|null $value    The value of the field.
	 * @param int         $priority The priority of the field.
	 *
	 * @return Info_Field_Abstract
	 */
	public static function from_args( string $id, string $label, ?string $value, int $priority = 50 ): Info_Field_Abstract {
		return new static( $id, $label, $value, $priority );
	}
}
