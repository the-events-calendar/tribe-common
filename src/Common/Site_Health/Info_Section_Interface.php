<?php

namespace TEC\Common\Site_Health;

/**
 * Interface for Site Health Info Section.
 *
 * @link https://developer.wordpress.org/reference/hooks/debug_information/
 *
 * @since TBD
 *
 * @package TEC\Common\Site_Health
 */
interface Info_Section_Interface {
	/**
	 * Static way of fetching the slug of this section.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public static function get_slug(): string;

	/**
	 * Returns the section as an array ready for WordPress site health.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function to_array(): array;

	/**
	 * Returns the label for this section.
	 *
	 * @see Info_Section_Abstract::filter_param() For how to hook into this params.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_label(): string;

	/**
	 * Returns the description for this section.
	 *
	 * @see Info_Section_Abstract::filter_param() For how to hook into this params.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_description(): string;

	/**
	 * Whether it should show the count of fields for this section.
	 *
	 * @see Info_Section_Abstract::filter_param() For how to hook into this params.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public function get_show_count(): bool;

	/**
	 * If this particular section should be copied when using the Site Health.
	 *
 	 * @see Info_Section_Abstract::filter_param() For how to hook into this params.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public function is_private(): bool;

	/**
	 * Returns an array of all the fields in this section.
	 *
 	 * @see Info_Section_Abstract::filter_param() For how to hook into this params.
	 *
	 * @since TBD
	 *
	 * @return array<string, Info_Field_Abstract>
	 */
	public function get_fields(): array;

	/**
	 * Determines if a given field exists.
	 *
	 * @since TBD
	 *
	 * @param string|Info_Field_Abstract $field
	 *
	 * @return bool
	 */
	public function has_field( $field ): bool;

	/**
	 * Based on the id of the field return the object.
	 *
	 * @since TBD
	 *
	 * @param string $id
	 *
	 * @return Info_Field_Abstract|null
	 */
	public function get_field( string $id ): ?Info_Field_Abstract;

	/**
	 * Adds a field to this section.
	 *
	 * @see Generic_Info_Field If you are looking on how to quickly generate a field without creating a new class.
	 *
	 * @since TBD
	 *
	 * @param Info_Field_Abstract $field     What field we are trying to add.
	 * @param bool                $overwrite (optional) Determines if we will overwrite the field or not, if found.
	 *
	 * @return bool Determined by if we found a field with that id already.
	 */
	public function add_field( Info_Field_Abstract $field, bool $overwrite = false ): bool;
}