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

trait Is_Events_Page {
	/**
	 * The page type for the page.
	 *
	 * @since 6.4.1
	 *
	 * @var bool
	 */
	public static $page_type = 'events';

	/**
	 * The slug for the parent page.
	 *
	 * @since 6.4.1
	 *
	 * @var string
	 */
	public static string $parent_slug = 'tec_events_page_';

	/**
	 * Get the parent page slug.
	 *
	 * @since 6.4.1
	 */
	public function get_parent_page_slug(): string {
		return 'edit.php?post_type=tribe_events';
	}

	/**
	 * Get the logo source URL.
	 *
	 * @since 6.4.1
	 */
	public function get_logo_source(): string {
		$logo_source = tribe_resource_url( 'images/logo/the-events-calendar.svg', false, null, Tribe__Main::instance() );

		/**
		 * Filter the admin page logo source URL.
		 *
		 * @since 6.4.1
		 *
		 * @param string $logo_source The settings page logo resource URL.
		 * @param object $this The current admin page object.
		 */
		return (string) apply_filters( 'tec_events_settings_page_logo_source', $logo_source, $this );
	}
}
