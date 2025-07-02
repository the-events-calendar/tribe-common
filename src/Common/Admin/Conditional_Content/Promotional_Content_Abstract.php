<?php
/**
 * Abstract class for promotional conditional content.
 *
 * @since TBD
 *
 * @package TEC\Common\Admin\Conditional_Content;
 */

namespace TEC\Common\Admin\Conditional_Content;

use TEC\Common\Admin\Conditional_Content\Traits\Datetime_Conditional_Trait;
use TEC\Common\Admin\Conditional_Content\Traits\Plugin_Suite_Conditional_Trait;
use TEC\Common\Admin\Conditional_Content\Traits\Installed_Plugins_Conditional_Trait;

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
 * @since TBD
 */
abstract class Promotional_Content_Abstract {
	use Dismissible_Trait;
	use Datetime_Conditional_Trait;
	use Plugin_Suite_Conditional_Trait;
	use Installed_Plugins_Conditional_Trait;

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
	 * @inheritdoc
	 */
	public function hook(): void {
		// Only hook the AJAX dismiss handler - sidebar integration is handled by Controller.
		add_action( 'wp_ajax_tec_conditional_content_dismiss', [ $this, 'handle_dismiss' ] );
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
	 * Determines if the content is displayable based on date validity and suite context.
	 *
	 * @since TBD
	 *
	 * @return bool Whether the content is displayable
	 */
	public function is_content_displayable(): bool {
		// 1. Check date validity (from Datetime_Conditional_Trait).
		if ( ! $this->is_date_valid() ) {
			return false;
		}

		// 2. Determine the current active admin suite context (from Plugin_Suite_Conditional_Trait).
		$current_suite_context = $this->get_current_admin_suite_context();

		// 3. If no suite context, or if the current context is not targeted by this ad, don't display.
		$target_suites = $this->get_target_plugin_suites();
		if ( is_null( $current_suite_context ) || ! in_array( $current_suite_context, $target_suites, true ) ) {
			return false;
		}

		// If user has dismissed this content, don't display.
		if ( $this->has_user_dismissed() ) {
			return false;
		}

		// If the content should be hidden based on global settings, don't display.
		if ( function_exists( 'tec_should_hide_upsell' ) && tec_should_hide_upsell( $this->get_slug() ) ) {
			return false;
		}

		// If date is valid and suite context matches, the ad can be displayed.
		return true;
	}

	/**
	 * Get the appropriate creative content for the current context.
	 *
	 * @since TBD
	 *
	 * @return array Creative content configuration
	 */
	protected function get_current_ad_creative(): array {
		// 1. Determine the current active admin suite context.
		$current_suite_context = $this->get_current_admin_suite_context();
		$suite_creative_map    = $this->get_suite_creative_map();

		// 2. If no context or no map for this context, return empty
		if ( is_null( $current_suite_context ) || ! isset( $suite_creative_map[ $current_suite_context ] ) ) {
			return []; // Return an empty array as a safe fallback.
		}

		$creative_rules = $suite_creative_map[ $current_suite_context ];

		// 3. Iterate through rules. Order matters for prioritization.
		foreach ( $creative_rules as $plugin_slug_or_default_key => $creative_details ) {
			if ( 'default' === $plugin_slug_or_default_key ) {
				// Default should only be considered after specific plugin conditions.
				continue;
			}

			// If plugin is NOT active and licensed, this is the upsell opportunity.
			if ( ! $this->is_plugin_active_and_licensed( $plugin_slug_or_default_key ) ) {
				return $creative_details;
			}
		}

		// 4. If no specific plugin condition was met, return the default creative for this suite
		if ( isset( $creative_rules['default'] ) ) {
			return $creative_rules['default'];
		}

		return []; // Fallback if no rules or default found for the current suite.
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
		if ( ! $this->is_content_displayable() ) {
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

		$creative = $this->get_current_ad_creative();

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
		$year      = date_i18n( 'Y' );
		$sale_name = $this->get_sale_name();

		$template_args = [
			'background_color' => $this->get_background_color(),
			'image_src'        => tribe_resource_url( 'images/conditional-content/' . $this->get_wide_banner_image(), false, null, \Tribe__Main::instance() ),
			'is_narrow'        => false,
			'is_sidebar'       => false,
			'link'             => $this->get_link_url(),
			'nonce'            => $this->get_nonce(),
			'sale_name'        => $sale_name,
			'slug'             => $this->get_slug(),
			'year'             => $year,
			'a11y_text'        => sprintf(
				/* translators: %1$s: Sale year (numeric), %2$s: Sale name */
				_x( '%1$s %2$s for The Events Calendar plugins, add-ons and bundles.', 'Alt text for the Sale Ad', 'tribe-common' ),
				$year,
				$sale_name
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
		if ( ! $this->is_content_displayable() ) {
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
		$year      = date_i18n( 'Y' );
		$sale_name = $this->get_sale_name();

		$template_args = [
			'background_color' => $this->get_background_color(),
			'image_src'        => tribe_resource_url( 'images/conditional-content/' . $this->get_narrow_banner_image(), false, null, \Tribe__Main::instance() ),
			'is_narrow'        => true,
			'is_sidebar'       => false,
			'link'             => $this->get_link_url(),
			'nonce'            => $this->get_nonce(),
			'sale_name'        => $sale_name,
			'slug'             => $this->get_slug(),
			'year'             => $year,
			'a11y_text'        => sprintf(
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
		if ( ! $this->is_content_displayable() ) {
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
		if ( ! $this->is_content_displayable() ) {
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
	 * @param Settings_Sidebar           $sidebar  Sidebar instance.
	 *
	 * @return Settings_Sidebar_Section[]
	 */
	public function add_sidebar_sections( $sections, $sidebar ): array {
		// Check if the content should currently be displayed.
		if ( ! $this->is_content_displayable() ) {
			return $sections;
		}

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
				tribe_resource_url( 'images/conditional-content/' . $this->get_sidebar_image(), false, null, \Tribe__Main::instance() ),
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
							$this->get_link_url(),
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

		// Check if the content should currently be displayed.
		if ( ! $this->is_content_displayable() ) {
			return;
		}

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
				tribe_resource_url( 'images/conditional-content/' . $this->get_sidebar_image(), false, null, \Tribe__Main::instance() ),
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
							$this->get_link_url(),
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
		// Check if the content should currently be displayed.
		if ( ! $this->is_content_displayable() ) {
			return;
		}

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
			'image_src'        => tribe_resource_url( 'images/conditional-content/' . $this->get_sidebar_image(), false, null, \Tribe__Main::instance() ),
			'is_narrow'        => false,
			'is_sidebar'       => true,
			'link'             => $this->get_link_url(),
			'nonce'            => $this->get_nonce(),
			'sale_name'        => $sale_name,
			'slug'             => $this->get_slug(),
			'year'             => $year,
			'a11y_text'        => sprintf(
				/* translators: %1$s: Sale year (numeric), %2$s: Sale name */
				_x( '%1$s %2$s for The Events Calendar plugins, add-ons and bundles.', 'Alt text for the Sale Ad', 'tribe-common' ),
				$year,
				$sale_name
			),
		];

		$this->get_template()->template( $this->get_template_slug(), $template_args, true );
	}
}
