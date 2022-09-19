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

   /**
	* Variable for asserting this is a submenu.
	*
	* @since TBD
	*
	* @var boolean
	*/
	protected $is_submenu = true;

	/**
	 * Actually handles registering the submenu with WordPress.
	 *
	 * @since TBD
	 */
	protected function register_in_wp() {
		$this->hook_suffix = add_submenu_page(
			$this->get_parent_slug(),
			$this->get_page_title(),
			$this->get_menu_title(),
			$this->get_capability(),
			$this->get_slug(),
			$this->get_callback(),
			$this->get_position()
		);

		do_action( 'tec_menu_registered', $this );

		do_action( 'tec_submenu_registered', $this );

		do_action( 'tec_menu_' . $this->get_slug() . '_registered', $this );

		do_action( 'tec_submenu_' . $this->get_slug() . '_registered', $this );

		return $this->hook_suffix;
	}

	/**
	 * {@inheritDoc}
	 */
	public function is_submenu() : bool {
		$is_submenu = apply_filters( 'tec_menus_is_submenu', $this->is_submenu, $this );

		return (bool) apply_filters( "tec_menus_{$this->get_slug()}_is_submenu", $is_submenu, $this );
	}
}
