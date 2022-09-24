<?php
/**
 * Provides methods and properties for CPT (sub)menus.
 *
 * @since   TBD
 *
 * @package TEC\Common\Menus
 */

namespace TEC\Common\Menus\Traits;

trait CPT {
	/**
	 * Should we display an associate "Add New" menu item?
	 *
	 * @since TBD
	 *
	 * @var boolean
	 */
	protected $add_new_menu = false;

	/**
	 * The CPT post type slug.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected $post_type = '';

	/**
	 * The parent (menu) file/slug.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected $parent_file = 'admin.php';

	/**
	 * {@inheritDoc}
	 */
	protected function cpt_hooks() : void {
		if ( ! has_action( 'tec_submenu_' . $this->get_slug() . '_registered' ) ) {
			add_action(
				'tec_submenu_' . $this->get_slug() . '_registered',
				[ $this, 'register_new_post_menu' ]
			);
		}

		// Parent menu highlight fix.
		add_filter( 'parent_file', [ $this, 'filter_parent_file' ], 100 );

	    // Sub menu highlight fix.
		add_filter( 'submenu_file', [ $this, 'filter_submenu_file' ], 100 );
	}

	/**
	 * Get the CPT post type slug.
	 *
	 * @since string
	 *
	 * @return string
	 */
	public function get_post_type() : string {
		return $this->post_type;
	}

	/**
	 * Get the parent file slug.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_parent_file() : string {
		$parent_file = apply_filters( 'tec_menus_parent_file', $this->parent_file, $this );

		return apply_filters( "tec_menus_{$this->get_slug()}_parent_file", $parent_file, $this );
	}

	/**
	 * Get the CPT post type OBJECT.
	 *
	 * @since TBD
	 *
	 * @return \WP_Post_Type|null The post type object for the CPT.
	 */
	public function get_post_type_object() : ?\WP_Post_Type {
		$pto = apply_filters(
			'tec_menus_post_type_object',
			get_post_type_object( $this->get_post_type() ),
			$this
		);

		return apply_filters(
			"tec_menus_{$this->get_slug()}_post_type_object",
			get_post_type_object( $this->get_post_type() ),
			$pto,
			$this
		);
	}

	/**
	 * Callback MUST be empty for CPT edit pages.
	 *
	 * @since TBD
	 *
	 * @return string|callable
	 */
	public function get_callback() : string|callable|null {
		return null;
	}

	/**
	 * Get the URL slug for the CPT edit page.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_slug() : string {
		return 'edit.php?post_type=' . $this->get_post_type();
	}

	/**
	 * Get the URL slug for the CPT edit page.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_new_post_slug() : string {
		return 'post-new.php?post_type=' . $this->get_post_type();
	}

	/**
	 * Get the URL to the CPT list table page.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_list_url() : string {
		return add_query_arg(
			[ 'post_type' => $this->get_post_type() ],
			admin_url( 'edit.php' )
		);
	}

	/**
	 * Get the "Add New" URL for the CPT.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_new_url() : string {
		return add_query_arg(
			[ 'post_type' => $this->get_post_type() ],
			admin_url( 'post-new.php' )
		);
	}

	/**
	 * Do we want to show an "Add New" menu item for our CPT?
	 *
	 * @since TBD
	 *
	 * @return boolean
	 */
	public function should_use_new_post_menu() : bool {
		// Allow setting (nonfilterable!) as a param.
		if ( isset( $this->add_new_menu ) ) {
			return $this->add_new_menu;
		}

		$add_new_menu = apply_filters( 'tec_menu_cpt_should_use_new_post_menu', false, $this );

		return apply_filters( "tec_menu_cpt_{$this->get_post_type()}_should_use_new_post_menu", $add_new_menu );
	}

	/**
	 * Adds the "Add New" menu item to our custom menu.
	 *
	 * @since TBD
	 */
	public function register_new_post_menu() {
		if ( ! $this->should_use_new_post_menu() ) {
 			return;
		}

		$labels = get_post_type_labels( $this->get_post_type_object() );

		add_submenu_page(
			$this->get_parent_slug(),
			$labels->new_item,
			$labels->add_new_item,
			$this->get_capability(),
			$this->get_new_post_slug(),
			$this->get_callback(), // should be null|empty string.
			$this->get_position() // Will automatically get added after the post type submenu.
		);
	}

	/**
	 * Fix parent admin menu item highlight.
	 *
	 * @since TBD
	 *
	 * @param string $parent_file
	 *
	 * @return ?string $parent_file
	 */
	public function filter_parent_file( $parent_file ) : ?string {
		/* Get current screen */
		global $current_screen;

		if ( ! in_array( $current_screen->base, [ 'edit' ] ) ) {
			return $parent_file;
		}

		if ( $this->get_post_type() !== $current_screen->post_type ) {
			return $parent_file;
		}

		return $this->get_parent_file();
	}

	/**
	 * Fix self admin menu item highlight.
	 *
	 * @since TBD
	 *
	 * @param string $submenu_file
	 *
	 * @return ?string $submenu_file
	 */
	public function filter_submenu_file( $submenu_file ) : ?string {
		global $current_screen;

		if ( ! in_array( $current_screen->base, [ 'edit', 'post' ] ) ) {
			return $submenu_file;
		}

		if ( $this->get_post_type() !== $current_screen->post_type ) {
			return $submenu_file;
		}

		if (
			$this->should_use_new_post_menu()
			&& $current_screen->action === 'add'
		) {
			return $this->get_new_post_slug();
		}

		return $this->get_slug();
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_url() : string {
		return $this->get_list_url();
	}
}
