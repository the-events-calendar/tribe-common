<?php
/**
 * Is TEC Page.
 *
 * @since 7.0.0
 *
 * @package TEC
 */

namespace TEC\Common\Admin\Traits;

use Tribe__Main;

trait Is_TEC_Page {
	/**
	 * Whether the page is an Event Tickets page.
	 *
	 * @since 7.0.0
	 *
	 * @var bool
	 */
	public static $is_tickets_page = true;

	/**
	 * The slug for the parent page.
	 *
	 * @since 7.0.0
	 *
	 * @var string
	 */
	public static string $parent_slug = 'tec_tickets_page_';

	/**
	 * Get the page slug.
	 *
	 * @since 7.0.0
	 */
	public static function get_page_slug(): string {
		if ( ! empty( static::$page_slug ) ) {
			return static::$page_slug;
		}

		static::$page_slug = static::$parent_slug . static::$slug;

		return static::$page_slug;
	}


	/**
	 * Get the logo source URL.
	 *
	 * @since 7.0.0
	 */
	public function get_logo_source(): string {
		$logo_source = tribe_resource_url( 'images/logo/event-tickets.svg', false, null, Tribe__Main::instance() );

		$admin_page = static::get_page_slug();

		/**
		 * Filter the admin page logo source URL.
		 *
		 * @since 7.0.0
		 *
		 * @param string $logo_source The settings page logo resource URL.
		 * @param string $admin_page The admin page ID.
		 */
		return (string) apply_filters( 'tec_tickets_settings_page_logo_source', $logo_source, $admin_page );
	}
}
