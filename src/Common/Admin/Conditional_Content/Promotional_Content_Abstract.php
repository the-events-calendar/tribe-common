<?php
/**
 * Abstract class for promotional conditional content.
 *
 * @since 6.8.2
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

/**
 * Abstract class for promotional content with banners.
 *
 * @since 6.8.2
 */
abstract class Promotional_Content_Abstract extends Datetime_Conditional_Abstract {
	use Dismissible_Trait;

	/**
	 * Background color for the promotional content.
	 * Must match the background color of the image.
	 *
	 * @since 6.8.2
	 *
	 * @var string
	 */
	protected string $background_color = 'transparent';

	/**
	 * @inheritdoc
	 */
	public function hook(): void {
		// Only hook the AJAX dismiss handler - sidebar integration is handled by Controller.
		add_action( 'wp_ajax_tec_conditional_content_dismiss', [ $this, 'handle_dismiss' ] );
	}

	/**
	 * Get the background color.
	 *
	 * @since 6.8.2
	 *
	 * @return string
	 */
	protected function get_background_color(): string {
		return $this->background_color;
	}

	/**
	 * Sale name for display.
	 *
	 * @since 6.8.2
	 *
	 * @return string
	 */
	abstract protected function get_sale_name(): string;

	/**
	 * Link URL for the promotional content.
	 *
	 * @since 6.8.2
	 *
	 * @return string
	 */
	abstract protected function get_link_url(): string;

	/**
	 * Wide banner image filename.
	 *
	 * @since 6.8.2
	 *
	 * @return string
	 */
	protected function get_wide_banner_image() {
		return $this->get_slug() . '/top-wide.png';
	}

	/**
	 * Narrow banner image filename.
	 *
	 * @since 6.8.2
	 *
	 * @return string
	 */
	protected function get_narrow_banner_image() {
		return $this->get_slug() . '/top-narrow.png';
	}

	/**
	 * Settings sidebar image filename.
	 *
	 * @since 6.8.2
	 *
	 * @return string
	 */
	protected function get_sidebar_image() {
		return $this->get_slug() . '/sidebar.png';
	}

	/**
	 * Get the template slug/filename.
	 *
	 * @since 6.8.2
	 *
	 * @return string
	 */
	protected function get_template_slug(): string {
		return 'conditional-content';
	}

	/**
	 * Get the full slug with year.
	 *
	 * @since 6.8.2
	 *
	 * @return string
	 */
	public function get_slug(): string {
		$year = date_i18n( 'Y' );
		return $this->slug . '-' . $year;
	}

	/**
	 * @inheritdoc
	 */
	protected function get_start_time(): ?Date_I18n {
		$date = parent::get_start_time();
		if ( null === $date ) {
			return null;
		}

		$date = $date->setTime( 4, 0 );

		return $date;
	}

	/**
	 * @inheritdoc
	 */
	protected function get_end_time(): ?Date_I18n {
		$date = parent::get_end_time();
		if ( null === $date ) {
			return null;
		}

		$date = $date->setTime( 4, 0 );

		return $date;
	}

	/**
	 * @inheritdoc
	 */
	protected function should_display(): bool {
		if ( $this->has_user_dismissed() ) {
			return false;
		}

		if ( tec_should_hide_upsell( $this->get_slug() ) ) {
			return false;
		}

		return parent::should_display();
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
		 * @since 6.8.2
		 */
		do_action( 'tec_conditional_content_assets' );

		$this->render_responsive_banner_html();
	}

	/**
	 * Render the responsive banner HTML (combines wide and narrow into one efficient render).
	 *
	 * @since 6.8.2
	 */
	protected function render_responsive_banner_html(): void {
		if ( ! $this->should_display() ) {
			return;
		}

		/**
		 * Fires before the responsive banner is rendered.
		 *
		 * @since 6.8.2
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
	 * @since 6.8.2
	 *
	 * @return string
	 */
	protected function get_responsive_banner_html(): string {
		$year      = date_i18n( 'Y' );
		$sale_name = $this->get_sale_name();

		$template_args = [
			'background_color' => $this->get_background_color(),
			'wide_image_src'   => $this->get_wide_banner_image_url(),
			'narrow_image_src' => $this->get_narrow_banner_image_url(),
			'is_responsive'    => true,
			'is_sidebar'       => false,
			'link'             => $this->get_creative_link_url(),
			'nonce'            => $this->get_nonce(),
			'sale_name'        => $sale_name,
			'slug'             => $this->get_slug(),
			'year'             => $year,
			'a11y_text'        => $this->get_creative_alt_text(),
		];

		return $this->get_template()->template( $this->get_template_slug(), $template_args, false );
	}

	/**
	 * Gets the content for the wide banner promo.
	 *
	 * @since 6.8.2
	 *
	 * @return string
	 */
	protected function get_wide_banner_html(): string {
		$year      = date_i18n( 'Y' );
		$sale_name = $this->get_sale_name();

		$template_args = [
			'background_color' => $this->get_background_color(),
			'image_src'        => $this->get_wide_banner_image_url(),
			'is_narrow'        => false,
			'is_sidebar'       => false,
			'link'             => $this->get_creative_link_url(),
			'nonce'            => $this->get_nonce(),
			'sale_name'        => $sale_name,
			'slug'             => $this->get_slug(),
			'year'             => $year,
			'a11y_text'        => $this->get_creative_alt_text(),
		];

		return $this->get_template()->template( $this->get_template_slug(), $template_args, false );
	}

	/**
	 * Render the wide banner HTML.
	 *
	 * @since 6.8.2
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
		 * @since 6.8.2
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
	 * @since 6.8.2
	 *
	 * @return string
	 */
	protected function get_narrow_banner_html(): string {
		$year      = date_i18n( 'Y' );
		$sale_name = $this->get_sale_name();

		$template_args = [
			'background_color' => $this->get_background_color(),
			'image_src'        => $this->get_narrow_banner_image_url(),
			'is_narrow'        => true,
			'is_sidebar'       => false,
			'link'             => $this->get_creative_link_url(),
			'nonce'            => $this->get_nonce(),
			'sale_name'        => $sale_name,
			'slug'             => $this->get_slug(),
			'year'             => $year,
			'a11y_text'        => $this->get_creative_alt_text(),
		];

		return $this->get_template()->template( $this->get_template_slug(), $template_args, false );
	}

	/**
	 * Render the narrow banner HTML.
	 *
	 * @since 6.8.2
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
		 * @since 6.8.2
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
	 * @since 6.8.2
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
	 * @since 6.8.2
	 *
	 * @param Settings_Sidebar_Section[] $sections The sidebar sections.
	 * @param Settings_Sidebar           $sidebar  Sidebar instance.
	 *
	 * @return Settings_Sidebar_Section[]
	 */
	public function add_sidebar_sections( $sections, $sidebar ): array {
		// Check if the content should currently be displayed.
		if ( ! $this->should_display() ) {
			return $sections;
		}

		$year      = date_i18n( 'Y' );
		$sale_name = $this->get_sale_name();

		/**
		 * Fires before the settings sidebar is rendered.
		 *
		 * @since 6.8.2
		 *
		 * @param string $slug     The slug of the conditional content.
		 * @param object $instance The promotional content instance.
		 */
		do_action( "tec_conditional_content_{$this->slug}", 'sidebar-filter', $this );

		$translated_title = sprintf(
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
				$this->get_sidebar_image_url(),
				new Attributes(
					[
						'alt'  => $this->get_creative_alt_text(),
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
							$this->get_creative_link_url(),
							$container,
							null,
							new Attributes(
								[
									'title'                                          => $this->get_creative_alt_text(),
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
	 * @since 6.8.2
	 *
	 * @param Settings_Sidebar $sidebar Sidebar instance.
	 *
	 * @return void
	 */
	public function include_sidebar_object( $sidebar ): void {
		$cache = tribe_cache();
		if ( ! empty( $cache[ __METHOD__ ] ) ) {
			return;
		}

		$cache[ __METHOD__ ] = true;

		// Check if the content should currently be displayed.
		if ( ! $this->should_display() ) {
			return;
		}

		$year      = date_i18n( 'Y' );
		$sale_name = $this->get_sale_name();

		/**
		 * Fires before the settings sidebar is rendered.
		 *
		 * @since 6.8.2
		 *
		 * @param string $slug     The slug of the conditional content.
		 * @param object $instance The promotional content instance.
		 */
		do_action( "tec_conditional_content_{$this->slug}", 'sidebar-object', $this );

		$translated_title = sprintf(
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
				$this->get_sidebar_image_url(),
				new Attributes(
					[
						'alt'  => $this->get_creative_alt_text(),
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
							$this->get_creative_link_url(),
							$container,
							null,
							new Attributes(
								[
									'title'                                          => $this->get_creative_alt_text(),
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
	 * @since 6.8.2
	 *
	 * @return void
	 */
	public function render_sidebar_content(): void {
		// Check if the content should currently be displayed.
		if ( ! $this->should_display() ) {
			return;
		}

		$year      = date_i18n( 'Y' );
		$sale_name = $this->get_sale_name();

		/**
		 * Fires before the sidebar content is rendered.
		 *
		 * @since 6.8.2
		 *
		 * @param string $slug     The slug of the conditional content.
		 * @param object $instance The promotional content instance.
		 */
		do_action( "tec_conditional_content_{$this->slug}", 'help-hub-sidebar', $this );

		$template_args = [
			'background_color' => $this->get_background_color(),
			'image_src'        => $this->get_sidebar_image_url(),
			'is_narrow'        => false,
			'is_sidebar'       => true,
			'link'             => $this->get_creative_link_url(),
			'nonce'            => $this->get_nonce(),
			'sale_name'        => $sale_name,
			'slug'             => $this->get_slug(),
			'year'             => $year,
			'a11y_text'        => $this->get_creative_alt_text(),
		];

		$this->get_template()->template( $this->get_template_slug(), $template_args, true );
	}

	/**
	 * Get the suite creative map.
	 *
	 * The creative map should be structured as follows:
	 *
	 * [
	 *   'context' => [
	 *     'plugin/path.php' => [
	 *       'image_url' => '...',
	 *       'narrow_image_url' => '...',
	 *       'link_url' => '...',
	 *       'alt_text' => '...',
	 *     ],
	 *     'feature-check' => [
	 *       'callback' => [ 'Class', 'method' ], // Callback to determine if feature is active
	 *       'image_url' => '...',
	 *       'narrow_image_url' => '...',
	 *       'link_url' => '...',
	 *       'alt_text' => '...',
	 *     ],
	 *     'default' => [ ... ] // Fallback creative
	 *   ],
	 * ]
	 *
	 * @since 6.8.3
	 *
	 * @return array The suite creative map.
	 */
	abstract protected function get_suite_creative_map(): array;

	/**
	 * Determine the admin page context.
	 *
	 * @since 6.8.3
	 *
	 * @return string The admin page context ('tickets', 'events', or 'default').
	 */
	protected function get_admin_page_context(): string {
		$admin_pages = tribe( 'admin.pages' );
		$admin_page  = $admin_pages->get_current_page();

		// If no admin page is detected, use default context.
		if ( empty( $admin_page ) ) {
			return 'default';
		}

		// Check if we're on a tickets admin page.
		if ( strpos( $admin_page, 'tec-tickets' ) !== false || strpos( $admin_page, 'tickets_page_' ) !== false ) {
			return 'tickets';
		}

		// Check if we're on an events admin page.
		if ( strpos( $admin_page, 'tribe_events' ) !== false || strpos( $admin_page, 'events_page_' ) !== false ) {
			return 'events';
		}

		// Check if we're on a general TEC admin page.
		if ( strpos( $admin_page, 'tec-' ) !== false ) {
			return 'events';
		}

		return 'default';
	}

	/**
	 * Get the selected creative based on admin page context and installed plugins.
	 *
	 * @since 6.8.3
	 *
	 * @return array|null The selected creative array or null if none found.
	 */
	protected function get_selected_creative(): ?array {
		$creative_map = $this->get_suite_creative_map();
		$context      = $this->get_admin_page_context();

		// If no creative map is available, return null.
		if ( empty( $creative_map ) ) {
			return null;
		}

		// Check if the context exists in the creative map.
		if ( ! isset( $creative_map[ $context ] ) ) {
			// Fall back to default if context not found.
			$context = 'default';
			if ( ! isset( $creative_map[ $context ] ) ) {
				return null;
			}
		}

		$context_creatives = $creative_map[ $context ];

		// Iterate through the creatives and find the first plugin that is not installed or where the callback returns false.
		foreach ( $context_creatives as $plugin_path => $creative ) {
			// Skip the default entry for now.
			if ( 'default' === $plugin_path ) {
				continue;
			}

			// Check if we have a callback for plugin detection.
			if ( isset( $creative['callback'] ) && is_callable( $creative['callback'] ) ) {
				// Execute the callback to determine if the plugin or feature is active.
				$is_active = call_user_func( $creative['callback'] );

				// If the callback returns false (feature not active), use this creative.
				if ( ! $is_active ) {
					return $creative;
				}
			} elseif ( ! is_plugin_active( $plugin_path ) ) {
				return $creative;
			}
		}

		// If all plugins are installed, use the default creative.
		if ( isset( $context_creatives['default'] ) ) {
			return $context_creatives['default'];
		}

		return null;
	}

	/**
	 * Get the wide banner image URL.
	 *
	 * @since 6.8.3
	 *
	 * @return string The wide banner image URL.
	 */
	protected function get_wide_banner_image_url(): string {
		$creative = $this->get_selected_creative();

		if ( ! empty( $creative['image_url'] ) ) {
			return $creative['image_url'];
		}

		// Fallback to default behavior.
		return tribe_resource_url( 'images/conditional-content/' . $this->get_wide_banner_image(), false, null, \Tribe__Main::instance() );
	}

	/**
	 * Get the narrow banner image URL.
	 *
	 * @since 6.8.3
	 *
	 * @return string The narrow banner image URL.
	 */
	protected function get_narrow_banner_image_url(): string {
		$creative = $this->get_selected_creative();

		if ( ! empty( $creative['narrow_image_url'] ) ) {
			return $creative['narrow_image_url'];
		}

		// Fallback to default behavior.
		return tribe_resource_url( 'images/conditional-content/' . $this->get_narrow_banner_image(), false, null, \Tribe__Main::instance() );
	}

	/**
	 * Get the sidebar image URL.
	 *
	 * @since 6.8.3
	 *
	 * @return string The sidebar image URL.
	 */
	protected function get_sidebar_image_url(): string {
		$creative = $this->get_selected_creative();

		if ( ! empty( $creative['sidebar_image_url'] ) ) {
			return $creative['sidebar_image_url'];
		}

		// Fallback to default behavior.
		return tribe_resource_url( 'images/conditional-content/' . $this->get_sidebar_image(), false, null, \Tribe__Main::instance() );
	}

	/**
	 * Get the link URL for the creative.
	 *
	 * @since 6.8.3
	 *
	 * @return string The link URL.
	 */
	protected function get_creative_link_url(): string {
		$creative = $this->get_selected_creative();

		if ( ! empty( $creative['link_url'] ) ) {
			return $creative['link_url'];
		}

		// Fallback to default behavior.
		return $this->get_link_url();
	}

	/**
	 * Get the alt text for the creative.
	 *
	 * @since 6.8.3
	 *
	 * @return string The alt text.
	 */
	protected function get_creative_alt_text(): string {
		$creative = $this->get_selected_creative();

		if ( ! empty( $creative['alt_text'] ) ) {
			return $creative['alt_text'];
		}

		// Fallback to default behavior.
		$year      = date_i18n( 'Y' );
		$sale_name = $this->get_sale_name();

		return sprintf(
			/* translators: %1$s: Sale year (numeric), %2$s: Sale name */
			_x( '%1$s %2$s for The Events Calendar plugins, add-ons and bundles.', 'Alt text for the Sale Ad', 'tribe-common' ),
			$year,
			$sale_name
		);
	}
}
