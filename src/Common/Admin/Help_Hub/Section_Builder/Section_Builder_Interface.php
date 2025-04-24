<?php
/**
 * Interface for Section Builders.
 *
 * @since TBD
 */

namespace TEC\Common\Admin\Help_Hub\Section_Builder;

/**
 * Interface Section_Builder_Interface
 *
 * @since TBD
 * @package TEC\Common\Admin\Help_Hub
 */
interface Section_Builder_Interface {
	/**
	 * Create a new section instance.
	 *
	 * @since TBD
	 *
	 * @param string $title The section title.
	 * @param string $slug  The section identifier/slug.
	 *
	 * @return static
	 */
	public static function make( string $title, string $slug ): self;

	/**
	 * Set the section description.
	 *
	 * @since TBD
	 *
	 * @param string $description The section description.
	 *
	 * @return $this
	 */
	public function set_description( string $description ): self;

	/**
	 * Add an item to the section.
	 *
	 * @since TBD
	 *
	 * @param array $item The item to add.
	 *
	 * @return $this
	 */
	public function add_item( array $item ): self;

	/**
	 * Build the section array.
	 *
	 * @since TBD
	 *
	 * @return array The section array.
	 */
	public function build(): array;

	/**
	 * Get all built sections.
	 *
	 * @since TBD
	 *
	 * @return array All built sections.
	 */
	public static function get_all_sections(): array;

	/**
	 * Get a specific section by slug.
	 *
	 * @since TBD
	 *
	 * @param string $slug The section slug.
	 *
	 * @return array|null The section data or null if not found.
	 */
	public static function get_section( string $slug ): ?array;

	/**
	 * Clear all stored sections.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public static function clear_sections(): void;
} 