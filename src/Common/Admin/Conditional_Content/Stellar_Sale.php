<?php
/**
 * Stellar Sale Promo Conditional Content.
 *
 * @since TBD
 *
 * @package TEC\Common\Admin\Conditional_Content;
 */

namespace TEC\Common\Admin\Conditional_Content;

use TEC\Common\Admin\Conditional_Content\Traits\Datetime_Conditional_Trait;
use TEC\Common\Admin\Conditional_Content\Traits\Plugin_Suite_Conditional_Trait;
use TEC\Common\Admin\Conditional_Content\Traits\Installed_Plugins_Conditional_Trait;

/**
 * Set up for Stellar Sale promo.
 *
 * @since TBD
 */
class Stellar_Sale extends Promotional_Content_Abstract {
	// Add the traits this concrete class specifically uses.
	use Datetime_Conditional_Trait;
	use Plugin_Suite_Conditional_Trait;
	use Installed_Plugins_Conditional_Trait;

	/**
	 * @inheritdoc
	 *
	 * @var string
	 *
	 * @since TBD
	 */
	protected string $slug = 'stellar-sale';

	/**
	 * @var string
	 *
	 * @since TBD
	 */
	protected string $background_color = '#1c202f';

	/**
	 * Constructor for the Stellar Sale promotional content.
	 *
	 * @since TBD
	 */
	public function __construct() {
		// Initialize the hooks provided by the traits used by this class (and the abstract class).
		$this->initialize_trait_hooks();

		// Configure Datetime_Conditional_Trait with specific dates for Stellar Sale.
		$this->set_datetime_configuration( 'July 29th', 'August 5th', 4, 7 );
	}

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
	 * Determines the current admin suite context (Events or Tickets) for Stellar Sale.
	 * This is called by Plugin_Suite_Conditional_Trait.
	 *
	 * @since TBD
	 *
	 * @return string|null 'events', 'tickets', or null if no context could be determined.
	 */
	protected function get_current_admin_suite_context(): ?string {
		// Implement specific logic for Stellar Sale to determine the suite context.
		global $current_screen;

		if ( isset( $current_screen ) && $current_screen instanceof \WP_Screen ) {
			if ( strpos( $current_screen->id, 'tribe_events' ) === 0 || $current_screen->post_type === 'tribe_events' ) {
				return 'events';
			}
			if ( strpos( $current_screen->id, 'tickets' ) === 0 || in_array( $current_screen->post_type, [ 'tec_tickets', 'tribe_rsvp', 'tribe_tickets' ] ) ) {
				return 'tickets';
			}
		}
		return null;
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
					'link_url'          => $this->get_link_url(),
					'sidebar_image_url' => tribe_resource_url( 'images/conditional-content/' . $this->get_sidebar_image(), false, null, \Tribe__Main::instance() ),
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
