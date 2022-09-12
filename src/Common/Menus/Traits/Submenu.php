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
	}

	public function register() {
		// the real deal.
		if ( ! $this->get_parent_slug() ) {
			_doing_it_wrong(
				__FUNCTION__,
				'Submenu cannot be created without a parent slug. Creating a top-level menu instead.',
				'TBD'
			);

			$this->hook_suffix = parent::add_menu();
		} else if ( ! $this->is_submenu() ) {
			_doing_it_wrong(
				__FUNCTION__,
				'Submenu Trait is not needed for a top-level menu. Creating a top-level menu instead.',
				'TBD'
			);

			$this->hook_suffix = parent::add_menu();
		} else {
			$this->hook_suffix = $this->add_menu();
		}



		return $this->hook_suffix;
	}

	public function add_menu() {
		if ( empty( $this->get_parent_slug() ) ) {
			_doing_it_wrong(
				__FUNCTION__,
				'Attempted to create a submenu without defining a parent menu.',
				'TBD'
			);
		}

		$this->hook_suffix = add_submenu_page(
			$this->get_parent_slug(), // required
			$this->page_title, // required
			$this->menu_title, // required
			$this->capability, // required
			$this->menu_slug, // required
			$this->callback,
			$this->position
		);

		return $this->hook_suffix;
	}
}
