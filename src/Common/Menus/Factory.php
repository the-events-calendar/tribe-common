<?php
/**
 * Menu Factory
 *
 * The parent class for managing menu creation and access.
 *
 * @since TBD
 *
 * @package TEC\Common\Menus
 *
 */

namespace TEC\Common\Menus;


class Factory {
	/**
	 * Can we still register menus?
	 *
	 * @since TBD
	 *
	 * @var bool
	 */
	protected $can_register = true;

	/**
	 * Placeholder for a list(array) of registered menus
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	protected $items = [];

	protected $registered_items = [];

	public function register() {
		add_action( 'admin_menu', [ $this, 'register_in_wp' ] );
	}

	public function get_menus( $submenus = null ) {
		$menu_list = [];

		switch( $submenus ) {
			case ( true ) : // submenus only.
				foreach( $this->items as $item ) {
					if ( ! empty( $item->is_submenu ) ) {
						$menu_list[$item::$menu_slug] = $item;
					}
				}

				return array_unique( $menu_list );
				break;
			case ( false ) : // top-level menus only.
				foreach( $this->items as $item ) {
					if ( empty( $item->is_submenu ) ) {
						$menu_list[$item::$menu_slug] = $item;
					}
				}

				return $menu_list;
				break;
			default: // all top-level menus and submenus.
				return $this->items;
				break;
		}

		return $this->items;
	}

	public function get_menu( $menu_id )  {
		if ( empty( $this->items[ $menu_id ] ) ) {
			return false;
		}

		return $this->items[ $menu_id ];
	}

	public function get_submenu( $menu_id ) {
		if ( empty( $this->items[ $menu_id ] ) ) {
			return false;
		}

		$potential_submenu = $this->items[ $menu_id ];

		// Did we get a submenu?
		if ( empty( $potential_submenu->is_submenu ) ) {
			return false;
		}

		return $potential_submenu;
	}

	public function get_submenus( $menu_id ) {
		$menu_list = [];

		foreach( $this->items as $item ) {
			if ( ! empty( $item->is_submenu ) ) {
				$menu_list = $item;
			}
		}

		array_map(
			function( $menu ) use ( $menu_id ) {
				return $menu->parent === $menu_id;
			},
			$menu_list
		);

		return array_unique( $menu_list);
	}

	public function add_menu( $obj ) {
		if ( ! $this->can_register() ) {
			$foo = '';
			_doing_it_wrong(
				__FUNCTION__,
				'Function was called after it is possible to register a new menu.',
				'TBD'
			);
		}

		// Don't add duplicates.
		if ( $this->is_registered( $obj ) ) {
			return;
		}

		$this->items[$obj::$menu_slug] = $obj;
	}

	public function can_register() {
		if ( 0 < did_action( 'admin_menu' ) ) {
			return false;
		}

		return $this->can_register;
	}

	public function is_registered( $menu_id, $return = 'bool' ) {
		$menu_id = $this->normalize_menu_id_to_slug( $menu_id );

		if ( empty( $this->items[$menu_id] ) ) {
			return false;
		}

		$obj = $this->items[$menu_id];

		if ( $return !== 'bool' && $return !== 'boolean' ) {
			return $obj;
		}

		return (bool) $obj;
	}

	public function is_registered_in_wp( $menu_id, $return = 'bool' ) {
		$menu_id = $this->normalize_menu_id_to_slug( $menu_id );

		if ( empty( $this->registered_items[$menu_id] ) ) {
			return false;
		}

		$obj = $this->items[$menu_id];

		if ( $return !== 'bool' && $return !== 'boolean' ) {
			return $obj;
		}

		return (bool) $obj;
	}

	public function normalize_menu_id_to_slug( $menu_id ) {
		// Menu object passed.
		if ( $menu_id instanceof Abstract_Menu ) {
			return $menu_id->get_slug();
		}

		// Slug passed and already set in items.
		if ( isset( $this->items[$menu_id] ) ) {
			return $menu_id;
		}

		// Hook suffix passed and already registered.
		if ( isset( $this->registered_items[$menu_id] ) ) {
			return $this->registered_items[$menu_id]->get_slug();
		}

		if ( is_string( $menu_id ) && class_exists( $menu_id, false ) ) {
			$temp_menu = new $menu_id;
			return $temp_menu->get_slug();
		}

		return false;
	}

	public function register_in_wp() {
		// Last chance to jump on board!

		/**
		 * Allows triggering actions before the menu page is registered with WP.
		 *
		 * @param TEC\Common\Menus\Menu $menu The current menu object.
		 */
		do_action( 'tec_menu_before_register', $this );

		//attach_to_admin_menu()
		$menus = $this->get_menus( false );
		$submenus = $this->get_menus( true );

		foreach ( $menus as $menu ) {
			/**
			 * Allows triggering actions before the menu page is registered with WP.
			 *
			 * @param TEC\Common\Menus\Menu $menu The current menu object.
			 */
			do_action( 'tec_menu_setup_' . $menu->get_slug(), $this );

			$this->add_menu_to_wp( $menu );
		}

		foreach ( $submenus as $menu ) {
			$this->add_submenu_to_wp( $menu );
		}

		$this->can_register = false;
	}

	public function add_menu_to_wp( $menu ) {
		$hook_suffix = add_menu_page(
			$menu->get_page_title(),
			$menu->get_menu_title(),
			$menu->get_capability(),
			$menu::$menu_slug,
			$menu->get_callback(),
			$menu->get_icon_url(),
			$menu->get_position(),
		);

		$menu->hook_suffix = $hook_suffix;

		$registered_items[$hook_suffix] = $menu;
	}

	public function add_submenu_to_wp( $menu ) {
		$hook_suffix = add_submenu_page(
			$menu->get_parent_slug(),
			$menu->get_page_title(),
			$menu->get_menu_title(),
			$menu->get_capability(),
			$menu::$menu_slug,
			$menu->get_callback(),
			$menu->get_position(),
		);

		$menu->hook_suffix = $hook_suffix;

		$registered_items[$hook_suffix] = $menu;
	}
}
