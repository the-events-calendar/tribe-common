<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class Tribe__Admin__Notice__Archive_Slug_Conflict
 *
 * Takes care of adding an admin notice if a page with the `/events` slug has been created in the site.
 */
class Tribe__Admin__Notice__Archive_Slug_Conflict extends Tribe__Admin__Notice__Abstract {
	/**
	 * On PHP 5.2 the child class doesn't get spawned on the Parent one, so we don't have
	 * access to that information on the other side unless we pass it around as a param
	 * so we throw __CLASS__ to the parent::instance() method to be able to spawn new instance
	 * of this class and save on the parent::$instances variable.
	 *
	 * @return Tribe__Admin__Notice__Archive_Slug_Conflict
	 */
	public static function instance( $name = null ) {
		return parent::instances( __CLASS__ );
	}


	/**
	 * Method to get the Slug of this Notice
	 *
	 * @return string
	 */
	public function get_slug() {
		return 'archive-slug-conflict';
	}

	/**
	 * Method returning a boolean to determine if the notice is visible
	 *
	 * @return boolean
	 */
	public function is_visible() {
		$archive_slug = Tribe__Settings_Manager::get_option( 'eventsSlug', 'events' );
		$page         = get_page_by_path( $archive_slug );

		if ( ! $page || 'trash' === $page->post_status ) {
			return false;
		}

		if ( $this->has_user_dimissed() ) {
			return false;
		}

		return true;
	}

	/**
	 * Display the Notice on the Admin page if `$this->is_visible()` returns true
	 *
	 * @return  void
	 */
	public function notice() {
		$archive_slug = Tribe__Settings_Manager::get_option( 'eventsSlug', 'events' );
		$page = get_page_by_path( $archive_slug );

		// What's happening?
		$page_title = apply_filters( 'the_title', $page->post_title, $page->ID );
		$line_1     = __( sprintf( 'The page "%1$s" uses the "/%2$s" slug: the Events Calendar plugin will show its calendar in place of the page.', $page_title, $archive_slug ), 'tribe-common' );

		// What the user can do
		$page_edit_link        = get_edit_post_link( $page->ID );
		$can_edit_page_link    = sprintf( __( '<a href="%s">Edit the page slug</a>', 'tribe-common' ), $page_edit_link );
		$page_edit_link_string = current_user_can( 'edit_pages' ) ? $can_edit_page_link : __( 'Ask the site administrator to edit the page slug', 'tribe-common' );

		$settings_cap                = apply_filters( 'tribe_settings_req_cap', 'manage_options' );
		$admin_slug                  = apply_filters( 'tribe_settings_admin_slug', 'tribe-common' );
		$setting_page_link           = apply_filters( 'tribe_settings_url', admin_url( 'edit.php?page=' . $admin_slug . '#tribe-field-eventsSlug' ) );
		$can_edit_settings_link      = sprintf( __( '<a href="%s">edit Events settings</a>.', 'tribe-common' ), $setting_page_link );
		$events_settings_link_string = current_user_can( $settings_cap ) ? $can_edit_settings_link : __( ' ask the site administrator set a different Events URL slug.', 'tribe-common' );

		$line_2 = __( sprintf( '%1$s or %2$s', $page_edit_link_string, $events_settings_link_string ), 'tribe-common' );

		echo sprintf( '<div id="message" class="notice error is-dismissible tribe-dismiss-notice" data-ref="archive-slug-conflict"><p>%s</p><p>%s</p></div>', $line_1, $line_2 );
	}
}
