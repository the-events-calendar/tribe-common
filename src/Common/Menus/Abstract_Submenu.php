<?php
/**
 * An EXAMPLE class modeling a submenu - you should not extend this.
 * Instead, you extend the base Abstract_Menu class and add the Submenu Trait!
 *
 * @since TBD
 *
 * @package TEC\Common\Menus
 */

namespace TEC\Common\Menus;

use TEC\Common\Menus\Traits\Submenu;

/**
 * Class Menu
 *
 * @since TBD
 *
 * @package TEC\Common\Menus
 */
abstract class Submenu_Example extends Abstract_Menu implements Menu_Contract {
	use Submenu;

	/**
	 * Slug of the parent menu this goes under.
	 * Required.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected $parent_slug = '';

	/**
	 * Whether this is a submenu or not.
	 *
	 * @since TBD
	 *
	 * @var boolean
	 */
	protected $is_submenu = true;
}
