<?php
/**
 * Provides methods and properties for CPT (sub)menus.
 *
 * @since   TBD
 *
 * @package TEC\Common\Menus
 */

namespace TEC\Common\Menus\Traits;

trait CPT {
	protected static $post_type = '';

	public function hooks() {
		add_filter('parent_file', [ $this, 'fix_admin_parent_file' ] );
	}

	public static function get_post_type() {
		return static::$post_type;
	}

	public function get_callback() {
		return '';
	}

	public function get_slug() {
		return 'edit.php?post_type=' . static::get_post_type();
	}


	function fix_admin_parent_file( $parent_file ){
		global $submenu_file, $current_screen;

		// Set correct active/current menu and submenu in the WordPress Admin menu for the "example_cpt" Add-New/Edit/List
		if ( $current_screen->post_type === $this->get_post_type() ) {
			$submenu_file = 'edit.php?post_type=' . $this->get_post_type();
			$parent_file = $this->get_parent_slug();
		}

		return $parent_file;
	}
}
