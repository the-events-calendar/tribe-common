<?php
/**
 * Abstract Section Builder for the Help Hub.
 *
 * Provides a base class for building consistent Help Hub sections with proper
 * structure and type safety, and stores built sections for later retrieval.
 *
 * @since   TBD
 *
 * @var array $section The section data containing links to render.
 */

namespace TEC\Common\Admin\Help_Hub\Section_Builder;

/**
 * Abstract class Abstract_Section_Builder
 *
 * Base class to build and store consistent Help Hub sections with proper structure and type safety.
 *
 * @since   TBD
 * @package TEC\Common\Admin\Help_Hub
 */
abstract class Abstract_Section_Builder {
	/**
	 * The section title.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected string $title;

	/**
	 * The section identifier/slug.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected string $slug;

	/**
	 * The section description.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected string $description = '';

	/**
	 * The section items.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	protected array $items = [];

	/**
	 * Static storage for all built sections.
	 *
	 * @since TBD
	 *
	 * @var array<string, array>
	 */
	private static array $sections = [];

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
	public static function make( string $title, string $slug ): self {
		$instance        = new static();
		$instance->title = $title;
		$instance->slug  = $slug;

		return $instance;
	}

	/**
	 * Set the section description.
	 *
	 * @since TBD
	 *
	 * @param string $description The section description.
	 *
	 * @return $this
	 */
	public function set_description( string $description ): self {
		$this->description = $description;

		return $this;
	}

	/**
	 * Add an item to the section.
	 *
	 * @since TBD
	 *
	 * @param array $item The item to add.
	 *
	 * @return $this
	 */
	protected function add_item( array $item ): self {
		$this->items[] = $item;

		return $this;
	}

	/**
	 * Build the section array.
	 *
	 * @since TBD
	 *
	 * @return array The section array.
	 */
	public function build(): array {
		$section = [
			'title'       => $this->title,
			'slug'        => $this->slug,
			'description' => $this->description,
			'type'        => $this->get_type(),
		];

		// Add items based on section type.
		$section[ $this->get_items_key() ] = $this->items;

		/**
		 * Filter the section data.
		 *
		 * @since TBD
		 *
		 * @param array  $section The section data.
		 * @param string $slug    The section slug.
		 */
		$section = apply_filters( "tec_help_hub_section_{$this->slug}", $section, $this->slug );

		// Store the section.
		self::$sections[ $this->slug ] = $section;

		return $section;
	}

	/**
	 * Get all built sections.
	 *
	 * @since TBD
	 *
	 * @return array All built sections.
	 */
	public static function get_all_sections(): array {
		return self::$sections;
	}

	/**
	 * Get a specific section by slug.
	 *
	 * @since TBD
	 *
	 * @param string $slug The section slug.
	 *
	 * @return array|null The section data or null if not found.
	 */
	public static function get_section( string $slug ): ?array {
		return self::$sections[ $slug ] ?? null;
	}

	/**
	 * Clear all stored sections.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public static function clear_sections(): void {
		self::$sections = [];
	}

	/**
	 * Get the section type.
	 *
	 * @since TBD
	 *
	 * @return string The section type.
	 */
	abstract protected function get_type(): string;

	/**
	 * Get the items array key.
	 *
	 * @since TBD
	 *
	 * @return string The items array key.
	 */
	abstract protected function get_items_key(): string;
}
