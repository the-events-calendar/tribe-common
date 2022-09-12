<?php
/**
 * The base, abstract, class modeling a submenu.
 * This is more for an example - you don't need to extend this.
 * When you extend the base Abstract_Menu class just add the Submenu Trait
 *
 * This class does nothing by itself - it is meant to be extended for specific menus,
 * changing the properties as appropriate.
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
abstract class Abstract_Submenu extends Abstract_Menu implements Menu_Contract {
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
