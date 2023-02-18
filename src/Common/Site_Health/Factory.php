<?php

namespace TEC\Common\Site_Health;

use Tribe__Main;

/**
 * Class Factory
 *
 * @since TBD
 *
 * @package TEC\Common\Site_Health
 */
class Factory {
	public function generate_generic_field( string $id, string $label, ?string $value, int $priority = 50 ): ?Info_Field_Abstract {
		return Generic_Info_Field::from_args( $id, $label, $value, $priority );
	}

	/**
	 *
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

		return array_filter( $sections, static function( $section ) {
			return $section instanceof Info_Section_Abstract;
		} );
	}

	protected function get_insert_after_section_key() {
		return 'wp-media';
	}

	public function filter_include_info_sections( array $info = [] ) {
		$sections = array_map( static function( Info_Section_Abstract $section ) {
			return $section->to_array();
		}, $this->get_sections() );

		$info = Tribe__Main::array_insert_after_key( $this->get_insert_after_section_key(), $info, $sections );

		return $info;
	}
}