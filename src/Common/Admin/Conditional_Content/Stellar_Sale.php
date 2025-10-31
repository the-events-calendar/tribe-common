<?php
/**
 * Stellar Sale Promo Conditional Content.
 *
 * @since 6.8.2
 *
 * @package TEC\Common\Admin\Conditional_Content;
 */

namespace TEC\Common\Admin\Conditional_Content;

use TEC\Common\Admin\Conditional_Content\Traits\{
	Has_Datetime_Conditions,
	Has_Targeted_Creative_Upsell,
	Is_Dismissible,
	Requires_Capability
};
use Tribe\Utils\Date_I18n;

/**
 * Set up for Stellar Sale promo.
 *
 * @since 6.8.2
 * @since 6.9.8 Modified to use the Has_Datetime_Conditions trait instead of extending the Datetime_Conditional_Abstract class.
 * @since 6.9.8 Modified to use the Requires_Capability trait.
 * @since 6.9.8 Modified to use the Has_Targeted_Creative_Upsell trait.
 * @since 6.9.8 Modified to use the Is_Dismissible trait.
 */
class Stellar_Sale extends Promotional_Content_Abstract {
	use Has_Datetime_Conditions {
		get_start_time as get_start_time_from_trait;
		get_end_time as get_end_time_from_trait;
		should_display as should_display_datetime;
	}
	use Is_Dismissible;
	use Requires_Capability;
	use Has_Targeted_Creative_Upsell;

	/**
	 * @inheritdoc
	 *
	 * @var string
	 */
	protected string $slug = 'stellar-sale';

	/**
	 * @inheritdoc
	 *
	 * @var string
	 */
	protected string $start_date = 'July 29th';

	/**
	 * @inheritdoc
	 *
	 * @var string
	 */
	protected string $end_date = 'August 5th';

	/**
	 * @inheritdoc
	 *
	 * @var int
	 */
	protected int $start_time = 4;

	/**
	 * @inheritdoc
	 *
	 * @var int
	 */
	protected int $end_time = 4;

	/**
	 * @inheritdoc
	 *
	 * @var string
	 */
	protected string $background_color = '#1c202f';

	/**
	 * @inheritdoc
	 */
	protected function get_sale_name(): string {
		return __( 'Stellar Sale', 'tribe-common' );
	}

	/**
	 * @inheritdoc
	 */
	public function hook(): void {
		// Register AJAX dismiss handler from Is_Dismissible trait.
		add_action( 'wp_ajax_tec_conditional_content_dismiss', [ $this, 'handle_dismiss' ] );
	}

	/**
	 * @inheritdoc
	 */
	protected function get_link_url(): string {
		return 'https://evnt.is/stellarsale25';
	}

	/**
	 * Override to set time to 4:00 AM UTC.
	 *
	 * @since 6.8.2
	 *
	 * @return ?Date_I18n
	 */
	protected function get_start_time(): ?Date_I18n {
		$date = $this->get_start_time_from_trait();
		if ( null === $date ) {
			return null;
		}

		return $date->setTime( 4, 0 );
	}

	/**
	 * Override to set time to 4:00 AM UTC.
	 *
	 * @since 6.8.2
	 *
	 * @return ?Date_I18n
	 */
	protected function get_end_time(): ?Date_I18n {
		$date = $this->get_end_time_from_trait();
		if ( null === $date ) {
			return null;
		}

		return $date->setTime( 4, 0 );
	}

	/**
	 * @inheritdoc
	 */
	protected function should_display(): bool {
		// Check if hidden by filter.
		if ( tec_should_hide_upsell( $this->get_slug() ) ) {
			return false;
		}

		// Check user capability (from Requires_Capability trait).
		if ( ! $this->check_capability() ) {
			return false;
		}

		// Check if user dismissed (from Is_Dismissible trait).
		if ( $this->has_user_dismissed() ) {
			return false;
		}

		// Check datetime conditions (from Has_Datetime_Conditions trait).
		if ( ! $this->should_display_datetime() ) {
			return false;
		}

		// Don't show if there are no upsell opportunities.
		return $this->has_upsell_opportunity();
	}

	/**
	 * Check if Event Tickets Plus Seating is licensed and active.
	 *
	 * @since 6.8.3
	 *
	 * @return bool Whether Seating is licensed and active.
	 */
	public static function check_seating_license(): bool {
		try {
			$service = tribe( \TEC\Tickets\Seating\Service\Service::class );
		} catch ( \Exception $e ) {
			return false;
		}

		// Get service status from the Seating Service.
		$service_status = $service->get_status();

		// If service status doesn't exist, seating is not active.
		if ( empty( $service_status ) ) {
			return false;
		}

		// Check if the license is valid (not invalid and has a license).
		return ! ( $service_status->has_no_license() || $service_status->is_license_invalid() );
	}

	/**
	 * @inheritdoc
	 */
	protected function get_suite_creative_map(): array {
		return [
			'events'  => [
				'events-calendar-pro/events-calendar-pro.php'                       => [
					'image_url'        => tribe_resource_url( 'images/conditional-content/' . $this->get_slug() . '/ecp-top-wide.png', false, null, \Tribe__Main::instance() ),
					'narrow_image_url' => tribe_resource_url( 'images/conditional-content/' . $this->get_slug() . '/ecp-top-narrow.png', false, null, \Tribe__Main::instance() ),
					'link_url'         => 'https://evnt.is/ecpsale',
					'alt_text'         => sprintf(
						/* translators: %1$s: Sale year (numeric), %2$s: Sale name */
						_x( '%1$s %2$s - Get Events Calendar Pro at 30%% off!', 'Alt text for the Events Pro Sale Ad', 'tribe-common' ),
						date_i18n( 'Y' ),
						$this->get_sale_name()
					),
				],
				'the-events-calendar-filterbar/the-events-calendar-filter-view.php' => [
					'image_url'        => tribe_resource_url( 'images/conditional-content/' . $this->get_slug() . '/fbar-top-wide.png', false, null, \Tribe__Main::instance() ),
					'narrow_image_url' => tribe_resource_url( 'images/conditional-content/' . $this->get_slug() . '/fbar-top-narrow.png', false, null, \Tribe__Main::instance() ),
					'link_url'         => 'https://evnt.is/filtersale',
					'alt_text'         => sprintf(
						/* translators: %1$s: Sale year (numeric), %2$s: Sale name */
						_x( '%1$s %2$s - Get Filter Bar at 30%% off!', 'Alt text for the Filter Bar Sale Ad', 'tribe-common' ),
						date_i18n( 'Y' ),
						$this->get_sale_name()
					),
				],
				'default'                                                           => [
					'image_url'        => tribe_resource_url( 'images/conditional-content/' . $this->get_wide_banner_image(), false, null, \Tribe__Main::instance() ),
					'narrow_image_url' => tribe_resource_url( 'images/conditional-content/' . $this->get_narrow_banner_image(), false, null, \Tribe__Main::instance() ),
					'link_url'         => $this->get_link_url(),
					'alt_text'         => sprintf(
						/* translators: %1$s: Sale year (numeric), %2$s: Sale name */
						_x( '%1$s %2$s for The Events Calendar plugins, add-ons and bundles.', 'Alt text for the Default Sale Ad', 'tribe-common' ),
						date_i18n( 'Y' ),
						$this->get_sale_name()
					),
				],
			],
			'tickets' => [
				'event-tickets-plus/event-tickets-plus.php' => [
					'image_url'         => tribe_resource_url( 'images/conditional-content/' . $this->get_slug() . '/etp-top-wide.png', false, null, \Tribe__Main::instance() ),
					'narrow_image_url'  => tribe_resource_url( 'images/conditional-content/' . $this->get_slug() . '/etp-top-narrow.png', false, null, \Tribe__Main::instance() ),
					'sidebar_image_url' => tribe_resource_url( 'images/conditional-content/' . $this->get_slug() . '/etp-sidebar.png', false, null, \Tribe__Main::instance() ),
					'link_url'          => 'https://evnt.is/ticketsplus',
					'alt_text'          => sprintf(
						/* translators: %1$s: Sale year (numeric), %2$s: Sale name */
						_x( '%1$s %2$s - Get Event Tickets Plus at 30%% off!', 'Alt text for the Event Tickets Plus Sale Ad', 'tribe-common' ),
						date_i18n( 'Y' ),
						$this->get_sale_name()
					),
				],
				'seating-check'                             => [
					'callback'          => [ __CLASS__, 'check_seating_license' ],
					'image_url'         => tribe_resource_url( 'images/conditional-content/' . $this->get_slug() . '/seating-top-wide.png', false, null, \Tribe__Main::instance() ),
					'narrow_image_url'  => tribe_resource_url( 'images/conditional-content/' . $this->get_slug() . '/seating-top-narrow.png', false, null, \Tribe__Main::instance() ),
					'sidebar_image_url' => tribe_resource_url( 'images/conditional-content/' . $this->get_slug() . '/seating-sidebar.png', false, null, \Tribe__Main::instance() ),
					'link_url'          => 'https://evnt.is/seatingsale',
					'alt_text'          => sprintf(
						/* translators: %1$s: Sale year (numeric), %2$s: Sale name */
						_x( '%1$s %2$s - Get Event Seating at 30%% off!', 'Alt text for the Event Seating Sale Ad', 'tribe-common' ),
						date_i18n( 'Y' ),
						$this->get_sale_name()
					),
				],
				'default'                                   => [
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
				],
			],
		];
	}

	/**
	 * Get the alt text for the creative.
	 *
	 * @since 6.9.8
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
			esc_html__(
				'%1$s %2$s for The Events Calendar plugins, add-ons, and bundles.',
				'tribe-common'
			),
			$year,
			$sale_name
		);
	}
}
