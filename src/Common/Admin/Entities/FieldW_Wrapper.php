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

use Tribe__Field;

/**
 * Class FieldW_Wrapper
 *
 * @since TBD
 */
class FieldW_Wrapper implements Element {

	/**
	 * The field to wrap.
	 *
	 * @var Tribe__Field
	 */
	private Tribe__Field $field;

	/**
	 * FieldW_Wrapper constructor.
	 *
	 * @param Tribe__Field $field The field to wrap.
	 */
	public function __construct( Tribe__Field $field ) {
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
}
