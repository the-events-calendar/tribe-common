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

	public function render();

	public function is_submenu();

	public function get_slug();

	public function get_parent();

	public function get_parent_slug();
}
