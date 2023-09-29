<?php

/**
 * Admin Calendash for TEC plugins.
 *
 * @since   TBD
 *
 * @package Tribe\Admin
 */

namespace Tribe\Admin;

use Tribe__Main;
use Tribe__Settings;

/**
 * Class Admin Calendash.
 *
 * @since   TBD
 *
 * @package Tribe\Admin
 */
class Calendash {
	/**
	 * Defines the slug of the calendash page in the WP admin menu item.
	 *
	 * @since TBD
	 *
	 * @var string the calendash menu slug.
	 */
	const MENU_SLUG = 'tec-calendash';

	/**
	 * The slug for the new admin page.
	 *
	 * @since TBD
	 *
	 */
	private $admin_page = null;

	/**
	 * Class constructor.
	 *
	 * @since TBD
	 *
	 */
	public function hook() {
		add_action( 'admin_menu', [ $this, 'add_menu_page' ], 90 );
		add_filter( 'admin_body_class', [ $this, 'admin_body_class' ] );
		add_action( 'wp_before_admin_bar_render', [ $this, 'add_toolbar_item' ], 20 );
	}

	/**
	 * This method created the calendash page and adds it to TEC menu.
	 *
	 * @since TBD
	 *
	 */
	public function add_menu_page() {
		if ( ! Tribe__Settings::instance()->should_setup_pages() ) {
			return;
		}

		$page_title = esc_html__( 'Calendash', 'tribe-common' );
		$menu_title = esc_html__( 'Calendash', 'tribe-common' );

		$capability = $this->get_required_capability();

		$where = Tribe__Settings::instance()->get_parent_slug();

		$this->admin_page = add_submenu_page(
			$where,
			$page_title,
			$menu_title,
			$capability,
			static::MENU_SLUG,
			[
				$this,
				'do_menu_page',
			]
		);
	}

	/**
	 * Gets the required capability for the calendash page.
	 *
	 * @since TBD
	 *
	 * @return string Which capability we required for the calendash page.
	 */
	public function get_required_capability() {
		/**
		 * Allows third party filtering of capability required to see the Calendash page.
		 *
		 * @since TBD
		 *
		 * @param string $capability      Which capability we are using as the one required for the
		 *                                calendash page.
		 * @param static $calendash       The current instance of the class that handles this page.
		 */
		$capability = apply_filters( 'tec_calendash_capability', 'install_plugins', $this );

		return $capability;
	}

	/**
	 * Hooked to admin_body_class to add a class for calendash page.
	 *
	 * @since 4.15.0
	 *
	 * @param string $classes a space separated string of classes to be added to body.
	 *
	 * @return string $classes a space separated string of classes to be added to body.
	 */
	public function admin_body_class( $classes ) {
		if ( ! $this->is_current_page() ) {
			return $classes;
		}

		$classes .= ' tec-calendash';

		return $classes;
	}

	/**
	 * Adds the calendash menu to the the WP admin bar under events.
	 *
	 * @since TBD
	 *
	 */
	public function add_toolbar_item() {
		$capability = $this->get_required_capability();

		if ( ! current_user_can( $capability ) ) {
			return;
		}

		global $wp_admin_bar;

		$wp_admin_bar->add_menu( [
			'id'     => 'tec-calendash',
			'title'  => esc_html__( 'Calendash', 'tribe-common' ),
			'href'   => Tribe__Settings::instance()->get_url( [ 'page' => static::MENU_SLUG ] ),
			'parent' => 'tribe-events-settings-group',
		] );
	}

	/**
	 * Checks if the current page is the calendash page.
	 *
	 * @since TBD
	 *
	 * @return boolean returns true if the current page is the calendash page.
	 */
	public function is_current_page() {
		if ( ! Tribe__Settings::instance()->should_setup_pages() || ! did_action( 'admin_menu' ) ) {
			return false;
		}

		if ( is_null( $this->admin_page ) ) {
			_doing_it_wrong(
				__FUNCTION__,
				'Function was called before it is possible to accurately determine what the current page is.',
				'4.5.6'
			);

			return false;
		}

		global $current_screen;

		$calendash_pages = [
			'tribe_events_page_tec-calendash',
			'tickets_page_tec-tickets-calendash',
		];

		return in_array( $current_screen->id, $calendash_pages );
	}

	/**
	 * Renders the Calendash page.
	 *
	 * @since TBD
	 *
	 */
	public function do_menu_page() {
		tribe_asset_enqueue( 'tribe-admin-help-page' );
		$main = Tribe__Main::instance();
		include_once Tribe__Main::instance()->plugin_path . 'src/admin-views/calendash.php';
	}

	/**
	 * Fired to display notices in the admin pages where the method is called.
	 *
	 * @since TBD
	 *
	 * @param string $page the page which the action is being applied.
	 *
	 */
	public function admin_notice( $page ) {
		do_action( 'tec_admin_notice_area', $page );
	}
}
