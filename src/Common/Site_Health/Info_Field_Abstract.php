<?php

namespace TEC\Common\Site_Health;

/**
 * Class Info_Field_Abstract
 *
 * @since   TBD
 *
 * @package TEC\Common\Site_Health
 */
abstract class Info_Field_Abstract implements Info_Field_Interface {
	public $id;
	public $label;
	public $value;
	public $priority;

	public function get_id(): string {
		return $this->id;
	}

	public function get_label(): string {
		return $this->label;
	}

	public function get_value(): string {
		return $this->value;
	}

	public function get_priority(): int {
		return $this->priority;
	}

	public function to_array(): array {
		return [
			'id' => $this->get_id(),
			'label' => $this->get_label(),
			'value' => $this->get_value(),
			'priority' => $this->get_priority(),
		];
	}

}