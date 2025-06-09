<?php

namespace TEC\Common\Site_Health;

use Tribe__Main;

/**
 * Class Factory
 *
 * @link https://developer.wordpress.org/reference/hooks/debug_information/
 *
 * @since 5.1.0
 *
 * @package TEC\Common\Site_Health
 */
class Factory {
	/**
	 * Generates a Generic field from a set of arguments.
	 *
	 * @since 5.1.0
	 *
	 * @param string            $id
	 * @param string            $label
	 * @param array|string|null $value
	 * @param int               $priority (optional) By default all fields are generated with priority 50.
	 *
	 * @return Info_Field_Abstract
	 */
	public static function generate_generic_field( string $id, string $label, $value, int $priority = 50 ): Info_Field_Abstract {
		return new Fields\Generic_Info_Field( $id, $label, $value, $priority );
	}

	/**
	 * Generates a Post type count field from a set of arguments.
	 *
	 * @since 5.1.0
	 *
	 * @param string $id
	 * @param string $post_type
	 * @param int    $priority (optional) By default all fields are generated with priority 50.
	 *
	 * @return Info_Field_Abstract
	 */
	public static function generate_post_status_count_field( string $id, string $post_type, int $priority = 50 ): Info_Field_Abstract {
		return new Fields\Post_Status_Count_Field( $id, $post_type, $priority );
	}

	/**
	 * Gets all registered sections.
	 *
	 * @since 5.1.0
	 *
	 * @return array<string,Info_Section_Abstract>
	 */
	public function get_sections(): array {
		/**
		 * Allows filtering of the Common Info Sections.
		 *
		 * @since 5.1.0
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
	 * @since 5.1.0
	 *
	 * @return string
	 */
	protected function get_insert_after_section_key(): string {
		return 'wp-media';
	}

	/**
	 * Filters the actual site health data to include our sections.
	 *
	 * @since 5.1.0
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
