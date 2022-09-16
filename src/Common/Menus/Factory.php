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
	protected $queue = [];

	/**
	 * Register the factory and any hooks.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'admin_menu', [ $this, 'register_in_wp' ] );
	}

	/**
	 * Undocumented function
	 *
	 * @since TBD
	 *
	 * @param [type] $menu_id
	 *
	 * @return Menu_Contract
	 */
	public function get_menu( $menu_id ): Menu_Contract  {
		$menu_id = $this->normalize_menu_id_to_slug( $menu_id );
		if ( empty( $this->queue[ $menu_id ] ) ) {
			return false;
		}

		return $this->queue[ $menu_id ];
	}

	public function get_menus( $submenus = null ) {
		$menu_list = [];

		if ( true === $submenus ) {
			foreach( $this->queue as $item ) {
				if ( ! empty( $item->is_submenu ) ) {
					$menu_list[$item::$menu_slug] = $item;
				}
			}

			return $menu_list;
		} else if( false === $submenus ) {
			foreach( $this->queue as $item ) {
				if ( empty( $item->is_submenu ) ) {
					$menu_list[$item::$menu_slug] = $item;
				}
			}

			return $menu_list;
		}

		return $this->queue;
	}

	public function get_submenu( $menu_id ) {
		if ( empty( $this->queue[ $menu_id ] ) ) {
			return false;
		}

		$potential_submenu = $this->queue[ $menu_id ];

		// Did we get a submenu?
		if ( empty( $potential_submenu->is_submenu ) ) {
			return false;
		}

		return $potential_submenu;
	}

	public function get_submenus( $menu_id ) {
		$menu_list = [];

		foreach( $this->queue as $item ) {
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

		// Don't add duplicates.
		if ( isset( $this->queue[$obj::$menu_slug] ) ) {
			return;
		}

		$this->queue[$obj::$menu_slug] = $obj;
	}

	public function can_register() {
		if ( 0 < did_action( 'admin_menu' ) ) {
			return false;
		}

		return $this->can_register;
	}

	public function is_enqueued( $menu_id, $return = 'bool' ) {
		$menu_id = $this->normalize_menu_id_to_slug( $menu_id );

		return ! empty( $this->queue[$menu_id] ) && $this->queue[$menu_id] instanceof Menu_Contract;
	}

	public function register_in_wp() {
		global $menu;
		/**
		 * Allows triggering actions before the menus are registered with WP.
		 *
		 * @param TEC\Common\Menus\Menu $menu The current menu object.
		 */
		do_action( 'tec_menus_before_register', $this );

		//attach_to_admin_menu()
		$menus = $this->get_menus( null );

		foreach ( $menus as $menu_item ) {
			$menu_item->register_menu();
		}

		$this->can_register = false;

		bdump($menu);
	}

	/**
	 * Takes a menu object, a slug or a hook suffix and converts it to a slug for ID purposes.
	 *
	 * @since TBD
	 *
	 * @param Menu_Contract|string $menu_id The Menu object. Alternatively its: slug, ,
	 * @return void
	 */
	public function normalize_menu_id_to_slug( $menu_id ) {
		// Menu object passed.
		if ( $menu_id instanceof Menu_Contract ) {
			return $menu_id->get_slug();
		}

		// Slug passed and already set in queue.
		if ( isset( $this->queue[$menu_id] ) ) {
			return $menu_id;
		}
		// Passed class path.
		if ( is_string( $menu_id ) && class_exists( $menu_id, false ) ) {
			$temp_menu = new $menu_id;
			return $temp_menu->get_slug();
		}

		// Hook suffix passed and already registered.
		if ( is_string( $menu_id ) ) {
			$menu = array_filter(
				$this->queue[$menu_id],
				function( $id, $menu ) use ( $menu_id ) {
					return $menu->get_hook_suffix() === $menu_id;
				},
				ARRAY_FILTER_USE_BOTH
			);

			return current( $menu )->get_slug();
		}

		// Anything else.
		return false;
	}
}
