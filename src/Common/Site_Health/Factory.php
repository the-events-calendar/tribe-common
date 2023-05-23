<?php

namespace TEC\Common\Site_Health;

use Tribe__Main;

/**
 * Class Factory
 *
 * @link https://developer.wordpress.org/reference/hooks/debug_information/
 *
 * @since   TBD
 *
 * @package TEC\Common\Site_Health
 */
class Factory {
	/**
	 * Generates a Generic field from a set of arguments.
	 *
	 * @since TBD
	 *
	 * @param string      $id
	 * @param string      $label
	 * @param string|null $value
	 * @param int         $priority (optional) By default all fields are generated with priority 50.
	 *
	 * @return Info_Field_Abstract
	 */
	public function generate_generic_field( string $id, string $label, ?string $value, int $priority = 50 ): Info_Field_Abstract {
		return Fields\Generic_Info_Field::from_args( $id, $label, $value, $priority );
	}

	/**
	 * Gets all registered sections.
	 *
	 * @since TBD
	 *
	 * @return array<string,Info_Section_Abstract>
	 */
	public function get_sections(): array {
		/**
		 * Allows filtering of the Common Info Sections.
		 *
		 * @since TBD
		 *
		 * @param array<string,mixed> $sections Which sections exist.
		 */
		$sections = (array) apply_filters( 'tec_debug_info_sections', [] );

		return array_filter( $sections, static function ( $section ) {
			return $section instanceof Info_Section_Abstract;
		} );
	}

	/**
	 * Gets the section after which we will insert all the factory-generated sections.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	protected function get_insert_after_section_key(): string {
		return 'wp-media';
	}

	/**
	 * Filters the actual site health data to include our sections.
	 *
	 * @since TBD
	 *
	 * @param array $info
	 *
	 * @return array
	 */
	public function filter_include_info_sections( array $info = [] ) {
		$sections = [];
		foreach ( $this->get_sections() as $key => $section ) {
			$sections[ $key ] = $section->to_array();
		}

		$info = Tribe__Main::array_insert_after_key( $this->get_insert_after_section_key(), $info, $sections );

		return $info;
	}
}
