<?php
/**
 * Black Friday Promo Conditional Content.
 *
 * @since 6.3.0
 *
 * @package TEC\Common\Admin\Conditional_Content;
 */

namespace TEC\Common\Admin\Conditional_Content;

/**
 * Set up for Black Friday promo.
 *
 * @since 6.3.0
 */
class Black_Friday extends Promotional_Content_Abstract {

	/**
	 * @inheritdoc
	 *
	 * @var string
	 */
	protected string $slug = 'black-friday';

	/**
	 * @inheritdoc
	 *
	 * @var string
	 */
	protected string $start_date = 'November 26th';

	/**
	 * @inheritdoc
	 *
	 * @var string
	 */
	protected string $end_date = 'December 3rd';

	/**
	 * Background color for the promotional content.
	 * Must match the background color of the image.
	 *
	 * @since 6.8.2
	 *
	 * @var string
	 */
	protected string $background_color = '#000';

	/**
	 * @inheritdoc
	 */
	protected function get_sale_name(): string {
		return __( 'Black Friday Sale', 'tribe-common' );
	}

	/**
	 * @inheritdoc
	 */
	protected function get_link_url(): string {
		return 'https://evnt.is/tec-bf-2024';
	}

	/**
	 * @inheritdoc
	 */
	protected function get_suite_creative_map(): array {
		return [
			'events'  => [
				'events-pro/events-pro.php'                            => [
					'image_url'        => tribe_resource_url( 'images/conditional-content/' . $this->get_slug() . '/ecp-top-wide.png', false, null, \Tribe__Main::instance() ),
					'narrow_image_url' => tribe_resource_url( 'images/conditional-content/' . $this->get_slug() . '/ecp-top-narrow.png', false, null, \Tribe__Main::instance() ),
					'link_url'         => 'https://evnt.is/tec-bf-pro-2024',
					'alt_text'         => sprintf(
						/* translators: %1$s: Sale year (numeric), %2$s: Sale name */
						_x( '%1$s %2$s - Get Events Calendar Pro at 30%% off!', 'Alt text for the Events Pro Sale Ad', 'tribe-common' ),
						date_i18n( 'Y' ),
						$this->get_sale_name()
					),
				],
				'events-filterbar/the-events-calendar-filter-view.php' => [
					'image_url'        => tribe_resource_url( 'images/conditional-content/' . $this->get_slug() . '/fb-top-wide.png', false, null, \Tribe__Main::instance() ),
					'narrow_image_url' => tribe_resource_url( 'images/conditional-content/' . $this->get_slug() . '/fb-top-narrow.png', false, null, \Tribe__Main::instance() ),
					'link_url'         => 'https://evnt.is/tec-bf-fb-2024',
					'alt_text'         => sprintf(
						/* translators: %1$s: Sale year (numeric), %2$s: Sale name */
						_x( '%1$s %2$s - Get Filter Bar at 30%% off!', 'Alt text for the Filter Bar Sale Ad', 'tribe-common' ),
						date_i18n( 'Y' ),
						$this->get_sale_name()
					),
				],
				'default'                                              => [
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
					'link_url'          => 'https://evnt.is/tec-bf-etp-2024',
					'alt_text'          => sprintf(
						/* translators: %1$s: Sale year (numeric), %2$s: Sale name */
						_x( '%1$s %2$s - Get Event Tickets Plus at 30%% off!', 'Alt text for the Event Tickets Plus Sale Ad', 'tribe-common' ),
						date_i18n( 'Y' ),
						$this->get_sale_name()
					),
				],
				'event-tickets-plus/seating.php'            => [
					'image_url'         => tribe_resource_url( 'images/conditional-content/' . $this->get_slug() . '/seat-top-wide.png', false, null, \Tribe__Main::instance() ),
					'narrow_image_url'  => tribe_resource_url( 'images/conditional-content/' . $this->get_slug() . '/seat-top-narrow.png', false, null, \Tribe__Main::instance() ),
					'sidebar_image_url' => tribe_resource_url( 'images/conditional-content/' . $this->get_slug() . '/seat-sidebar.png', false, null, \Tribe__Main::instance() ),
					'link_url'          => 'https://evnt.is/tec-bf-etp-2024',
					'alt_text'          => sprintf(
						/* translators: %1$s: Sale year (numeric), %2$s: Sale name */
						_x( '%1$s %2$s - Get Event Tickets Plus at 30%% off!', 'Alt text for the Event Tickets Plus Sale Ad', 'tribe-common' ),
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
}
