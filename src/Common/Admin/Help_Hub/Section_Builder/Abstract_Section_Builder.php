<?php
/**
 * Abstract Section Builder for the Help Hub.
 *
 * Provides a base class for building consistent Help Hub sections with proper
 * structure and type safety, and stores built sections for later retrieval.
 *
 * @since 6.8.0
 *
 * @var array $section The section data containing links to render.
 */

namespace TEC\Common\Admin\Help_Hub\Section_Builder;

use InvalidArgumentException;
use RuntimeException;

/**
 * Abstract class Abstract_Section_Builder
 *
 * Base class to build and store consistent Help Hub sections with proper structure and type safety.
 *
 * @since 6.8.0
 * @package TEC\Common\Admin\Help_Hub
 */
abstract class Abstract_Section_Builder implements Section_Builder_Interface {
	/**
	 * The section title.
	 *
	 * @since 6.8.0
	 *
	 * @var string
	 */
	protected string $title;

	/**
	 * The section identifier/slug.
	 *
	 * @since 6.8.0
	 *
	 * @var string
	 */
	protected string $slug;

	/**
	 * The section description.
	 *
	 * @since 6.8.0
	 *
	 * @var string
	 */
	protected string $description = '';

	/**
	 * The section items.
	 *
	 * @since 6.8.0
	 *
	 * @var array
	 */
	protected array $items = [];

	/**
	 * Static storage for all built sections.
	 *
	 * @since 6.8.0
	 *
	 * @var array<string, array>
	 */
	private static array $sections = [];

	/**
	 * The items array key.
	 *
	 * @since 6.8.0
	 *
	 * @var string
	 */
	protected const ITEMS_KEY = '';

	/**
	 * Create a new section instance.
	 *
	 * @since 6.8.0
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
	 * @since 6.8.0
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
	 * @since 6.8.0
	 *
	 * @param array $item The item to add.
	 *
	 * @return $this
	 */
	public function add_item( array $item ): self {
		$this->validate_item( $item );

		/**
		 * Filter the item before it's added to the section.
		 *
		 * @since 6.8.0
		 *
		 * @param array  $item The item to add.
		 * @param string $slug The section slug.
		 */
		$item = apply_filters( "tec_help_hub_section_{$this->slug}_item", $item, $this->slug );

		$this->items[] = $item;

		return $this;
	}

	/**
	 * Validate an item before adding it to the section.
	 *
	 * @since 6.8.0
	 *
	 * @throws InvalidArgumentException If the item is invalid.
	 *
	 * @param array $item The item to validate.
	 *
	 * @return void
	 */
	protected function validate_item( array $item ): void {
		if ( empty( $item ) ) {
			throw new InvalidArgumentException( 'Item cannot be empty' );
		}
	}

	/**
	 * Build the section array.
	 *
	 * @since 6.8.0
	 *
	 * @throws RuntimeException If the concrete class doesn't implement ITEMS_KEY.
	 * @return array The section array.
	 */
	public function build(): array {
		if ( empty( static::ITEMS_KEY ) ) {
			throw new RuntimeException( 'Items key must be defined in the concrete class' );
		}

		$section = [
			'title'       => $this->title,
			'slug'        => $this->slug,
			'description' => $this->description,
			'type'        => static::ITEMS_KEY,
		];

		/**
		 * Filter the items array before it's added to the section.
		 *
		 * @since 6.8.0
		 *
		 * @param array  $items The items array.
		 * @param string $slug  The section slug.
		 */
		$items = apply_filters( "tec_help_hub_section_{$this->slug}_items", $this->items, $this->slug );

		// Add items based on section type.
		$section[ static::ITEMS_KEY ] = $items;

		/**
		 * Filter the section data before it's stored.
		 *
		 * @since 6.8.0
		 *
		 * @param array  $section The section data.
		 * @param string $slug    The section slug.
		 */
		$section = apply_filters( "tec_help_hub_section_{$this->slug}", $section, $this->slug );

		/**
		 * Filter the section data after it's built.
		 *
		 * @since 6.8.0
		 *
		 * @param array  $section The section data.
		 * @param string $slug    The section slug.
		 */
		$section = apply_filters( 'tec_help_hub_section', $section, $this->slug );

		// Store the section.
		self::$sections[ $this->slug ] = $section;

		return $section;
	}

	/**
	 * Get all built sections.
	 *
	 * @since 6.8.0
	 *
	 * @return array All built sections.
	 */
	public static function get_all_sections(): array {
		return self::$sections;
	}

	/**
	 * Get a specific section by slug.
	 *
	 * @since 6.8.0
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
	 * @since 6.8.0
	 *
	 * @return void
	 */
	public static function clear_sections(): void {
		self::$sections = [];
	}
}
