<?php
/**
 * Menus
 *
 * The parent class for managing menu creation and access.
 *
 * @since TBD
 *
 * @package TEC\Common\Menus
 *
 */

namespace TEC\Common\Menus;


class Menus {
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
	 */
	public function register() : void {
		add_action( 'admin_menu', [ $this, 'register_in_wp' ] );
	}

	/**
	 * Add a menu to the queue.
	 *
	 * @since TBD
	 *
	 * @param Abstract_Menu $obj The menu object.
	 */
	public static function add_menu( $obj ) : void {
		if ( ! $this->can_register() ) {
			_doing_it_wrong(
				__FUNCTION__,
				'Function was called after it is possible to register a new menu.',
				'TBD'
			);
		}

		// Don't add duplicates.
		if ( isset( $this->queue[ $obj->get_slug() ] ) ) {
			return;
		}

		$this->queue[ $obj->get_slug() ] = $obj;
	}

	/**
	 * Get a menu by slug.
	 *
	 * @since TBD
	 *
	 * @param Abstract_Menu|string $menu_id The Menu object. Alternatively its: slug, hook suffix, or namespaced class "path".
	 *
	 * @return Abstract_Menu|false The Menu object. False if not found.
	 */
	public static function get_menu( $menu_id ) : Abstract_Menu  {
		$menu_id = $this->normalize_menu_id_to_slug( $menu_id );
		if ( empty( $this->queue[ $menu_id ] ) ) {
			return false;
		}

		return $this->queue[ $menu_id ];
	}

	/**
	 * Get all enqueued menus, optionally filtered.
	 *
	 * @since TBD
	 *
	 * @param ?bool $submenus If not passed, all menus will be returned.
	 *                        If true, only submenus will be returned.
	 *                        If false only to-level menus will be returned.
	 *
	 * @return array <string,mixed> An array of menu objects.
	 */
	public function get_menus( ?bool $submenus ) : array {
		$menu_list = [];

		if ( true === $submenus ) {
			foreach( $this->queue as $item ) {
				if ( ! empty( $item->is_submenu ) ) {
					$menu_list[ $item->get_slug() ] = $item;
				}
			}

			return $menu_list;
		} else if( false === $submenus ) {
			foreach( $this->queue as $item ) {
				if ( empty( $item->is_submenu ) ) {
					$menu_list[ $item->get_slug() ] = $item;
				}
			}

			return $menu_list;
		}

		return $this->queue;
	}

	/**
	 * Search the queue for a submenu by slug.
	 *
	 * @since TBD
	 *
	 * @param Abstract_Menu|string $menu_id The Menu object. Alternatively its: slug, hook suffix, or namespaced class "path".
	 *
	 * @return Abstract_Menu|null The Submenu object. NULL if not found or found but not a submenu.
	 */
	public function get_submenu( $menu_id ) : ?Abstract_Menu {
		if ( empty( $this->queue[ $menu_id ] ) ) {
			return null;
		}

		$potential_submenu = $this->queue[ $menu_id ];

		// Did we get a submenu?
		if ( empty( $potential_submenu->is_submenu ) ) {
			return null;
		}

		return $potential_submenu;
	}

	/**
	 * Search the queue for all submenus of a specified menu.
	 *
	 * @since TBD
	 *
	 * @param Abstract_Menu|string $menu_id The Menu object. Alternatively its: slug, hook suffix, or namespaced class "path".
	 *
	 * @return array <string,mixed> An array of menu objects - all submenus of the provided parent menu.
	 */
	public function get_submenus( $menu_id ) : array {
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

	/**
	 * Are we able to register menus?
	 *
	 * @since TBD
	 *
	 * @return boolean
	 */
	public function can_register() : bool {
		if ( 0 < did_action( 'admin_menu' ) ) {
			$this->can_register = false;
		}

		return $this->can_register;
	}

	/**
	 * Check if a menu is enqueued.
	 *
	 * @since TBD
	 *
	 * @param Abstract_Menu|string $menu_id The Menu object. Alternatively its: slug, hook suffix, or namespaced class "path".
	 *
	 * @return boolean
	 */
	public function is_enqueued( $menu_id ) : bool {
		$menu_id = $this->normalize_menu_id_to_slug( $menu_id );

		return ! empty( $this->queue[ $menu_id ] ) && $this->queue[ $menu_id ] instanceof Abstract_Menu;
	}

	/**
	 * Register all enqueued menus in WordPress.
	 *
	 * @since TBD
	 */
	public function register_in_wp() : void {
		global $menu;
		/**
		 * Allows triggering actions before the menus are registered with WP.
		 *
		 * @param TEC\Common\Menus\Abstract_Menu $menu The current menu object.
		 */
		do_action( 'tec_menus_before_register', $this );

		//attach_to_admin_menu()
		$menus = $this->get_menus( null );

		foreach ( $menus as $menu_item ) {
			$menu_item->register_menu();
		}

		$this->can_register = false;
	}

	/**
	 * Takes a menu object, a slug or a hook suffix and converts it to a slug for ID purposes.
	 *
	 * @since TBD
	 *
	 * @param Abstract_Menu|string $menu_id The Menu object. Alternatively its: slug, hook suffix, or namespaced class "path".
	 *
	 * @return string|null The menu slug (ID) or null if it could not be discerned.
	 */
	public function normalize_menu_id_to_slug( $menu_id ) : ?string {
		// Menu object passed.
		if ( $menu_id instanceof Abstract_Menu ) {
			return $menu_id->get_slug();
		}

		// Slug passed and already set in queue.
		if ( isset( $this->queue[ $menu_id ] ) ) {
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
				$this->queue[ $menu_id ],
				function( $id, $menu ) use ( $menu_id ) {
					return $menu->get_hook_suffix() === $menu_id;
				},
				ARRAY_FILTER_USE_BOTH
			);

			return current( $menu )->get_slug();
		}

		// Anything else.
		return null;
	}
}
