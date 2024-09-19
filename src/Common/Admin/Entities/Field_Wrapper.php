<?php
/**
 * FieldW_Wrapper element.
 *
 * Wraps a Tribe__Field object.
 *
 * @since TBD
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

use Tribe__Field as Field;

/**
 * Class FieldW_Wrapper
 *
 * @since TBD
 */
class Field_Wrapper implements Element {

	/**
	 * The field to wrap.
	 *
	 * @var Field
	 */
	private Field $field;

	/**
	 * FieldW_Wrapper constructor.
	 *
	 * @param Field $field The field to wrap.
	 */
	public function __construct( Field $field ) {
		$this->field = $field;
	}

	/**
	 * Render the field.
	 *
	 * @since TBD
	 */
	public function render(): void {
		$this->field->do_field();
	}

	/**
	 * Get the field.
	 *
	 * @return Field
	 */
	public function get_field(): Field {
		return $this->field;
	}
}
