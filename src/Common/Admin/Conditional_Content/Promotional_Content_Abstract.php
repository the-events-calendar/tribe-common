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
 * Concrete classes must:
 * - Use ONE of: Has_Generic_Upsell_Opportunity OR Has_Targeted_Creative_Upsell
 * - Use appropriate traits (Has_Datetime_Conditions, Is_Dismissible, Requires_Capability)
 * - Implement should_display() to compose the display logic they need
 *
 * @since 6.8.2
 */
abstract class Promotional_Content_Abstract {
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
	 * Register actions and filters.
	 *
	 * Concrete classes should implement this to hook their specific handlers
	 * (e.g., dismiss handlers from Is_Dismissible trait).
	 *
	 * @since 6.8.2
	 *
	 * @return void
	 */
	abstract public function hook(): void;

	/**
	 * Whether the promotional content is dismissible.
	 * Defaults to false, the Is_Dismissible trait must be used to override this.
	 *
	 * @since 6.9.8
	 *
	 * @return bool
	 */
	public function is_dismissible(): bool {
		return false;
	}

	/**
	 * Whether the promotional content is date bound.
	 * Defaults to false, the Has_Datetime_Conditions trait must be used to override this.
	 *
	 * @since 6.9.8
	 *
	 * @return bool
	 */
	public function is_date_bound() {
		return false;
	}

	/**
	 * Whether the promotional content is targeted.
	 * Defaults to false, the Has_Targeted_Creative_Upsell trait must be used to override this.
	 *
	 * @since 6.9.8
	 *
	 * @return bool
	 */
	public function is_targeted(): bool {
		return false;
	}

	/**
	 * Whether the promotional content requires a capability.
	 * Defaults to false, the Requires_Capability trait must be used to override this.
	 *
	 * @since 6.9.8
	 *
	 * @return bool
	 */
	public function requires_capability(): bool {
		return false;
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
	 * Requires $slug property to be defined in concrete class.
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
	 * Determines if the promotional content should be displayed.
	 *
	 * Concrete classes must implement this to compose their display logic
	 * using checks from traits (e.g., capability, dismissal, datetime, upsell).
	 *
	 * @since 6.8.2
	 *
	 * @return bool Whether the content should display.
	 */
	abstract protected function should_display(): bool;

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
			'sale_name'        => $sale_name,
			'slug'             => $this->get_slug(),
			'year'             => $year,
			'a11y_text'        => $this->get_creative_alt_text(),
		];

		if ( $this->is_dismissible() ) {
			$template_args['nonce'] = $this->get_nonce();
		}

		return $this->get_template()->template( $this->get_template_slug(), $template_args, false );
	}

	/**
	 * Add the dismiss button to the container.
	 *
	 * @since 6.9.8
	 *
	 * @param Container $container The container to add the dismiss button to.
	 *
	 * @return void
	 */
	protected function do_dismiss_button( &$container ): void {
		if ( ! $this->is_dismissible() ) {
			return;
		}

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
			'sale_name'        => $sale_name,
			'slug'             => $this->get_slug(),
			'year'             => $year,
			'a11y_text'        => $this->get_creative_alt_text(),
		];

		if ( $this->is_dismissible() ) {
			$template_args['nonce'] = $this->get_nonce();
		}

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
			'sale_name'        => $sale_name,
			'slug'             => $this->get_slug(),
			'year'             => $year,
			'a11y_text'        => $this->get_creative_alt_text(),
		];

		if ( $this->is_dismissible() ) {
			$template_args['nonce'] = $this->get_nonce();
		}

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
	 * @param Settings_Sidebar           $sidebar  Unused. Sidebar instance.
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

		$container = new Container();
		// Conditionally add the dismiss button.
		$this->do_dismiss_button( $container );

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
									'data-tec-conditional-content-dismiss-container' => $this->is_dismissible(),
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
		$cache_key = 'include_sidebar_object_' . $this->get_slug();
		$cache     = tribe_cache();

		if ( $cache->get( $cache_key ) ) {
			return;
		}

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

		$container = new Container();
		// Conditionally add the dismiss button.
		$this->do_dismiss_button( $container );

		$sidebar->prepend_section(
			( new Settings_Sidebar_Section() )
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
									'data-tec-conditional-content-dismiss-container' => $this->is_dismissible(),
								]
							)
						),
					]
				)
		);

		$cache->set( $cache_key, true, 5 * MINUTE_IN_SECONDS );
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
			'sale_name'        => $sale_name,
			'slug'             => $this->get_slug(),
			'year'             => $year,
			'a11y_text'        => $this->get_creative_alt_text(),
		];

		if ( $this->is_dismissible() ) {
			$template_args['nonce'] = $this->get_nonce();
		}

		$this->get_template()->template( $this->get_template_slug(), $template_args, true );
	}

	/**
	 * Find an image file with automatic format detection (jpg, with png fallback).
	 *
	 * @since 6.9.8
	 *
	 * @param string $base_path The base path without extension (e.g., 'black-friday-2025/top-wide').
	 *
	 * @return string The filename with extension, or the base path with .png if neither format exists.
	 */
	protected function find_image_with_format( string $base_path ): string {
		$base_dir = \Tribe__Main::instance()->plugin_path . 'src/resources/images/conditional-content/';

		// Try .jpg first.
		if ( file_exists( $base_dir . $base_path . '.jpg' ) ) {
			return $base_path . '.jpg';
		}

		// Fall back to .png.
		return $base_path . '.png';
	}

	/**
	 * Get the wide banner image URL.
	 *
	 * @since 6.8.3
	 *
	 * @return string The wide banner image URL.
	 */
	protected function get_wide_banner_image_url(): string {
		$image_path = $this->find_image_with_format( $this->get_slug() . '/top-wide' );
		return tribe_resource_url( 'images/conditional-content/' . $image_path, false, null, \Tribe__Main::instance() );
	}

	/**
	 * Get the narrow banner image URL.
	 *
	 * @since 6.8.3
	 *
	 * @return string The narrow banner image URL.
	 */
	protected function get_narrow_banner_image_url(): string {
		$image_path = $this->find_image_with_format( $this->get_slug() . '/top-narrow' );
		return tribe_resource_url( 'images/conditional-content/' . $image_path, false, null, \Tribe__Main::instance() );
	}

	/**
	 * Get the sidebar image URL.
	 *
	 * @since 6.8.3
	 *
	 * @return string The sidebar image URL.
	 */
	protected function get_sidebar_image_url(): string {
		$image_path = $this->find_image_with_format( $this->get_slug() . '/sidebar' );
		return tribe_resource_url( 'images/conditional-content/' . $image_path, false, null, \Tribe__Main::instance() );
	}

	/**
	 * Get the link URL for the creative.
	 *
	 * @since 6.8.3
	 *
	 * @return string The link URL.
	 */
	protected function get_creative_link_url(): string {
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
