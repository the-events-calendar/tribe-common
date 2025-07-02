<?php
/**
 * Stellar Sale Promo Conditional Content.
 *
 * @since TBD
 *
 * @package TEC\Common\Admin\Conditional_Content;
 */

namespace TEC\Common\Admin\Conditional_Content;

/**
 * Set up for Stellar Sale promo.
 *
 * @since TBD
 */
class Stellar_Sale extends Promotional_Content_Abstract {

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
	protected function get_link_url(): string {
		return 'https://evnt.is/1bdv';
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
				'events-pro/events-pro.php'                   => [
					'image_url'        => tribe_resource_url( 'images/conditional-content/' . $this->get_slug() . '/events-pro.png', false, null, \Tribe__Main::instance() ),
					'narrow_image_url' => tribe_resource_url( 'images/conditional-content/' . $this->get_slug() . '/events-pro-narrow.png', false, null, \Tribe__Main::instance() ),
					'link_url'         => 'https://evnt.is/stellar-events-pro',
					'alt_text'         => sprintf(
						/* translators: %1$s: Sale year (numeric), %2$s: Sale name */
						_x( '%1$s %2$s - Get Events Calendar Pro with 25%% savings!', 'Alt text for the Events Pro Sale Ad', 'tribe-common' ),
						date_i18n( 'Y' ),
						$this->get_sale_name()
					),
				],
				// Community Events upsell.
				'events-community/tribe-community-events.php' => [
					'image_url'        => tribe_resource_url( 'images/conditional-content/' . $this->get_slug() . '/community.png', false, null, \Tribe__Main::instance() ),
					'narrow_image_url' => tribe_resource_url( 'images/conditional-content/' . $this->get_slug() . '/community-narrow.png', false, null, \Tribe__Main::instance() ),
					'link_url'         => 'https://evnt.is/stellar-community',
					'alt_text'         => sprintf(
						/* translators: %1$s: Sale year (numeric), %2$s: Sale name */
						_x( '%1$s %2$s - Get Community Events with 25%% savings!', 'Alt text for the Community Events Sale Ad', 'tribe-common' ),
						date_i18n( 'Y' ),
						$this->get_sale_name()
					),
				],
				// Default creative for Events suite when all plugins are active/licensed.
				'default'                                     => [
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
					'link_url'         => 'https://evnt.is/stellar-tickets-plus',
					'alt_text'         => sprintf(
						/* translators: %1$s: Sale year (numeric), %2$s: Sale name */
						_x( '%1$s %2$s - Get Event Tickets Plus with 25%% savings!', 'Alt text for the Event Tickets Plus Sale Ad', 'tribe-common' ),
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
