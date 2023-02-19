<?php

namespace TEC\Common\Site_Health;

/**
 * Class Info_Field_Abstract
 *
 * @link    https://developer.wordpress.org/reference/hooks/debug_information/
 *
 * @since   TBD
 *
 * @package TEC\Common\Site_Health
 */
abstract class Info_Field_Abstract implements Info_Field_Interface {
	/**
	 * Stores the ID for the field.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected string $id;

	/**
	 * Stores the label for the field.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected string $label;

	/**
	 * Stores the value for field.
	 *
	 * @since TBD
	 *
	 * @return string|int|float|array<int>|array<float>|array<string>
	 */
	protected $value;

	/**
	 * Stores the priority for the field, used for sorting.
	 *
	 * @since TBD
	 *
	 * @var int
	 */
	protected int $priority;

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
	public function to_array(): array {
		return [
			'id'       => $this->get_id(),
			'label'    => $this->get_label(),
			'value'    => $this->get_value(),
			'priority' => $this->get_priority(),
			'private'  => $this->is_private(),
			'debug'    => $this->get_debug(),
		];
	}

}