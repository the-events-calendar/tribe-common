<?php
/**
 * Is TEC Page.
 *
 * @since 6.4.1
 *
 * @package TEC
 */

namespace TEC\Common\Admin\Traits;

use Tribe__Main;

trait Is_Tickets_Page {
	/**
	 * The page type for the page.
	 *
	 * @since 6.4.1
	 *
	 * @var bool
	 */
	public static $page_type = 'tickets';

	/**
	 * The slug for the parent page.
	 *
	 * @since 6.4.1
	 *
	 * @var string
	 */
	public static string $parent_slug = 'tec_tickets_page_';

	/**
	 * Get the parent page slug.
	 *
	 * @since 6.4.1
	 */
	public function get_parent_page_slug(): string {
		return 'tec-tickets';
	}

	/**
	 * Get the logo source URL.
	 *
	 * @since 6.4.1
	 */
	public function get_logo_source(): string {
		$logo_source = tribe_resource_url( 'images/logo/event-tickets.svg', false, null, Tribe__Main::instance() );

		/**
		 * Filter the admin page logo source URL.
		 *
		 * @since 6.4.1
		 *
		 * @param string $logo_source The settings page logo resource URL.
		 * @param object $page        The current admin page object.
		 */
		return (string) apply_filters( 'tec_tickets_settings_page_logo_source', $logo_source, $this );
	}
}
