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

	public function get_menus( $submenus = null ) {
		$menu_list = [];

		switch( $submenus ) {
			case ( true ) : // submenus only.
				foreach( $this->items as $item ) {
					if ( ! empty( $item->is_submenu ) ) {
						$menu_list = $item;
					}
				}

				return array_unique( $menu_list );
				break;
			case ( false ) : // top-level menus only.
				foreach( $this->items as $item ) {
					if ( empty( $item->is_submenu ) ) {
						$menu_list = $item;
					}
				}

				return array_unique( $menu_list );
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
			_doing_it_wrong(
				__FUNCTION__,
				'Function was called after it is possible to register a new menu.',
				'TBD'
			);
		}


	}

	public function can_register() {
		if ( did_action( 'admin_menu' ) ) {
			return false;
		}

		return $this->can_register;
	}

	public function is_registered( $menu_id, $return = 'bool' ) {
		if ( empty( $this->items[$menu_id] ) ) {
			return false;
		}

		$obj = $this->items[$menu_id];

		if ( $return !== 'bool' && $return !== 'boolean' ) {
			return $obj;
		}

		return (bool) $obj;
	}

	public function register_in_wp() {
		//attach_to_admin_menu()
		$menus = $this->get_menus( false );
		$submenus = $this->get_menus( true );

		foreach ( $menus as $menu ) {
			$menu->register();
		}

		foreach ( $submenus as $menu ) {
			$menu->register();
		}

		$this->can_register = false;
	}
}
