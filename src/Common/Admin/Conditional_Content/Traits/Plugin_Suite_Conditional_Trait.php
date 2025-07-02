<?php
/**
 * Plugin Suite Conditional Trait to check plugin activation and context.
 *
 * @since TBD
 *
 * @package TEC\Common\Admin\Conditional_Content\Traits
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Conditional_Content\Traits;

/**
 * Trait for plugin suite-related conditional functionality.
 *
 * @since TBD
 */
trait Plugin_Suite_Conditional_Trait {

	/**
	 * Checks if The Events Calendar plugin is active.
	 *
	 * @since TBD
	 *
	 * @return bool True if TEC is active, false otherwise.
	 */
	public function is_events_suite_active(): bool {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		return is_plugin_active( 'the-events-calendar/the-events-calendar.php' );
	}

	/**
	 * Checks if Event Tickets plugin is active.
	 *
	 * @since TBD
	 *
	 * @return bool True if ET is active, false otherwise.
	 */
	public function is_tickets_suite_active(): bool {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		return is_plugin_active( 'event-tickets/event-tickets.php' );
	}

	/**
	 * Determines the current admin suite context (Events or Tickets).
	 * Only one context is considered active at a time for ad display purposes.
	 *
	 * @since TBD
	 *
	 * @return string|null 'events', 'tickets', or null if no context could be determined.
	 */
	public function get_current_admin_suite_context(): ?string {
		global $current_screen, $pagenow;

		// If we're not in admin, there's no admin context.
		if ( ! is_admin() ) {
			return null;
		}

		// First check screen ID which is the most reliable indicator.
		if ( isset( $current_screen ) && $current_screen instanceof \WP_Screen ) {
			if ( strpos( $current_screen->id, 'tribe_events' ) === 0 ) {
				return 'events';
			}

			if ( strpos( $current_screen->id, 'tickets' ) === 0 ) {
				return 'tickets';
			}

			// Check post type for editing screens.
			if ( in_array( $current_screen->base, [ 'post', 'edit' ] ) ) {
				if ( $current_screen->post_type === 'tribe_events' ) {
					return 'events';
				}
				if ( in_array( $current_screen->post_type, [ 'tec_tickets', 'tribe_rsvp', 'tribe_tickets' ] ) ) {
					return 'tickets';
				}
			}
		}

		// Check for known admin pages via $_GET parameters.
		if ( tribe_get_request_var( 'page' ) ) {
			$page = sanitize_key( tribe_get_request_var( 'page' ) );

			// Events admin pages.
			$events_pages = [
				'aggregator',
				'tribe-common',
				'tec-events-settings',
				'tec-events-help',
				'tribe-app-shop',
			];

			if ( in_array( $page, $events_pages ) ) {
				return 'events';
			}

			// Tickets admin pages.
			$tickets_pages = [
				'tec-tickets-settings',
				'tec-tickets-help',
				'tec-tickets-attendees',
				'tickets-attendees',
			];

			if ( in_array( $page, $tickets_pages ) ) {
				return 'tickets';
			}
		}

		// Check post type from URL parameters for edit screens.
		if ( 'edit.php' === $pagenow && tribe_get_request_var( 'post_type' ) ) {
			$post_type = sanitize_key( tribe_get_request_var( 'post_type' ) );

			if ( 'tribe_events' === $post_type ) {
				return 'events';
			}

			if ( in_array( $post_type, [ 'tec_tickets', 'tribe_rsvp', 'tribe_tickets' ] ) ) {
				return 'tickets';
			}
		}

		/**
		 * Filters the current admin suite context.
		 *
		 * @since TBD
		 *
		 * @param string|null $context The determined context ('events', 'tickets', or null).
		 */
		return apply_filters( 'tec_admin_conditional_content_current_suite_context', null );
	}
}
