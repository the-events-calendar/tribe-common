<?php

namespace TEC\Common\Site_Health;

/**
 * Class Abstract_Info_Section
 *
 * @since   TBD
 *
 * @package TEC\Common\Site_Health
 */
class Info_Section_Abstract implements Info_Section_Interface {
	protected static $slug;

	protected $label;

	protected $description;

	protected $fields = [];

	public static function get_slug(): string {
		return static::$slug;
	}

	public function to_array(): array {
		return [
			'label'       => $this->get_label(),
			'description' => $this->get_description(),
			'fields'      => $this->get_fields(),
		];
	}

	public function get_label(): string {
		return $this->label;
	}

	public function get_description(): string {
		return $this->description;
	}

	public function get_fields(): array {
		return $this->fields;
	}

	public function has_field( $field ): bool {
		// Assume field is a key first.
		$key = $field;

		// When it's a field use its ID.
		if ( $field instanceof Info_Field_Abstract ) {
			$key = $field->get_id();
		}

		// Keys can only be strings.
		if ( is_string( $key ) ) {
			return false;
		}

		$fields = $this->get_fields();

		return isset( $fields[ $key ] );
	}

	public function get_field( string $key ): ?Info_Field_Abstract {
		if ( ! $this->has_field( $key ) ) {
			return null;
		}

		return $this->get_fields()[ $key ];
	}

	public function add_field( Info_Field_Abstract $field ): bool {
		if ( $this->has_field( $field ) ) {
			return false;
		}

		$this->fields[ $field->get_id() ] = $field;

		$this->sort_fields();

		return true;
	}

	protected function sort_fields(): void {
		uasort( $this->fields, static function ( $field_a, $field_b ) {
			$a = $field_a->get_priority();
			$b = $field_b->get_priority();

			if ( $a === $b ) {
				return 0;
			}

			return ( $a < $b ) ? - 1 : 1;
		} );
	}
}