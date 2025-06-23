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
	Settings_Sidebar,
	Settings_Section
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
abstract class Promotional_Content_Abstract extends Datetime_Conditional_Abstract {
	use Dismissible_Trait;

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
		// Only hook the AJAX dismiss handler - sidebar integration is handled by Controller
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
		return $this->slug . '/hero-section-wide.jpg';
	}

	/**
	 * Narrow banner image filename.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	protected function get_narrow_banner_image() {
		return $this->slug . '/hero-section-narrow.jpg';
	}

	/**
	 * Settings sidebar image filename.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	protected function get_sidebar_image() {
		return $this->slug . '/hero-section-settings-sidebar.jpg';
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
		$year = date_i18n( 'Y' );
		$sale_name = $this->get_sale_name();

		$template_args = [
			'background_color' => $this->get_background_color(),
			'wide_image_src'   => tribe_resource_url( 'images/conditional-content/' . $this->get_wide_banner_image(), false, null, \Tribe__Main::instance() ),
			'narrow_image_src' => tribe_resource_url( 'images/conditional-content/' . $this->get_narrow_banner_image(), false, null, \Tribe__Main::instance() ),
			'is_responsive'    => true,
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
	 * Gets the content for the wide banner promo.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	protected function get_wide_banner_html(): string {
		$year = date_i18n( 'Y' );
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
		$year = date_i18n( 'Y' );
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
	 * Include the promo in the settings sidebar.
	 *
	 * @since TBD
	 *
	 * @param Section[]        $sections The sidebar sections.
	 * @param Settings_Sidebar $sidebar Sidebar instance.
	 *
	 * @return void
	 */
	public function include_sidebar_section( $sections, $sidebar ): void {
		$cache = tribe_cache();
		if ( $cache[ __METHOD__ ] ) {
			return;
		}

		$cache[ __METHOD__ ] = true;

		// Check if the content should currently be displayed.
		if ( ! $this->should_display() ) {
			return;
		}

		$year = date_i18n( 'Y' );
		$sale_name = $this->get_sale_name();

		/**
		 * Fires before the settings sidebar is rendered.
		 *
		 * @since TBD
		 *
		 * @param string $slug     The slug of the conditional content.
		 * @param object $instance The promotional content instance.
		 */
		do_action( "tec_conditional_content_{$this->slug}", 'settings-sidebar', $this );

		$translated_title = sprintf(
			/* translators: %1$s: Sale year, %2$s: Sale name */
			esc_attr_x( '%1$s %2$s for The Events Calendar plugins, add-ons and bundles.', 'Alt text for the Sale Ad', 'tribe-common' ),
			esc_attr( $year ),
			esc_attr( $sale_name )
		);

		$container = new Container();

		$button_attr = new Attributes(
			[
				'style'                                       => 'position: absolute; top: 0; right: 0; background: transparent; border: 0; color: #fff; padding: 0.5em; cursor: pointer;',
				'data-tec-conditional-content-dismiss-button' => true,
				'data-tec-conditional-content-dismiss-slug'   => $this->get_slug(),
				'data-tec-conditional-content-dismiss-nonce'  => $this->get_nonce(),
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

		$sections[] = (
			( new Settings_Section() )
				->add_elements(
					[
						new Link(
							$this->get_link_url(),
							$container,
							null,
							new Attributes(
								[
									'title'  => $translated_title,
									'target' => '_blank',
									'rel'    => 'noopener nofollow',
									'style'  => 'position: relative; display:block;',

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
	 * Add sections to sidebar sections array (for filter-based sidebars).
	 *
	 * @since TBD
	 *
	 * @param Section[] $sections The sidebar sections.
	 * @param Settings_Sidebar $sidebar Sidebar instance.
	 *
	 * @return Section[]
	 */
	public function add_sidebar_sections( $sections, $sidebar ): array {
		// Check if the content should currently be displayed.
		if ( ! $this->should_display() ) {
			return $sections;
		}

		$year = date_i18n( 'Y' );
		$sale_name = $this->get_sale_name();

		/**
		 * Fires before the settings sidebar is rendered.
		 *
		 * @since TBD
		 *
		 * @param string $slug     The slug of the conditional content.
		 * @param object $instance The promotional content instance.
		 */
		do_action( "tec_conditional_content_{$this->slug}", 'settings-sidebar-filter', $this );

		$translated_title = sprintf(
			/* translators: %1$s: Sale year, %2$s: Sale name */
			esc_attr_x( '%1$s %2$s for The Events Calendar plugins, add-ons and bundles.', 'Alt text for the Sale Ad', 'tribe-common' ),
			esc_attr( $year ),
			esc_attr( $sale_name )
		);

		$container = new Container();

		$button_attr = new Attributes(
			[
				'style'                                       => 'position: absolute; top: 0; right: 0; background: transparent; border: 0; color: #fff; padding: 0.5em; cursor: pointer;',
				'data-tec-conditional-content-dismiss-button' => true,
				'data-tec-conditional-content-dismiss-slug'   => $this->get_slug(),
				'data-tec-conditional-content-dismiss-nonce'  => $this->get_nonce(),
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

		// Prepend to sections array
		array_unshift( $sections,
			( new Settings_Section() )
				->add_elements(
					[
						new Link(
							$this->get_link_url(),
							$container,
							null,
							new Attributes(
								[
									'title'  => $translated_title,
									'target' => '_blank',
									'rel'    => 'noopener nofollow',
									'style'  => 'position: relative; display:block;',

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
	public function include_sidebar_object( $sidebar ): void {
		$cache = tribe_cache();
		if ( $cache[ __METHOD__ ] ) {
			return;
		}

		$cache[ __METHOD__ ] = true;

		// Check if the content should currently be displayed.
		if ( ! $this->should_display() ) {
			return;
		}

		$year = date_i18n( 'Y' );
		$sale_name = $this->get_sale_name();

		/**
		 * Fires before the settings sidebar is rendered.
		 *
		 * @since TBD
		 *
		 * @param string $slug     The slug of the conditional content.
		 * @param object $instance The promotional content instance.
		 */
		do_action( "tec_conditional_content_{$this->slug}", 'settings-sidebar-object', $this );

		$translated_title = sprintf(
			/* translators: %1$s: Sale year, %2$s: Sale name */
			esc_attr_x( '%1$s %2$s for The Events Calendar plugins, add-ons and bundles.', 'Alt text for the Sale Ad', 'tribe-common' ),
			esc_attr( $year ),
			esc_attr( $sale_name )
		);

		$container = new Container();

		$button_attr = new Attributes(
			[
				'style'                                       => 'position: absolute; top: 0; right: 0; background: transparent; border: 0; color: #fff; padding: 0.5em; cursor: pointer;',
				'data-tec-conditional-content-dismiss-button' => true,
				'data-tec-conditional-content-dismiss-slug'   => $this->get_slug(),
				'data-tec-conditional-content-dismiss-nonce'  => $this->get_nonce(),
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
									'title'  => $translated_title,
									'target' => '_blank',
									'rel'    => 'noopener nofollow',
									'style'  => 'position: relative; display:block;',

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
		if ( ! $this->should_display() ) {
			return;
		}

		$year = date_i18n( 'Y' );
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

		echo $this->get_template()->template( $this->get_template_slug(), $template_args, false ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped,StellarWP.XSS.EscapeOutput.OutputNotEscaped
	}
}
