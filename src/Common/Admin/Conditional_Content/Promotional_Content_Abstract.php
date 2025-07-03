<?php
/**
 * Abstract class for promotional conditional content.
 *
 * @since TBD
 *
 * @package TEC\Common\Admin\Conditional_Content;
 */

namespace TEC\Common\Admin\Conditional_Content;

use TEC\Common\Admin\Entities\{
	Div,
	Container,
	Button,
	Link,
	Image
};

use TEC\Common\Admin\{
	Settings_Section,
	Settings_Sidebar,
	Settings_Sidebar_Section,
};

use Tribe\Utils\{
	Element_Attributes as Attributes,
	Date_I18n,
	Element_Classes
};

use Tribe__Template as Template;

/**
 * Abstract class for promotional content with banners.
 *
 * @since TBD
 */
abstract class Promotional_Content_Abstract {
	use Dismissible_Trait;

	/**
	 * Slug for the promotional content.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected string $slug;

	/**
	 * Background color for the promotional content.
	 * Must match the background color of the image.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected string $background_color = 'transparent';

	/**
	 * Stores the instance of the template engine that we will use for rendering the page.
	 *
	 * @since 6.3.0
	 *
	 * @var Template
	 */
	protected Template $template;

	/**
	 * @inheritdoc
	 */
	public function hook(): void {
		// Only hook the AJAX dismiss handler - sidebar integration is handled by Controller.
		add_action( 'wp_ajax_tec_conditional_content_dismiss', [ $this, 'handle_dismiss' ] );

		// Initialize trait hooks when the object is hooked.
		$this->initialize_trait_hooks();
	}

	/**
	 * Get the background color.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	protected function get_background_color(): string {
		return $this->background_color;
	}

	/**
	 * Define which plugin suites this promotional content should target.
	 *
	 * @since TBD
	 *
	 * @return array List of plugin suites ('events', 'tickets')
	 */
	abstract protected function get_target_plugin_suites(): array;

	/**
	 * Define the mapping of suites to creative content configurations.
	 *
	 * This method will be used by Plugin_Suite_Conditional_Trait to get the content matrix.
	 *
	 * @since TBD
	 *
	 * @return array Associative array of plugin suites and their creative configurations.
	 */
	abstract protected function get_suite_creative_map(): array;

	/**
	 * Sale name for display.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	abstract protected function get_sale_name(): string;

	/**
	 * Link URL for the promotional content.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	abstract protected function get_link_url(): string;

	/**
	 * Wide banner image filename.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	protected function get_wide_banner_image() {
		return $this->get_slug() . '/top-wide.png';
	}

	/**
	 * Narrow banner image filename.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	protected function get_narrow_banner_image() {
		return $this->get_slug() . '/top-narrow.png';
	}

	/**
	 * Settings sidebar image filename.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	protected function get_sidebar_image() {
		return $this->get_slug() . '/sidebar.png';
	}

	/**
	 * Get the template slug/filename.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	protected function get_template_slug(): string {
		return 'conditional-content';
	}

	/**
	 * Get the full slug with year.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_slug(): string {
		$year = date_i18n( 'Y' );
		return $this->slug . '-' . $year;
	}

	/**
	 * Render the header notice.
	 *
	 * @since 6.3.0
	 */
	public function render_header_notice(): void {
		/**
		 * Fires to allow for conditional content assets to be rendered.
		 * Before the banner is output.
		 *
		 * @since TBD
		 */
		do_action( 'tec_conditional_content_assets' );

		$this->render_responsive_banner_html();
	}

	/**
	 * Render the responsive banner HTML (combines wide and narrow into one efficient render).
	 *
	 * @since TBD
	 */
	protected function render_responsive_banner_html(): void {
		if ( ! $this->should_display() ) {
			return;
		}

		/**
		 * Fires before the responsive banner is rendered.
		 *
		 * @since TBD
		 *
		 * @param string $slug     The slug of the conditional content.
		 * @param object $instance The promotional content instance.
		 */
		do_action( "tec_conditional_content_{$this->slug}", 'responsive_banner', $this );

		echo $this->get_responsive_banner_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped,StellarWP.XSS.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Gets the content for the responsive banner promo.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	protected function get_responsive_banner_html(): string {
		$year      = date_i18n( 'Y' );
		$sale_name = $this->get_sale_name();

		// Get the creative content from the filters. It will now be an array.
		$creative = $this->get_content();

		// Use creative content if available, otherwise fall back to traditional methods.
		$template_args = [
			'background_color' => $this->get_background_color(),
			'wide_image_src'   => ! empty( $creative['image_url'] )
				? $creative['image_url']
				: tribe_resource_url( 'images/conditional-content/' . $this->get_wide_banner_image(), false, null, \Tribe__Main::instance() ),
			'narrow_image_src' => ! empty( $creative['narrow_image_url'] )
				? $creative['narrow_image_url']
				: tribe_resource_url( 'images/conditional-content/' . $this->get_narrow_banner_image(), false, null, \Tribe__Main::instance() ),
			'is_responsive'    => true,
			'is_sidebar'       => false,
			'link'             => ! empty( $creative['link_url'] ) ? $creative['link_url'] : $this->get_link_url(),
			'nonce'            => $this->get_nonce(),
			'sale_name'        => $sale_name,
			'slug'             => $this->get_slug(),
			'year'             => $year,
			'a11y_text'        => ! empty( $creative['alt_text'] ) ? $creative['alt_text'] : sprintf(
				/* translators: %1$s: Sale year (numeric), %2$s: Sale name */
				_x( '%1$s %2$s for The Events Calendar plugins, add-ons and bundles.', 'Alt text for the Sale Ad', 'tribe-common' ),
				$year,
				$sale_name
			),
		];

		return $this->get_template()->template( $this->get_template_slug(), $template_args, false );
	}

	/**
	 * Gets the content for the wide banner promo.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	protected function get_wide_banner_html(): string {
		// Now using get_content() which returns the creative array.
		$creative  = $this->get_content();
		$year      = date_i18n( 'Y' );
		$sale_name = $this->get_sale_name();

		$template_args = [
			'background_color' => $this->get_background_color(),
			'image_src'        => ! empty( $creative['image_url'] )
				? $creative['image_url']
				: tribe_resource_url( 'images/conditional-content/' . $this->get_wide_banner_image(), false, null, \Tribe__Main::instance() ),
			'is_narrow'        => false,
			'is_sidebar'       => false,
			'link'             => ! empty( $creative['link_url'] ) ? $creative['link_url'] : $this->get_link_url(),
			'nonce'            => $this->get_nonce(),
			'sale_name'        => $sale_name,
			'slug'             => $this->get_slug(),
			'year'             => $year,
			'a11y_text'        => ! empty( $creative['alt_text'] ) ? $creative['alt_text'] : sprintf(
				/* translators: %1$s: Sale year (numeric), %2$s: Sale name */
				_x( '%1$s %2$s for The Events Calendar plugins, add-ons and bundles.', 'Alt text for the Sale Ad', 'tribe-common' ),
				$year,
				$this->get_sale_name()
			),
		];

		return $this->get_template()->template( $this->get_template_slug(), $template_args, false );
	}

	/**
	 * Render the wide banner HTML.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function render_wide_banner_html(): void {
		if ( ! $this->should_display() ) {
			return;
		}

		/**
		 * Fires before the wide banner is rendered.
		 *
		 * @since TBD
		 *
		 * @param string $slug     The slug of the conditional content.
		 * @param object $instance The promotional content instance.
		 */
		do_action( "tec_conditional_content_{$this->slug}", 'wide_banner', $this );

		echo $this->get_wide_banner_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped,StellarWP.XSS.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Gets the content for the narrow banner promo.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	protected function get_narrow_banner_html(): string {
		// Now using get_content() which returns the creative array.
		$creative  = $this->get_content();
		$year      = date_i18n( 'Y' );
		$sale_name = $this->get_sale_name();

		$template_args = [
			'background_color' => $this->get_background_color(),
			'image_src'        => ! empty( $creative['narrow_image_url'] )
				? $creative['narrow_image_url']
				: tribe_resource_url( 'images/conditional-content/' . $this->get_narrow_banner_image(), false, null, \Tribe__Main::instance() ),
			'is_narrow'        => true,
			'is_sidebar'       => false,
			'link'             => ! empty( $creative['link_url'] ) ? $creative['link_url'] : $this->get_link_url(),
			'nonce'            => $this->get_nonce(),
			'sale_name'        => $sale_name,
			'slug'             => $this->get_slug(),
			'year'             => $year,
			'a11y_text'        => ! empty( $creative['alt_text'] ) ? $creative['alt_text'] : sprintf(
				/* translators: %1$s: Sale year (numeric), %2$s: Sale name */
				_x( '%1$s %2$s for The Events Calendar plugins, add-ons and bundles.', 'Alt text for the Sale Ad', 'tribe-common' ),
				$year,
				$sale_name
			),
		];

		return $this->get_template()->template( $this->get_template_slug(), $template_args, false );
	}

	/**
	 * Render the narrow banner HTML.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function render_narrow_banner_html(): void {
		if ( ! $this->should_display() ) {
			return;
		}

		/**
		 * Fires before the narrow banner is rendered.
		 *
		 * @since TBD
		 *
		 * @param string $slug     The slug of the conditional content.
		 * @param object $instance The promotional content instance.
		 */
		do_action( "tec_conditional_content_{$this->slug}", 'narrow_banner', $this );

		echo $this->get_narrow_banner_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped,StellarWP.XSS.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Include the promo in the tickets settings section.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function include_tickets_settings_section(): void {
		if ( ! $this->should_display() ) {
			return;
		}

		$page = tribe_get_request_var( 'page' );
		if ( $page !== 'tec-tickets-settings' ) {
			return;
		}

		$this->render_narrow_banner_html();
	}

	/**
	 * Add sections to sidebar sections array (for filter-based sidebars).
	 *
	 * @since TBD
	 *
	 * @param Settings_Sidebar_Section[] $sections The sidebar sections.
	 *
	 * @return Settings_Sidebar_Section[]
	 */
	public function add_sidebar_sections( $sections ): array {
		if ( ! $this->should_display() ) {
			return $sections;
		}

		// Get the creative content from the filters.
		$creative  = $this->get_content();
		$year      = date_i18n( 'Y' );
		$sale_name = $this->get_sale_name();

		/**
		 * Fires before the settings sidebar is rendered.
		 *
		 * @since TBD
		 *
		 * @param string $slug     The slug of the conditional content.
		 * @param object $instance The promotional content instance.
		 */
		do_action( "tec_conditional_content_{$this->slug}", 'sidebar-filter', $this );

		$translated_title = ! empty( $creative['alt_text'] ) ? $creative['alt_text'] : sprintf(
			/* translators: %1$s: Sale year, %2$s: Sale name */
			esc_attr_x( '%1$s %2$s for The Events Calendar plugins, add-ons and bundles.', 'Alt text for the Sale Ad', 'tribe-common' ),
			esc_attr( $year ),
			esc_attr( $sale_name )
		);

		$container = new Container();

		$button_attr = new Attributes(
			[
				'data-tec-conditional-content-dismiss-button' => true,
				'data-tec-conditional-content-dismiss-slug'   => $this->get_slug(),
				'data-tec-conditional-content-dismiss-nonce'  => $this->get_nonce(),
				'style'                                       => 'position: absolute; top: 0; right: 0; background: transparent; border: 0; color: #fff; padding: 0.5em; cursor: pointer;',
			]
		);
		$button      = new Button( null, $button_attr );
		$button->add_child(
			new Div( new Element_Classes( [ 'dashicons', 'dashicons-dismiss' ] ) )
		);

		$container->add_child( $button );
		$container->add_child(
			new Image(
				! empty( $creative['sidebar_image_url'] )
				? $creative['sidebar_image_url']
				: tribe_resource_url( 'images/conditional-content/' . $this->get_sidebar_image(), false, null, \Tribe__Main::instance() ),
				new Attributes(
					[
						'alt'  => $translated_title,
						'role' => 'presentation',
					]
				)
			)
		);

		// Prepend to sections array.
		array_unshift(
			$sections,
			( new Settings_Section() )
				->add_elements(
					[
						new Link(
							! empty( $creative['link_url'] ) ? $creative['link_url'] : $this->get_link_url(),
							$container,
							null,
							new Attributes(
								[
									'title'                                          => $translated_title,
									'target'                                         => '_blank',
									'rel'                                            => 'noopener nofollow',
									'style'                                          => 'position: relative; display:block;',
									// Dismiss container attributes.
									'data-tec-conditional-content-dismiss-container' => true,
								]
							)
						),
					]
				)
		);

		return $sections;
	}

	/**
	 * Include the promo in the settings sidebar (for object-based sidebars).
	 *
	 * @since TBD
	 *
	 * @param Settings_Sidebar $sidebar Sidebar instance.
	 *
	 * @return void
	 */
	public function include_sidebar_object( &$sidebar ): void {
		$cache = tribe_cache();
		if ( ! empty( $cache[ __METHOD__ ] ) ) {
			return;
		}

		$cache[ __METHOD__ ] = true;

		if ( ! $this->should_display() ) {
			return;
		}

		// Get the creative content from the filters.
		$creative  = $this->get_content();
		$year      = date_i18n( 'Y' );
		$sale_name = $this->get_sale_name();

		/**
		 * Fires before the settings sidebar is rendered.
		 *
		 * @since TBD
		 *
		 * @param string $slug     The slug of the conditional content.
		 * @param object $instance The promotional content instance.
		 */
		do_action( "tec_conditional_content_{$this->slug}", 'sidebar-object', $this );

		$translated_title = ! empty( $creative['alt_text'] ) ? $creative['alt_text'] : sprintf(
			/* translators: %1$s: Sale year, %2$s: Sale name */
			esc_attr_x( '%1$s %2$s for The Events Calendar plugins, add-ons and bundles.', 'Alt text for the Sale Ad', 'tribe-common' ),
			esc_attr( $year ),
			esc_attr( $sale_name )
		);

		$container   = new Container();
		$button_attr = new Attributes(
			[
				'data-tec-conditional-content-dismiss-button' => true,
				'data-tec-conditional-content-dismiss-slug'   => $this->get_slug(),
				'data-tec-conditional-content-dismiss-nonce'  => $this->get_nonce(),
				'style'                                       => 'position: absolute; top: 0; right: 0; background: transparent; border: 0; color: #fff; padding: 0.5em; cursor: pointer;',
			]
		);
		$button      = new Button( null, $button_attr );
		$button->add_child(
			new Div( new Element_Classes( [ 'dashicons', 'dashicons-dismiss' ] ) )
		);

		$container->add_child( $button );
		$container->add_child(
			new Image(
				! empty( $creative['sidebar_image_url'] )
				? $creative['sidebar_image_url']
				: tribe_resource_url( 'images/conditional-content/' . $this->get_sidebar_image(), false, null, \Tribe__Main::instance() ),
				new Attributes(
					[
						'alt'  => $translated_title,
						'role' => 'presentation',
					]
				)
			)
		);

		$sidebar->prepend_section(
			( new Settings_Section() )
				->add_elements(
					[
						new Link(
							! empty( $creative['link_url'] ) ? $creative['link_url'] : $this->get_link_url(),
							$container,
							null,
							new Attributes(
								[
									'title'                                          => $translated_title,
									'target'                                         => '_blank',
									'rel'                                            => 'noopener nofollow',
									'style'                                          => 'position: relative; display:block;',
									// Dismiss container attributes.
									'data-tec-conditional-content-dismiss-container' => true,
								]
							)
						),
					]
				)
		);
	}

	/**
	 * Render sidebar promotional content directly for help hub pages.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function render_sidebar_content(): void {
		if ( ! $this->should_display() ) {
			return;
		}

		// Get the creative content from the filters.
		$creative  = $this->get_content();
		$year      = date_i18n( 'Y' );
		$sale_name = $this->get_sale_name();

		/**
		 * Fires before the sidebar content is rendered.
		 *
		 * @since TBD
		 *
		 * @param string $slug     The slug of the conditional content.
		 * @param object $instance The promotional content instance.
		 */
		do_action( "tec_conditional_content_{$this->slug}", 'help-hub-sidebar', $this );

		$template_args = [
			'background_color' => $this->get_background_color(),
			'image_src'        => ! empty( $creative['sidebar_image_url'] )
				? $creative['sidebar_image_url']
				: tribe_resource_url( 'images/conditional-content/' . $this->get_sidebar_image(), false, null, \Tribe__Main::instance() ),
			'is_narrow'        => false,
			'is_sidebar'       => true,
			'link'             => ! empty( $creative['link_url'] ) ? $creative['link_url'] : $this->get_link_url(),
			'nonce'            => $this->get_nonce(),
			'sale_name'        => $sale_name,
			'slug'             => $this->get_slug(),
			'year'             => $year,
			'a11y_text'        => ! empty( $creative['alt_text'] ) ? $creative['alt_text'] : sprintf(
				/* translators: %1$s: Sale year (numeric), %2$s: Sale name */
				_x( '%1$s %2$s for The Events Calendar plugins, add-ons and bundles.', 'Alt text for the Sale Ad', 'tribe-common' ),
				$year,
				$sale_name
			),
		];

		$this->get_template()->template( $this->get_template_slug(), $template_args, true );
	}

	/**
	 * Gets the instance of the template engine used for rendering the conditional template.
	 *
	 * @since 6.3.0
	 *
	 * @return Template
	 */
	public function get_template(): Template {
		if ( empty( $this->template ) ) {
			$this->template = new Template();
			$this->template->set_template_origin( \Tribe__Main::instance() );
			$this->template->set_template_folder( 'src/admin-views/conditional_content' );
			$this->template->set_template_context_extract( true );
			$this->template->set_template_folder_lookup( false );
		}

		return $this->template;
	}

	/**
	 * Constructor or a dedicated init method should call this.
	 * Discovers which traits are used by the concrete class and registers their hooks.
	 */
	protected function initialize_trait_hooks(): void {
		// Use class_uses( $this ) to get traits used by the concrete class instance itself.
		$traits = class_uses( $this );

		// Register default content creative first, with lowest priority (earliest in chain).
		// This provides the initial array structure for content.
		add_filter( 'promotional_content_get_content', [ $this, 'get_default_promotional_content_array' ], 5, 2 );

		// Iterate through the traits and register their respective hook methods.
		foreach ( $traits as $trait ) {
			switch ( $trait ) {
				case \TEC\Common\Admin\Conditional_Content\Traits\Datetime_Conditional_Trait::class:
					add_filter( 'promotional_content_should_display', [ $this, 'filter_datetime_display_condition' ], 10, 2 );
					break;
				case \TEC\Common\Admin\Conditional_Content\Traits\Plugin_Suite_Conditional_Trait::class:
					// Register the should_display hook for the suite trait.
					add_filter( 'promotional_content_should_display', [ $this, 'is_content_displayable' ], 15, 2 );
					// This filter selects the creative rules based on the suite.
					// It receives a creative array and returns an array of creative *rules*.
					add_filter( 'promotional_content_get_content', [ $this, 'filter_plugin_suite_content_by_suite' ], 20, 2 );
					break;
				case \TEC\Common\Admin\Conditional_Content\Traits\Installed_Plugins_Conditional_Trait::class:
					// This filter receives the creative *rules* (from Plugin_Suite_Conditional_Trait)
					// and returns the *single chosen creative array*.
					add_filter( 'promotional_content_get_content', [ $this, 'filter_installed_plugins_content_condition' ], 25, 2 );
					break;
			}
		}
	}

	/**
	 * Determines if the promotional content should be displayed.
	 * Trait-based conditions filter this.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public function should_display(): bool {
		// If user has dismissed this content, don't display.
		if ( $this->has_user_dismissed() ) {
			return false;
		}

		// If the content should be hidden based on global settings, don't display.
		if ( function_exists( 'tec_should_hide_upsell' ) && tec_should_hide_upsell( $this->get_slug() ) ) {
			return false;
		}

		$should_display = false; // Start with false. It must be explicitly enabled by a trait.

		/**
		 * Filter to determine if the promotional content should be displayed. Includes slug & year.
		 *
		 * @since TBD
		 *
		 * @param bool   $should_display Whether the content should be displayed.
		 * @param object $instance       The promotional content instance.
		 */
		$should_display = apply_filters( "tec_admin_conditional_content_{$this->get_slug()}_should_display", $should_display, $this );

		/**
		 * Filter to determine if the promotional content should be displayed. Includes only slug.
		 *
		 * @since TBD
		 *
		 * @param bool   $should_display Whether the content should be displayed.
		 * @param object $instance       The promotional content instance.
		 */
		$should_display = apply_filters( "tec_admin_conditional_content_{$this->slug}_should_display", $should_display, $this );

		/**
		 * Filter to determine if the promotional content should be displayed.
		 *
		 * @since TBD
		 *
		 * @param bool   $should_display Whether the content should be displayed.
		 * @param object $instance       The promotional content instance.
		 */
		return apply_filters( 'tec_admin_conditional_content_should_display', $should_display, $this );
	}

	/**
	 * Retrieves the promotional content.
	 * Trait-based content modifiers filter this.
	 *
	 * @param array $default_content The initial content array to be filtered.
	 * This is usually empty or a very generic fallback.
	 *
	 * @return array The potentially modified content creative configuration.
	 */
	public function get_content( array $default_content = [] ): array {
		// CRITICAL FIX: Only retrieve content if the ad is supposed to be displayed.
		// This prevents content filters from running and potentially causing TypeErrors
		// when the ad is not active (e.g., due to date, dismissal, or global settings).
		if ( ! $this->should_display() ) {
			return [];
		}

		// The initial value is empty, as the first filter (get_default_promotional_content_array) will provide the base.
		return apply_filters( 'promotional_content_get_content', $default_content, $this );
	}

	// --- Abstract methods required by traits or rendering logic ---

	/**
	 * Determines the current admin suite context (e.g., 'events', 'tickets').
	 * Required by Plugin_Suite_Conditional_Trait.
	 *
	 * @since TBD
	 *
	 * @return string|null 'events', 'tickets', or null if no context could be determined.
	 */
	abstract protected function get_current_admin_suite_context(): ?string;

	/**
	 * Checks if a specific plugin is both active and considered "licensed" by the system.
	 * This method is provided by Installed_Plugins_Conditional_Trait.
	 *
	 * @since TBD
	 *
	 * @param string $plugin_slug The plugin slug to check.
	 *
	 * @return bool True if the plugin is active and licensed, false otherwise.
	 */
	// This method is no longer abstract here. It's provided by Installed_Plugins_Conditional_Trait.
	// If a concrete class doesn't use Installed_Plugins_Conditional_Trait, it won't have this method.
	// Plugin_Suite_Conditional_Trait will have to check for its existence.
	// This is the correct flexible approach.

	/**
	 * Provides the default creative content as an array.
	 * This is the initial value for the 'promotional_content_get_content' filter
	 * if no traits modify it.
	 *
	 * @since TBD
	 *
	 * @param array  $content_from_previous_filters The incoming value.
	 * @param object $instance                      The promotional content instance.
	 *
	 * @return array
	 */
	public function get_default_promotional_content_array( array $content_from_previous_filters, $instance ): array {
		// If content has already been set by a higher priority filter, don't override it.
		// This ensures this is truly the *default* base.
		if ( ! empty( $content_from_previous_filters ) ) {
			return $content_from_previous_filters;
		}

		return [
			'image_url'         => tribe_resource_url( 'images/conditional-content/' . $this->get_wide_banner_image(), false, null, \Tribe__Main::instance() ),
			'narrow_image_url'  => tribe_resource_url( 'images/conditional-content/' . $this->get_narrow_banner_image(), false, null, \Tribe__Main::instance() ),
			'sidebar_image_url' => tribe_resource_url( 'images/conditional-content/' . $this->get_sidebar_image(), false, null, \Tribe__Main::instance() ),
			'link_url'          => $this->get_link_url(),
			'alt_text'          => sprintf(
				/* translators: %1$s: Sale year (numeric), %2$s: Sale name */
				_x( '%1$s %2$s for The Events Calendar plugins, add-ons and bundles.', 'Alt text for the Default Sale Ad', 'tribe-common' ),
				date_i18n( 'Y' ),
				$this->get_sale_name()
			),
		];
	}
}
