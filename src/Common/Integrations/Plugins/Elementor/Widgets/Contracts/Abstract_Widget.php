<?php
/**
 * Abstract Elementor Widget.
 *
 * @since   TBD
 *
 * @package TEC\Common\Integrations\Plugins\Elementor\Widgets\Contracts
 */

namespace TEC\Common\Integrations\Plugins\Elementor\Widgets\Contracts;

use TEC\Common\Integrations\Plugins\Elementor\Widgets\Template_Engine;
use Elementor\Widget_Base;
use WP_Post;

/**
 * Abstract Widget class
 *
 * All template widgets should extend this class.
 */
abstract class Abstract_Widget extends Widget_Base {
	/**
	 * Widget slug prefix.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected static string $slug_prefix = 'tec_elementor_widget_';


	/**
	 * Widget asset prefix.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected static string $asset_prefix = 'tec-elementor-widget-';

	/**
	 * Widget group key.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected static string $group_key = 'tec-elementor-widgets';


	/**
	 * Widget asset base path.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected static string $asset_base_path = 'integrations/plugins/elementor/widgets/';

	/**
	 * Widget slug.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected static string $slug;

	/**
	 * Widget's associated post type.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected static string $post_type = 'post';

	/**
	 * Whether the widget has styles to register/enqueue.
	 *
	 * @since TBD
	 *
	 * @var bool
	 */
	protected static bool $has_styles = false;

	/**
	 * Whether the widget has scripts to register/enqueue.
	 *
	 * @since TBD
	 *
	 * @var bool
	 */
	protected static bool $has_scripts = false;

	/**
	 * Widget categories.
	 *
	 * @since TBD
	 *
	 * @var array<string>
	 */
	protected array $categories = [ 'the-events-calendar' ];

	/**
	 * Widget template engine.
	 *
	 * @since TBD
	 *
	 * @var Template_Engine
	 */
	protected Template_Engine $template;

	/**
	 * Widget template prefix.
	 *
	 * This holds the base path to the widget templates.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected static string $template_prefix = 'tec/integrations/elementor/widgets';

	/**
	 * Template engine class.
	 *
	 * @since TBD
	 *
	 * @var string The template engine class to use.
	 */
	protected string $template_engine_class = Template_Engine::class;

	/**
	 * The hooks added by the widget.
	 *
	 * @since TBD
	 *
	 * @var array<string,array>
	 */
	protected array $added_hooks = [];

	/**
	 * Get elementor widget slug.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_elementor_slug(): string {
		return $this->get_slug_prefix() . static::get_slug();
	}

	/**
	 * Gets the name (aka slug) of the widget.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_name(): string {
		return $this->get_elementor_slug();
	}

	/**
	 * Get local widget slug.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public static function get_slug(): string {
		return static::$slug;
	}

	/**
	 * Get local widget slug.
	 *
	 * @since TBD
	 *
	 * @param bool $trim Whether to trim the last underscore from the prefix.
	 *
	 * @return string
	 */
	public function get_slug_prefix( $trim = false ): string {
		$prefix = static::$slug_prefix;


		if ( $trim ) {
			$prefix = rtrim( $prefix, '_' );
		} else {
			// Ensure our prefix ends with an underscore.
			$prefix = rtrim( $prefix, '_' ) . '_';
		}

		/**
		 * Filters the slug prefix for all tec-elementor widgets.
		 *
		 * @since TBD
		 *
		 * @param string $prefix The widget slug prefix.
		 * @param bool   $trim   Whether to trim the last underscore from the prefix.
		 * @param object $this   The widget instance.
		 *
		 * @return string
		 */
		return (string) apply_filters( 'tec_elementor_widget_slug_prefix', $prefix, $trim, $this );
	}

	public function create_slug( $slug ) {
		$prefix = $this->get_slug_prefix();

		return $prefix . $slug;
	}

	/**
	 * Get local widget slug.
	 *
	 * @since TBD
	 *
	 * @param bool $trim Whether to trim the last  slug.
	 *
	 * @return string
	 */
	public function get_asset_prefix( $trim = false ): string {
		$prefix = str_replace( '_', '-', $this->get_slug_prefix() );

		if ( $trim ) {
			$prefix = rtrim( $prefix, '-' );
		}

		return $prefix;
	}

	/**
	 * Gets the title of the widget.
	 *
	 * @since TBD
	 */
	public function get_title(): string {
		$title = $this->title();
		$slug  = static::get_slug();

		/**
		 * Filters the title of the widget.
		 *
		 * @since TBD
		 *
		 * @param string          $title The widget title.
		 * @param Abstract_Widget $this  The widget instance.
		 */
		$title = apply_filters( $this->create_slug( 'title' ), $title, $this );

		/**
		 * Filters the title of a specific tec-elementor widget, by slug.
		 *
		 * @since TBD
		 *
		 * @param string          $title The widget title.
		 * @param Abstract_Widget $this  The widget instance.
		 */
		return (string) apply_filters( $this->get_slug_prefix() . "widget_{$slug}_title", $title, $this );
	}

	/**
	 * Gets/creates the title of the widget.
	 * This must be overridden by the child class to include translating the title string.
	 *
	 * @since TBD
	 */
	abstract protected function title(): string;

	/**
	 * Gets the icon class for the widget.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return $this->get_icon_class();
	}

	/**
	 * Get the template file path, which will be used to include the correct widget template to be rendered.
	 * By default, it will be the combination of a folder named 'widgets' and the widget slug with _ replaced by -.
	 * For example:
	 * - if the widget slug is 'event_cost'
	 * - template file path will be 'widgets/event-cost'.
	 *
	 * This method can be overridden by the child class to provide a custom template file path.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_template_file(): string {
		$file = str_replace( '_', '-', static::get_slug() );

		return "widgets/{$file}";
	}

	/**
	 * Gets the CSS class list for the widget.
	 * As a string (for use in attributes) or as an array.
	 *
	 * @since TBD
	 *
	 * @param string $format The format to return. Either 'attribute' (default) or 'array'.
	 *
	 * @return string|array<string>
	 */
	public function get_element_classes( string $format = 'attribute' ) {
		// If the property is empty, generate and use the widget class.
		$classes = $this->get_widget_class();
		$slug    = static::get_slug();

		/**
		 * Filters the widget class list for all tec-elementor widgets.
		 *
		 * @since TBD
		 *
		 * @param array<string>   $classes The widget classes.
		 * @param string          $format  The format to return. Either 'attribute' (default - returns a string) or 'array'.
		 * @param Abstract_Widget $this    The widget instance.
		 *
		 * @return array<string>
		 */
		$classes = apply_filters( $this->get_slug_prefix() . 'element_classes', (array) $classes, $format, $this );

		/**
		 * Filters the widget class list for a specific tec-elementor widget, by slug.
		 *
		 * @since TBD
		 *
		 * @param array<string>   $classes The widget classes.
		 * @param string          $format  The format to return. Either 'attribute' (default - returns a string) or 'array'.
		 * @param Abstract_Widget $this    The widget instance.
		 *
		 * @return array<string>
		 */
		$classes = apply_filters( $this->get_slug_prefix() . "{$slug}_element_classes", (array) $classes, $format, $this );

		// If we want a string, this is where we convert.
		if ( 'attribute' === $format ) {
			return implode( ' ', (array) $classes );
		}

		return $classes;
	}

	/**
	 * Provides a "trimmed" slug for usage in classes and such and converts all underscores to dashes.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public static function trim_slug(): string {
		return trim( str_replace( '_', '-', static::get_slug() ) );
	}

	/**
	 * Provides the main CSS class for the widget.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_widget_class(): string {
		$slug  = static::get_slug();
		$class = static::get_asset_prefix() . '_' . static::trim_slug();

		/**
		 * Filters the widget class for all tec-elementor widgets.
		 *
		 * @since TBD
		 *
		 * @param string          $class The widget class.
		 * @param Abstract_Widget $this  The widget instance.
		 *
		 * @return string
		 */
		$class = apply_filters( $this->get_slug_prefix() . 'class', $class, $this );

		/**
		 * Filters the widget class for a specific tec-elementor widget, by slug.
		 *
		 * @since TBD
		 *
		 * @param string          $class The widget class.
		 * @param Abstract_Widget $this  The widget instance.
		 *
		 * @return string
		 */
		return apply_filters( $this->get_slug_prefix() . "{$slug}_class", $class, $this );
	}

	/**
	 * Provides the CSS class for the widget icon.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_icon_class(): string {
		$slug  = static::get_slug();
		$class = static::get_asset_prefix() . '__icon-' . static::trim_slug();

		/**
		 * Filters the widget icon class for all tec-elementor widgets.
		 *
		 * @since TBD
		 *
		 * @param string          $class The widget class.
		 * @param Abstract_Widget $this  The widget instance.
		 *
		 * @return string
		 */
		$class = apply_filters( $this->get_slug_prefix() . 'icon_class', $class, $this );

		/**
		 * Filters the widget icon class for a specific tec-elementor widget, by slug.
		 *
		 * @since TBD
		 *
		 * @param string          $class The widget class.
		 * @param Abstract_Widget $this  The widget instance.
		 *
		 * @return string
		 */
		return (string) apply_filters( $this->get_slug_prefix() . "{$slug}icon_class", $class, $this );
	}

	/**
	 * Gets the categories of the widget.
	 *
	 * @since TBD
	 *
	 * @return array<string>
	 */
	public function get_categories(): array {
		return $this->categories;
	}

	/**
	 * An internal, filterable function to get the ID of the post the widget is used in.
	 *
	 * @since TBD
	 *
	 * @return int|false The ID of the current item (parent post) the widget is in. False if not found.
	 */
	protected function post_id(): ?int {
		$post_id = (int) get_the_ID();
		$slug    = static::get_slug();

		if (
			is_admin() &&
			'elementor' === tribe_get_request_var( 'action' )
		) {
			$post_id = (int) tribe_get_request_var( 'post', false );
		}

		/**
		 * Filters the post ID of the post the widget is used in.
		 *
		 * @since TBD
		 *
		 * @param int             $post_id The post ID.
		 * @param Abstract_Widget $this    The widget instance.
		 */
		$post_id = (int) apply_filters( $this->get_slug_prefix() . 'post_id', (int) $post_id, $this );

		/**
		 * Filters the post ID of the post the widget is used in.
		 *
		 * @since TBD
		 *
		 * @param int             $post_id The post ID.
		 * @param Abstract_Widget $this    The widget instance.
		 */
		$post_id = (int) apply_filters( $this->get_slug_prefix() . "{$slug}_post_id", (int) $post_id, $this );

		if ( get_post_type( $post_id ) !== static::get_widget_post_type() ) {
			return null;
		}

		return $post_id > 0 ? $post_id : null;
	}

	/**
	 * Get the post ID.
	 *
	 * @since TBD
	 *
	 * @return int|null
	 */
	public function get_post_id() {
		return $this->post_id();
	}

	/**
	 * Get the post object for the widget.
	 *
	 * @since TBD
	 *
	 * @return WP_Post|null
	 */
	public function get_post() {
		$post = get_post( $this->get_post_id() );

		/**
		 * Filters the post object for the widget.
		 *
		 * @since TBD
		 *
		 * @param WP_Post         $post The post object.
		 * @param Abstract_Widget $this The widget instance.
		 *
		 * @return WP_Post
		 */
		return apply_filters( $this->get_slug_prefix() . 'post', $post, $this );
	}

	/**
	 * Determines if the widget has a valid post ID associated with it.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	protected function has_post_id(): bool {
		return $this->get_post_id() !== null;
	}

	/**
	 * Get the post type associated with the widget.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public static function get_widget_post_type(): string {
		return static::$post_type;
	}

	/**
	 * Get the template engine class.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	protected function get_template_engine_class(): string {
		// Ensures that the class returned is a subclass of Template_Engine.
		if ( ! is_subclass_of( $this->template_engine_class, Template_Engine::class ) ) {
			return Template_Engine::class;
		}

		return $this->template_engine_class;
	}

	/**
	 * Get template object.
	 *
	 * @since TBD
	 *
	 * @return Template_Engine
	 */
	public function get_template(): Template_Engine {
		if ( empty( $this->template ) ) {
			/**
			 * @var Template_Engine $template_engine_class
			 */
			$template_engine_class = $this->get_template_engine_class();
			$this->template        = $template_engine_class::with_widget( $this );
			$this->template->set_post( $this->get_post() );

			do_action( 'tec_elementor_widget_set_template', $this );
		}

		return $this->template;
	}

	public function get_template_prefix( $file = '' ): string {
		return trailingslashit( static::$template_prefix ) . ltrim( $file, '/' );
	}

	/**
	 * Set up a self-removing filter for a widget template, it should hook itself on the before and after include hooks
	 * of the template engine.
	 *
	 * @since TBD
	 *
	 * @param string    $on            The hook to add on.
	 * @param ?callable $callback      The callback to add to the filter.
	 * @param int       $priority      The priority of the filter.
	 * @param int       $accepted_args The number of arguments the filter accepts.
	 */
	protected function set_template_filter( string $on, ?callable $callback = null, int $priority = 10, int $accepted_args = 1 ): void {
		$hook_name     = $this->get_template_prefix( $this->trim_slug() );

		$add    = "tribe_template_before_include:{$hook_name}";
		$remove = "tribe_template_after_include:{$hook_name}";

		// ensure the callback is callable.
		if ( ! is_callable( $callback ) ) {
			return;
		}

		$add_callback = static function () use ( $on, $callback, $priority, $accepted_args ) {
			add_filter( $on, $callback, $priority, $accepted_args );
		};

		$remove_callback = static function () use ( $on, $callback, $priority ) {
			remove_filter( $on, $callback, $priority );
		};

		// Include the hook.
		add_action( $add, $add_callback );
		$this->added_hooks[] = [
			'hook'     => $add,
			'callback' => $add_callback,
		];

		// Remove the hook.
		add_action( $remove, $remove_callback );
		$this->added_hooks[] = [
			'hook'     => $remove,
			'callback' => $remove_callback,
		];
	}

	/**
	 * Unset the template filters.
	 *
	 * @since TBD
	 */
	protected function unset_template_filters(): void {
		foreach ( $this->added_hooks as $hook ) {
			remove_action( $hook['hook'], $hook['callback'] );
		}
	}

	/**
	 * Get the template args for the widget.
	 *
	 * @since TBD
	 *
	 * @return array The template args.
	 */
	abstract protected function template_args(): array;

	/**
	 * Determine if the widget should show mock data.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public function should_show_mock_data(): bool {
		return false;
	}

	/**
	 * Get the template arguments.
	 *
	 * This calls the template_args method on the widget and then filters the data.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function get_template_args(): array {
		$args = $this->template_args(); // Defined in each widget instance.
		$slug = static::get_slug();


		/**
		 * Filters the template data for all Elementor widget templates.
		 *
		 * @param array<string,mixed> $args   The template data.
		 * @param bool                $preview Whether the template is in preview mode.
		 * @param object              $widget The widget object.
		 *
		 * @return array
		 */
		$args = (array) apply_filters( $this->get_slug_prefix() . 'template_data', $args, false, $this );

		/**
		 * Filters the template data for a specific (by $slug) Elementor widget templates.
		 *
		 * @param array<string,mixed> $args   The template data.
		 * @param bool                $preview Whether the template is in preview mode.
		 * @param object              $widget The widget object.
		 *
		 * @return array
		 */
		$args = (array) apply_filters( $this->get_slug_prefix() . "{$slug}_template_data", $args, false, $this );

		// Add the widget to the data array.
		$args['widget'] = $this;

		return $args;
	}

	/**
	 * Get the asset source (plugin) for the widget.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	abstract protected function get_asset_source();

	/**
	 * Get the asset base path for the widget.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_asset_base_path(): string {
		/**
		 * Filters the asset base path for all Elementor widgets.
		 *
		 * @since TBD
		 *
		 * @param string          $path The asset base path.
		 * @param Abstract_Widget $this The widget instance.
		 *
		 * @return string
		 */
		return (string) apply_filters( $this->get_slug_prefix() . 'asset_base_path', static::$asset_base_path, $this );
	}

	/**
	 * Get the asset file name for the widget.
	 *
	 * @since TBD
	 *
	 * @param string $suffix The file suffix. 'js' for a javascript or 'css' for a stylesheet asset. Default is 'css'.
	 *
	 * @return string
	 */
	public function get_asset_file_name( $suffix = 'css' ) {
		$file = static::get_asset_base_path() . static::trim_slug();
		$file .= ( 'js' === $suffix ) ? '.js' : '.css';

		/**
		 * Filters the asset file name for all Elementor widgets.
		 *
		 * @since TBD
		 *
		 * @param string          $file The asset file name.
		 * @param string          $suffix The file suffix. Should be 'js' or 'css'.
		 * @param Abstract_Widget $this The widget instance.
		 *
		 * @return string
		 */
		return (string) apply_filters( $this->get_slug_prefix() . 'asset_file_name', $file, $suffix, $this );
	}

	/**
	 * Get the asset handle for the widget.
	 *
	 * @since TBD
	 *
	 * @param string $suffix The file suffix. 'js' for a javascript or 'css' for a stylesheet asset. Default is 'css'.
	 *
	 * @return string
	 */
	public function get_asset_handle( $suffix = 'css' ) {
		$handle = static::get_asset_prefix() . static::trim_slug();
		$handle .= ( 'js' === $suffix ) ? '-scripts' : '-styles';

		/**
		 * Filters the asset handle for all Elementor widgets.
		 *
		 * @since TBD
		 *
		 * @param string          $handle The asset handle.
		 * @param string          $suffix The file suffix. Should be 'js' or 'css'.
		 * @param Abstract_Widget $this The widget instance.
		 *
		 * @return string
		 */
		return (string) apply_filters( $this->get_slug_prefix() . 'asset_handle', $handle, $suffix, $this );
	}

	/**
	 * Register the styles for the widget.
	 *
	 * @since TBD
	 */
	public function register_assets(): void {
		if ( ! static::$has_styles ) {
			return;
		}

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		// Register the styles for the widget.
		if ( static::$has_styles ) {
			tribe_asset(
				tribe( $this->get_asset_source() ),
				$this->get_asset_handle(),
				$this->get_asset_file_name(),
				[],
				null,
				[ 'groups' => [ static::$group_key ] ]
			);
		}

		// Register the scripts for the widget.
		if ( static::$has_scripts ) {
			tribe_asset(
				tribe( $this->get_asset_source() ),
				$this->get_asset_handle( 'js' ),
				$this->get_asset_file_name( 'js' ),
				[],
				null,
				[ 'groups' => [ static::$group_key ] ]
			);
		}

	}

	/**
	 * Enqueue the styles for the widget.
	 *
	 * @since TBD
	 */
	public function enqueue_style(): void {
		if ( ! static::$has_styles ) {
			return;
		}

		tribe_asset_enqueue( static::get_asset_handle() );
	}

	/**
	 * Enqueue the styles for the widget.
	 *
	 * @since TBD
	 */
	public function enqueue_script(): void {
		if ( ! static::$has_scripts ) {
			return;
		}

		tribe_asset_enqueue( static::get_asset_handle( 'js' ) );
	}

	/**
	 * Get the output of the widget.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_output(): string {
		$template = $this->get_template();
		$output = $template->template( 'widgets/base', $this->get_template_args(), false );

		if ( ! $template->has_post() && ! $template->get_widget()->should_show_mock_data() ) {
			return '';
		}

		$this->unset_template_filters();

		return $output;
	}

	/**
	 * Render the Elementor widget, this method needs to be protected as it is originally defined as such in elementor.
	 *
	 * @since TBD
	 */
	protected function render(): void {
		echo $this->get_output(); // phpcs:ignore StellarWP.XSS.EscapeOutput.OutputNotEscaped,WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
