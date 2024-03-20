<?php

namespace TEC\Common\Site_Health;

/**
 * Class Info_Field_Abstract
 *
 * @link    https://developer.wordpress.org/reference/hooks/debug_information/
 *
 * @since   5.1.0
 *
 * @package TEC\Common\Site_Health
 */
abstract class Info_Field_Abstract implements Info_Field_Interface {
	/**
	 * Stores the ID for the field.
	 *
	 * @since 5.1.0
	 *
	 * @var string
	 */
	protected string $id;

	/**
	 * Stores the label for the field.
	 *
	 * @since 5.1.0
	 *
	 * @var string
	 */
	protected string $label;

	/**
	 * Stores the value for field.
	 *
	 * @since 5.1.0
	 *
	 * @return string|int|float|array<int>|array<float>|array<string>
	 */
	protected $value;

	/**
	 * Stores the priority for the field, used for sorting.
	 *
	 * @since 5.1.0
	 *
	 * @var int
	 */
	protected int $priority;

	/**
	 * Stores the flag for if the field is private.
	 *
	 * @since 5.1.0
	 *
	 * @var bool
	 */
	protected bool $is_private;

	/**
	 * Stores the debug value for the field.
	 *
	 * @since 5.1.0
	 *
	 * @var string
	 */
	protected string $debug;

	/**
	 * @inheritDoc
	 */
	public function get_id(): string {
		return $this->id;
	}

	/**
	 * @inheritDoc
	 */
	public function get_label(): string {
		return $this->label;
	}

	/**
	 * @inheritDoc
	 */
	public function get_value() {
		return $this->value;
	}

	/**
	 * @inheritDoc
	 */
	public function get_priority(): int {
		return $this->priority;
	}

	/**
	 * @inheritDoc
	 */
	public function is_private(): bool {
		return $this->is_private;
	}

	/**
	 * @inheritDoc
	 */
	public function get_debug(): string {
		return $this->debug;
	}

	/**
	 * @inheritDoc
	 */
	public function to_array(): array {
		return [
			'id'       => $this->filter_param( 'id', $this->get_id() ),
			'label'    => $this->filter_param( 'label', $this->get_label() ),
			'value'    => $this->filter_param( 'value', $this->get_value() ),
			'priority' => $this->filter_param( 'priority', $this->get_priority() ),
			'private'  => $this->filter_param( 'private', $this->is_private() ),
			'debug'    => $this->filter_param( 'debug', $this->get_debug() ),
		];
	}

	/**
	 * Internal method to the Field class, to allow filtering of specific values.
	 *
	 * @since 5.1.0
	 *
	 * @param string                $param   The field parameter we're filtering.
	 * @param mixed                 $value   Value of the field.
	 *
	 * @return mixed
	 */
	protected function filter_param( string $param, $value = null ) {
		$field_id     = $this->get_id();

		/**
		 * Filters the get of a particular param for all fields.
		 *
		 * @since 5.1.0
		 *
		 * @param mixed                 $value   Value of the field.
		 * @param Info_Field_Abstract   $field   Current Field.
		 */
		$value = apply_filters( "tec_debug_info_field_get_{$param}", $value, $this );

		/**
		 * Filters the get of a particular param for a specific section + field combination.
		 *
		 * @since 5.1.0
		 *
		 * @param mixed                 $value   Value of the field.
		 * @param Info_Field_Abstract   $field   Current Field.
		 */
		return apply_filters( "tec_debug_info_field_{$field_id}_get_{$param}", $value, $this );
	}

}
