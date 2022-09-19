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

	/**
	 * {@inheritDoc}
	 */
	protected $capability = 'edit_posts';

	/**
	 * {@inheritDoc}
	 */
	protected static $menu_slug = 'tec-submenu';

	/**
	 * {@inheritDoc}
	 */
	protected $position = 15;

	/**
	 * {@inheritDoc}
	 */
	public function init() {
		parent::init();

		$this->menu_title  = _x( 'Venues', 'The title for the admin menu link', 'the-events-calendar');
		$this->page_title  = _x( 'Venues', 'The title for the admin page', 'the-events-calendar');
		$this->parent_file = 'tec-parent';
		$this->parent_slug = 'tec-parent';
		$this->post_type   = 'tec_custom_posttype';
	}

	public function render() {
		echo "Your {$this->get_menu_title()} submenu works! Now override this function to render your admin page.";
	}
}
