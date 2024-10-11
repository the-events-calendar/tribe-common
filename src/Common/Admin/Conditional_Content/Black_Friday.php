<?php

namespace TEC\Common\Admin\Conditional_Content;

use TEC\Common\Admin\Settings_Sidebar;
use Tribe\Utils\Element_Attributes as Attributes;
use TEC\Common\Admin\Entities\Link;
use TEC\Common\Admin\Entities\Image;
use TEC\Common\Admin\Settings_Section;
use Tribe\Utils\Date_I18n;

/**
 * Set up for Black Friday promo.
 *
 * @since TBD
 */
class Black_Friday extends Datetime_Conditional_Abstract {
	/**
	 * @inheritdoc
	 */
	protected string $slug = 'black_friday';

	/**
	 * @inheritdoc
	 */
	protected string $start_date = 'fourth Thursday of November';

	/**
	 * @inheritdoc
	 */
	protected string $end_date = 'November 30th';

	/**
	 * @inheritdoc
	 */
	public function hook(): void {
		add_action( 'tec_settings_sidebar_start', [ $this, 'include_sidebar_section' ] );
		add_action( 'tribe_settings_below_tabs', [ $this, 'include_tickets_settings_section' ] );
	}

	/**
	 * @inheritdoc
	 */
	protected function get_start_time(): ?Date_I18n {
		$date = parent::get_start_time();
		if ( null === $date ) {
			return null;
		}

		$date = $date->modify( '-3 days' );

		return $date;
	}

	/**
	 * @inheritdoc
	 */
	protected function should_display(): bool {
		return true; // Here to enable QA to test this easier.

		if ( tec_should_hide_upsell( 'black-friday' ) ) {
			return false;
		}

		return parent::should_display();
	}

	/**
	 * Gets the content for the Black Friday promo.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	protected function get_wide_banner_html(): string {
		$template_args = [
			'image_src' => tribe_resource_url( 'images/hero-section-wide.jpg', false, null, \Tribe__Main::instance() ),
			'link'      => 'https://evnt.is/tec-bf-2024',
		];

		return $this->get_template()->template( 'black-friday', $template_args, false );
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
		echo $this->get_wide_banner_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped,StellarWP.XSS.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Gets the content for the Black Friday promo.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	protected function get_narrow_banner_html(): string {
		$template_args = [
			'image_src' => tribe_resource_url( 'images/hero-section-narrow.jpg', false, null, \Tribe__Main::instance() ),
			'link'      => 'https://evnt.is/tec-bf-2024',
			'is_narrow' => true,
		];

		return $this->get_template()->template( 'black-friday', $template_args, false );
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
		echo $this->get_narrow_banner_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped,StellarWP.XSS.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Include the Black Friday promo in the tickets settings section.
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
	 * Replace the opening markup for the general settings info box.
	 *
	 * @since TBD
	 *
	 * @param Settings_Sidebar $sidebar Sidebar instance.
	 *
	 * @return void
	 */
	public function include_sidebar_section( $sidebar ): void {
		// Check if the content should currently be displayed.
		if ( ! $this->should_display() ) {
			return;
		}

		$year = date_i18n( 'Y' );

		$sidebar->prepend_section(
			( new Settings_Section() )
				->add_elements(
					[
						new Link(
							'https://evnt.is/tec-bf-2024',
							new Image(
								tribe_resource_url( 'images/hero-section-settings-sidebar.jpg', false, null, \Tribe__Main::instance() ),
								new Attributes(
									[
										/* translators: %1$s: Black Friday year */
										'alt'  => sprintf( esc_attr_x( '%1$s Black Friday Sale for The Events Calendar plugins, add-ons and bundles.', 'Alt text for the Black Friday Ad', 'tribe-common' ), esc_attr( $year ) ),
										'role' => 'presentation',
									]
								)
							),
							null,
							new Attributes(
								[
									/* translators: %1$s: Black Friday year */
									'title'  => sprintf( esc_attr_x( '%1$s Black Friday Sale for The Events Calendar plugins, add-ons and bundles.', 'Alt text for the Black Friday Ad', 'tribe-common' ), esc_attr( $year ) ),
									'target' => '_blank',
									'rel'    => 'noopener nofollow',
								]
							)
						),
					]
				)
		);
	}
}
