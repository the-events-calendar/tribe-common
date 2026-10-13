<?php
/**
 * Who may see the Nexcess banner, and where.
 *
 * @since TBD
 *
 * @package TEC\Common\Banners
 */

namespace TEC\Common\Banners;

use TEC\Common\LiquidWeb\Harbor\Admin\Feature_Manager_Page;
use WP_Screen;

/**
 * Decides whether the banner may show on the current screen.
 *
 * @since TBD
 */
class Audience {

	/**
	 * The plugins' post types.
	 *
	 * @since TBD
	 *
	 * @var string[]
	 */
	private const POST_TYPES = [
		'tribe_events',
		'tribe_venue',
		'tribe_organizer',
		'tribe_event_series',
		'tec_calendar_embed',
		'tec_tc_order',
		'ticket-meta-fieldset',
	];

	/**
	 * Plugin pages the common screen helper misses.
	 *
	 * @since TBD
	 *
	 * @var string[]
	 */
	private const PAGES = [ 'tickets-setup' ];

	/**
	 * The site's paid plan.
	 *
	 * @since TBD
	 *
	 * @var Active_Plan
	 */
	private Active_Plan $active_plan;

	/**
	 * Audience constructor.
	 *
	 * @since TBD
	 *
	 * @param Active_Plan $active_plan The site's paid plan.
	 */
	public function __construct( Active_Plan $active_plan ) {
		$this->active_plan = $active_plan;
	}

	/**
	 * Whether the banner may show on the current screen.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public function should_display(): bool {
		return $this->active_plan->exists()
			&& ! $this->is_editor()
			&& ( $this->is_plugin_page() || $this->is_license_manager_page() );
	}

	/**
	 * Whether this screen edits or creates a single post.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	private function is_editor(): bool {
		$screen = get_current_screen();

		return $screen instanceof WP_Screen && 'post' === $screen->base;
	}

	/**
	 * Whether the current screen is one of the plugins' admin pages.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	private function is_plugin_page(): bool {
		$screen = get_current_screen();

		if ( ! $screen instanceof WP_Screen ) {
			return false;
		}

		if ( $screen->post_type !== '' ) {
			return in_array( $screen->post_type, self::POST_TYPES, true );
		}

		return tribe( 'admin.helpers' )->is_screen() || in_array( tribe_get_request_var( 'page' ), self::PAGES, true );
	}

	/**
	 * Whether this is the Nexcess licensing page.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	private function is_license_manager_page(): bool {
		$screen = get_current_screen();

		return $screen instanceof WP_Screen && 'settings_page_' . Feature_Manager_Page::PAGE_SLUG === $screen->id;
	}
}
