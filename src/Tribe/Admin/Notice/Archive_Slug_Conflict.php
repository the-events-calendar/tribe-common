<?php


/**
 * Class Tribe__Admin__Notice__Archive_Slug_Conflict
 *
 * Takes care of adding an admin notice if a page with the `/events` slug has been created in the site.
 */
class Tribe__Admin__Notice__Archive_Slug_Conflict {

	/**
	 * @var static
	 */
	protected static $instance;

	/**
	 * @var string The slug of The Events Calendar archive page.
	 */
	protected $archive_slug;

	/**
	 * @var WP_Post The page post object.
	 */
	protected $page;

	/**
	 * @return Tribe__Admin__Notice__Archive_Slug_Conflict
	 */
	public static function instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Hooks the action to show an admin notice if a page with the `/events` slug exists on the site.
	 */
	public function maybe_add_admin_notice() {
		$this->archive_slug = Tribe__Settings_Manager::get_option( 'eventsSlug', 'events' );
		$page               = get_page_by_path( $this->archive_slug );
		if ( ! $page || $page->post_status == 'trash' ) {
			return;
		}
		$this->page = $page;
		add_action( 'admin_notices', array( $this, 'notice' ) );
	}

	/**
	 * Echoes the admin notice to the page
	 */
	public function notice() {
		// What's happening?
		$page_title = apply_filters( 'the_title', $this->page->post_title );
		$line_1     = __( sprintf( 'The page "%1$s" uses the "/%2$s" slug: the Events Calendar plugin will show its calendar in place of the page.', $page_title, $this->archive_slug ), 'tribe-common' );

		// What the user can do
		$page_edit_link = get_edit_post_link( $this->page->ID );
		$can_edit_page_link    = sprintf( __( '<a href="%s">Edit the page slug</a>', 'tribe-common' ), $page_edit_link );
		$page_edit_link_string = current_user_can( 'edit_pages' ) ? $can_edit_page_link : __( 'Ask the site administrator to edit the page slug', 'tribe-common' );

		$settings_cap                = apply_filters( 'tribe_settings_req_cap', 'manage_options' );
		$admin_slug                  = apply_filters( 'tribe_settings_admin_slug', 'tribe-common' );
		$setting_page_link           = apply_filters( 'tribe_settings_url', admin_url( 'edit.php?page=' . $admin_slug . '#tribe-field-eventsSlug' ) );
		$can_edit_settings_link      = sprintf( __( '<a href="%s">edit Events settings</a>.', 'tribe-common' ), $setting_page_link );
		$events_settings_link_string = current_user_can( $settings_cap ) ? $can_edit_settings_link : __( ' ask the site administrator set a different Events URL slug.', 'tribe-common' );

		$line_2 = __( sprintf( '%1$s or %2$s', $page_edit_link_string, $events_settings_link_string ), 'tribe-common' );

		echo sprintf( '<div id="message" class="error"><p>%s</p><p>%s</p></div>', $line_1, $line_2 );
	}
}
