<?php
/**
 * Provides methods and properties for submenus.
 *
 * @since   TBD
 *
 * @package TEC\Common\Menus
 */

namespace TEC\Common\Menus\Traits;

trait Submenu {
	/**
	 * Slug of the parent menu this goes under.
	 * Required.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
   protected $parent_slug = '';

	public function __construct() {
		$this->is_submenu = true;

        if ( empty( $this->get_parent() ) ) {
			_doing_it_wrong(
				__FUNCTION__,
				'You cannot register a submenu without a parent menu.',
				'TBD'
			);
        }
	}

	public function register_in_wp() {
		$this->hook_suffix = add_submenu_page(
			$this->get_parent_slug(),
			$this->get_page_title(),
			$this->get_menu_title(),
			$this->get_capability(),
			$this->get_slug(),
			$this->get_callback(),
			$this->get_position(),
		);

		return $this->hook_suffix;
	}
}
