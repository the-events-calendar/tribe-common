<?php
/**
 * The interface for all menu objects.
 *
 * @since TBD
 *
 * @package TEC\Common\Menus
 */

namespace TEC\Common\Menus;

/**
 * Interface Menu_Contract
 *
 * @since TBD
 *
 * @package TEC\Common\Menus
 */
interface Menu_Contract {
	/**
	 * Constructor
	 */
	public function __construct();

	/**
	 * Initialize the menu.
	 * This is where children will set properties inherited from traits, etc.
	 *
	 * @since TBD
	 */
	public function init();

	/**
	 * Build the menu.
	 * This triggers adding the menu via the Menus Object.
	 * Menus handles enqueueing menus before triggering registration with WordPress.
	 *
	 * @since TBD
	 */
	public function build();

	/**
	 * Wrapper function for register_in_wp() that contains hooks
	 *
	 * @since TBD
	 */
	public function register_menu();

	/**
	 * Is the menu registered in WordPress?
	 * Shortcut that just checks if the menu $hok_suffix has been set.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public function is_registered() : bool;

	/**
	 * Is this menu a submenu?
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public function is_submenu() : bool;

	/**
	 * returns the callback function.
	 *
	 * @since TBD
	 *
	 * @return string|callable
	 */
	public function get_callback() : string|callable;

	/**
	 * Get the menu's required capability.
	 *
	 * @since TBD
	 *
	 * @return string|array
	 */
	public function get_capability() : string|array;

	/**
	 * Get the menu's hok suffix.
	 * Not set until the menu has been registered with WordPress.
	 *
	 * @since TBD
	 *
	 * @return string|null
	 */
	public function get_hook_suffix() : ?string;

	/**
	 * Get the menu's icon URL.
	 * Typically a URL string, a dashicon name or an svg string.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_icon_url() : string;

	/**
	 * Get the menu's title - the one used in the menu itself.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_menu_title() : string;

	/**
	 * Get the menu's page title.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_page_title() : string;

	/**
	 * Get the menu's parent slug. Empty if a top-level menu.
	 *
	 * @since TBD
	 *
	 * @return string|null
	 */
	public function get_parent_slug() : ?string;

	/**
	 * Get the menu's parent. Empty if a top-level menu.
	 *
	 * @since TBD
	 *
	 * @return Menu_Contract|null
	 */
	public function get_parent() : ?Menu_Contract;

	/**
	 * Get the menu's position.
	 *
	 * @since TBD
	 *
	 * @return int
	 */
	public function get_position() : int;

	/**
	 * Get the menu's slug.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_slug() : string;

	/**
	 * Get the menu's admin URL.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_url() : string;
}
