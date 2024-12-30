<?php

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
 * Set up for Black Friday promo.
 *
 * @since 6.3.0
 */
class Black_Friday extends Datetime_Conditional_Abstract {
	use Dismissible_Trait;

	/**
	 * @inheritdoc
	 */
	protected string $slug = 'black-friday-2024';

	/**
	 * @inheritdoc
	 */
	protected string $start_date = 'November 26th';

	/**
	 * @inheritdoc
	 */
	protected string $end_date = 'December 3rd';

	/**
	 * @inheritdoc
	 */
	public function hook(): void {
		add_action( 'tec_settings_sidebar_start', [ $this, 'include_sidebar_section' ] );
		add_action( 'tribe_settings_below_tabs', [ $this, 'include_tickets_settings_section' ] );
		add_action( 'wp_ajax_tec_conditional_content_dismiss', [ $this, 'handle_dismiss' ] );
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

		if ( tec_should_hide_upsell( $this->slug ) ) {
			return false;
		}

		return parent::should_display();
	}

	/**
	 * Gets the content for the Black Friday promo.
	 *
	 * @since 6.3.0
	 *
	 * @return string
	 */
	protected function get_wide_banner_html(): string {
		$template_args = [
			'image_src' => tribe_resource_url( 'images/hero-section-wide.jpg', false, null, \Tribe__Main::instance() ),
			'link'      => 'https://evnt.is/tec-bf-2024',
			'nonce'     => $this->get_nonce(),
			'slug'      => $this->slug,
		];

		return $this->get_template()->template( 'black-friday', $template_args, false );
	}

	/**
	 * Render the wide banner HTML.
	 *
	 * @since 6.3.0
	 *
	 * @return void
	 */
	public function render_wide_banner_html(): void {
		if ( ! $this->should_display() ) {
			return;
		}

		/**
		 * Fires before the wide banner is rendered.
		 * This hook is used to add additional content before the narrow banner.
		 *
		 * @since 6.3.0
		 *
		 * @param string       $slug The slug of the conditional content.
		 * @param Black_Friday $this The Black Friday instance.
		 */
		do_action( 'tec_conditional_content_black_friday', 'wide_banner', $this );

		echo $this->get_wide_banner_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped,StellarWP.XSS.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Gets the content for the Black Friday promo.
	 *
	 * @since 6.3.0
	 *
	 * @return string
	 */
	protected function get_narrow_banner_html(): string {
		$template_args = [
			'image_src' => tribe_resource_url( 'images/hero-section-narrow.jpg', false, null, \Tribe__Main::instance() ),
			'link'      => 'https://evnt.is/tec-bf-2024',
			'is_narrow' => true,
			'nonce'     => $this->get_nonce(),
			'slug'      => $this->slug,
		];

		return $this->get_template()->template( 'black-friday', $template_args, false );
	}

	/**
	 * Render the narrow banner HTML.
	 *
	 * @since 6.3.0
	 *
	 * @return void
	 */
	public function render_narrow_banner_html(): void {
		if ( ! $this->should_display() ) {
			return;
		}
		/**
		 * Fires before the narrow banner is rendered.
		 * This hook is used to add additional content before the narrow banner.
		 *
		 * @since 6.3.0
		 *
		 * @param string       $slug The slug of the conditional content.
		 * @param Black_Friday $this The Black Friday instance.
		 */
		do_action( 'tec_conditional_content_black_friday', 'narrow_banner', $this );

		echo $this->get_narrow_banner_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped,StellarWP.XSS.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Include the Black Friday promo in the tickets settings section.
	 *
	 * @since 6.3.0
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
	 * Replace the opening markup for the general settings info box.
	 *
	 * @since 6.3.0
	 *
	 * @param Settings_Sidebar $sidebar Sidebar instance.
	 *
	 * @return void
	 */
	public function include_sidebar_section( $sidebar ): void {
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

		/**
		 * Fires before the settings sidebar is rendered.
		 * This hook is used to add additional content before the narrow banner.
		 *
		 * @since 6.3.0
		 *
		 * @param string       $slug The slug of the conditional content.
		 * @param Black_Friday $this The Black Friday instance.
		 */
		do_action( 'tec_conditional_content_black_friday', 'settings-sidebar', $this );

		$translated_title = sprintf(
			/* translators: %1$s: Black Friday year */
			esc_attr_x( '%1$s Black Friday Sale for The Events Calendar plugins, add-ons and bundles.', 'Alt text for the Black Friday Ad', 'tribe-common' ),
			esc_attr( $year )
		);

		$container = new Container();

		$button_attr = new Attributes(
			[
				'style'                                       => 'position: absolute; top: 0; right: 0; background: transparent; border: 0; color: #fff; padding: 0.5em; cursor: pointer;',

				// Dismiss button attributes.
				'data-tec-conditional-content-dismiss-button' => true,
				'data-tec-conditional-content-dismiss-slug'   => $this->slug,
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
				tribe_resource_url( 'images/hero-section-settings-sidebar.jpg', false, null, \Tribe__Main::instance() ),
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
							'https://evnt.is/tec-bf-2024',
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
}
