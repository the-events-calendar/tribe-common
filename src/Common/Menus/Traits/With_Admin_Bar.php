<?php
/**
 * Adds menu item to the WP admin bar.
 *
 * @since   TBD
 *
 * @package TEC\Common\Menus
 */

namespace TEC\Common\Menus\Traits;

trait With_Admin_Bar {

	/**
	 * slug of the adminbar parent menu.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public $adminbar_parent = '';

	public function hooks() {
		add_action( 'wp_before_admin_bar_render', [ $this, 'add_toolbar_item' ], 20 );
	}

	/**
	 * Adds the troubleshooting menu to the the WP admin bar under events.
	 *
	 * @since 4.14.2
	 *
	 */
	public function add_toolbar_item() {
		$capability = $this->get_capability();

		if ( ! current_user_can( $capability ) ) {
			return;
		}

		global $wp_admin_bar;

		$wp_admin_bar->add_menu( [
			'id'     => $this->get_slug(),
			'title'  => $this->get_menu_title(),
			'href'   => $this->get_url(),
			'parent' => $this->adminbar_parent,
		] );
	}
}
