<?php

namespace TEC\Common\Site_Health;

interface Info_Section_Interface {
	public static function get_slug(): string;

	public function to_array(): array;

	public function get_label(): string;

	public function get_description(): string;

	public function get_fields(): array;

	public function has_field( $key ): bool;

	public function get_field( string $key ): ?Info_Field_Abstract;

	public function add_field( Info_Field_Abstract $field ): bool;
}