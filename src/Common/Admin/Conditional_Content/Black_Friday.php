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
	 * @since TBD
	 *
	 * @var string
	 */
	protected string $slug = 'black-friday';

	/**
	 * @inheritdoc
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected string $start_date = 'November 26th';

	/**
	 * @inheritdoc
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected string $end_date = 'December 3rd';

	/**
	 * @inheritdoc
	 *
	 * @var string
	 */
	protected int $start_time = 4;

	/**
	 * @inheritdoc
	 *
	 * @var string
	 */
	protected int $end_time = 7;

	/**
	 * Background color for the promotional content.
	 * Must match the background color of the image.
	 *
	 * @since TBD
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
	protected function get_target_plugin_suites(): array {
		// Target both Events Calendar and Event Tickets suites.
		return [ 'events', 'tickets' ];
	}

	/**
	 * @inheritdoc
	 */
	protected function get_suite_creative_map(): array {
		// Define the prioritized mapping of plugin states to creatives.
		return [
			// Rules for the Events suite context.
			'events'  => [
				// Events Pro upsell.
				'events-pro/events-pro.php'                            => [
					'image_url'        => tribe_resource_url( 'images/conditional-content/' . $this->get_slug() . '/events-pro.png', false, null, \Tribe__Main::instance() ),
					'narrow_image_url' => tribe_resource_url( 'images/conditional-content/' . $this->get_slug() . '/events-pro-narrow.png', false, null, \Tribe__Main::instance() ),
					'link_url'         => 'https://evnt.is/tec-bf-pro-2024',
					'alt_text'         => sprintf(
						/* translators: %1$s: Sale year (numeric), %2$s: Sale name */
						_x( '%1$s %2$s - Get Events Calendar Pro at 30%% off!', 'Alt text for the Events Pro Sale Ad', 'tribe-common' ),
						date_i18n( 'Y' ),
						$this->get_sale_name()
					),
				],
				// Filter Bar upsell.
				'events-filterbar/the-events-calendar-filter-view.php' => [
					'image_url'        => tribe_resource_url( 'images/conditional-content/' . $this->get_slug() . '/filterbar.png', false, null, \Tribe__Main::instance() ),
					'narrow_image_url' => tribe_resource_url( 'images/conditional-content/' . $this->get_slug() . '/filterbar-narrow.png', false, null, \Tribe__Main::instance() ),
					'link_url'         => 'https://evnt.is/tec-bf-fb-2024',
					'alt_text'         => sprintf(
						/* translators: %1$s: Sale year (numeric), %2$s: Sale name */
						_x( '%1$s %2$s - Get Filter Bar at 30%% off!', 'Alt text for the Filter Bar Sale Ad', 'tribe-common' ),
						date_i18n( 'Y' ),
						$this->get_sale_name()
					),
				],
				// Default creative for Events suite when all plugins are active/licensed.
				'default'                                            => [
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
			// Rules for the Tickets suite context.
			'tickets' => [
				// Event Tickets Plus upsell.
				'event-tickets-plus/event-tickets-plus.php' => [
					'image_url'        => tribe_resource_url( 'images/conditional-content/' . $this->get_slug() . '/tickets-plus.png', false, null, \Tribe__Main::instance() ),
					'narrow_image_url' => tribe_resource_url( 'images/conditional-content/' . $this->get_slug() . '/tickets-plus-narrow.png', false, null, \Tribe__Main::instance() ),
					'link_url'         => 'https://evnt.is/tec-bf-etp-2024',
					'alt_text'         => sprintf(
						/* translators: %1$s: Sale year (numeric), %2$s: Sale name */
						_x( '%1$s %2$s - Get Event Tickets Plus at 30%% off!', 'Alt text for the Event Tickets Plus Sale Ad', 'tribe-common' ),
						date_i18n( 'Y' ),
						$this->get_sale_name()
					),
				],
				// Default creative for Tickets suite when all plugins are active/licensed.
				'default'                                   => [
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
		];
	}
}
