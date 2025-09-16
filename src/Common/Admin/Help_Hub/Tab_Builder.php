<?php
/**
 * Tab Builder for the Help Hub.
 *
 * Provides a fluent interface for building consistent Help Hub tabs with proper
 * structure and type safety, and stores built tabs for later retrieval.
 *
 * @since 6.8.0
 * @package TEC\Common\Admin\Help_Hub
 */

namespace TEC\Common\Admin\Help_Hub;

/**
 * Class Tab_Builder
 *
 * Helper class to build and store consistent Help Hub tabs with proper structure and type safety.
 *
 * @since 6.8.0
 * @package TEC\Common\Admin\Help_Hub
 */
class Tab_Builder {
	/**
	 * The tab target.
	 *
	 * @since 6.8.0
	 *
	 * @var string
	 */
	private string $target;

	/**
	 * The tab CSS class.
	 *
	 * @since 6.8.0
	 *
	 * @var string
	 */
	private string $class = '';

	/**
	 * The tab label.
	 *
	 * @since 6.8.0
	 *
	 * @var string
	 */
	private string $label;

	/**
	 * The tab ID.
	 *
	 * @since 6.8.0
	 *
	 * @var string
	 */
	private string $id;

	/**
	 * The tab template.
	 *
	 * @since 6.8.0
	 *
	 * @var string
	 */
	private string $template;

	/**
	 * The tab arguments.
	 *
	 * @since 6.8.0
	 *
	 * @var array<string, mixed>
	 */
	private array $args = [];

	/**
	 * Static storage for all built tabs.
	 *
	 * @since 6.8.0
	 *
	 * @var array<string, array>
	 */
	private static array $tabs = [];

	/**
	 * Create a new tab instance.
	 *
	 * @since 6.8.0
	 *
	 * @param string               $target   The tab target.
	 * @param string               $label    The tab label.
	 * @param string               $id       The tab ID.
	 * @param string               $template The tab template.
	 * @param array<string, mixed> $args     Optional arguments for the tab.
	 *
	 * @return self
	 */
	public static function make( string $target, string $label, string $id, string $template, array $args = [] ): self {
		$instance           = new self();
		$instance->target   = $target;
		$instance->label    = $label;
		$instance->id       = $id;
		$instance->template = $template;
		$instance->args     = $args;

		return $instance;
	}

	/**
	 * Set the tab CSS class.
	 *
	 * @since 6.8.0
	 *
	 * @param string $class_name The tab CSS class.
	 *
	 * @return $this
	 */
	public function set_class( string $class_name ): self {
		$this->class = $class_name;

		return $this;
	}

	/**
	 * Set the tab arguments.
	 *
	 * @since 6.8.0
	 *
	 * @param array<string, mixed> $args The tab arguments.
	 *
	 * @return $this
	 */
	public function set_arguments( array $args ): self {
		$this->args = $args;

		return $this;
	}

	/**
	 * Get the tab arguments.
	 *
	 * @since 6.8.0
	 *
	 * @return array<string, mixed> The tab arguments.
	 */
	public function get_arguments(): array {
		return $this->args;
	}

	/**
	 * Build the tab array and store it.
	 *
	 * @since 6.8.0
	 *
	 * @return array{
	 *     target: string,
	 *     class: string,
	 *     label: string,
	 *     id: string,
	 *     template: string,
	 *     args: array<string, mixed>
	 * } The built tab.
	 */
	public function build(): array {
		$tab = [
			'target'   => $this->target,
			'class'    => $this->class,
			'label'    => $this->label,
			'id'       => $this->id,
			'template' => $this->template,
			'args'     => $this->args,
		];

		/**
		 * Filter a specific Help Hub tab.
		 *
		 * @since 6.8.0
		 *
		 * @param array  $tab The tab data.
		 * @param string $id  The tab ID.
		 */
		$tab = (array) apply_filters( "tec_help_hub_tab_{$this->id}", $tab, $this->id );

		// Store the tab.
		self::$tabs[ $this->id ] = $tab;

		return $tab;
	}

	/**
	 * Get all stored tabs.
	 *
	 * @since 6.8.0
	 *
	 * @return array<string, array> All stored tabs.
	 */
	public static function get_all_tabs(): array {
		return self::$tabs;
	}

	/**
	 * Get a specific tab by ID.
	 *
	 * @since 6.8.0
	 *
	 * @param string $id The tab ID.
	 *
	 * @return array|null The tab data or null if not found.
	 */
	public static function get_tab( string $id ): ?array {
		return self::$tabs[ $id ] ?? null;
	}

	/**
	 * Clear all stored tabs.
	 *
	 * @since 6.8.0
	 *
	 * @return void
	 */
	public static function clear_tabs(): void {
		self::$tabs = [];
	}
}
