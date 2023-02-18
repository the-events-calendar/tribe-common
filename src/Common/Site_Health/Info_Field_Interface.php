<?php

namespace TEC\Common\Site_Health;

interface Info_Field_Interface {

	public function get_id(): string;

	public function get_label(): string;

	public function get_value(): string;

	public function get_priority(): int;

	public function to_array(): array;

}