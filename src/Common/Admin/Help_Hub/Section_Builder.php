<?php
/**
 * Section Builder for the Help Hub.
 *
 * Provides a fluent interface for building consistent Help Hub sections with proper
 * structure and type safety, and stores built sections for later retrieval.
 *
 * @since   TBD
 * @package TEC\Common\Admin\Help_Hub
 */

namespace TEC\Common\Admin\Help_Hub;

/**
 * Class Section_Builder
 *
 * Helper class to build and store consistent Help Hub sections with proper structure and type safety.
 *
 * @since   TBD
 * @package TEC\Common\Admin\Help_Hub
 */
class Section_Builder {
	/**
	 * The section title.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	private string $title;

	/**
	 * The section identifier/slug.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	private string $slug;

	/**
	 * The section description.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	private string $description = '';

	/**
	 * The section type.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	private string $type = 'default';

	/**
	 * The section links or FAQ items.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	private array $items = [];

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
	 * @param string $type  Optional. The section type ('default' or 'faq').
	 *
	 * @return static
	 */
	public static function make( string $title, string $slug, string $type = 'default' ): self {
		$instance        = new self();
		$instance->title = $title;
		$instance->slug  = $slug;
		$instance->type  = $type;

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
	 * Add a link to the section (for default sections).
	 *
	 * @since TBD
	 *
	 * @param string $title The link title.
	 * @param string $url   The link URL.
	 * @param string $icon  Optional. The icon URL.
	 *
	 * @return $this
	 */
	public function add_link( string $title, string $url, string $icon = '' ): self {
		if ( $this->type !== 'default' ) {
			return $this;
		}

		$this->items[] = [
			'title' => $title,
			'url'   => $url,
			'icon'  => $icon,
		];

		return $this;
	}

	/**
	 * Add a FAQ item to the section (for FAQ sections).
	 *
	 * @since TBD
	 *
	 * @param string $question  The FAQ question.
	 * @param string $answer    The FAQ answer.
	 * @param string $link_text Optional. The "Learn More" link text.
	 * @param string $link_url  Optional. The "Learn More" link URL.
	 *
	 * @return $this
	 */
	public function add_faq( string $question, string $answer, string $link_text = '', string $link_url = '' ): self {
		if ( $this->type !== 'faq' ) {
			return $this;
		}

		$faq = [
			'question' => $question,
			'answer'   => $answer,
		];

		if ( $link_text && $link_url ) {
			$faq['link_text'] = $link_text;
			$faq['link_url']  = $link_url;
		}

		$this->items[] = $faq;

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
			'type'        => $this->type,
		];

		// Add items based on section type.
		if ( $this->type === 'faq' ) {
			$section['faqs'] = $this->items;
		} else {
			$section['links'] = $this->items;
		}

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
}
