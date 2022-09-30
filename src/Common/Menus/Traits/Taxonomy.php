<?php
/**
 * Provides methods and properties for CPT (sub)menus.
 *
 * @since TBD
 *
 * @package TEC\Common\Menus
 */

namespace TEC\Common\Menus\Traits;

trait Taxonomy {
	protected $tax_slug = '';

	protected $post_type = null;


	/**
	 * {@inheritDoc}
	 */
	protected function tax_hooks() : void {
		// Parent menu highlight fix.
		add_filter( 'parent_file', [ $this, 'filter_parent_file' ], 100 );

	    // Sub menu highlight fix.
		add_filter( 'submenu_file', [ $this, 'filter_submenu_file' ], 100 );
	}

	/**
	 * Get the associated taxonomy slug.
	 *
	 * @since TBD
	 */
	public function get_tax_slug() : string {
		return $this->tax_slug;
	}

	/**
	 * Get the URL slug for the Taxonomy page.
	 *
	 * @since TBD
	 */
	public function get_slug() : string {
		$this->menu_slug = 'edit-tags.php?taxonomy=' . $this->get_tax_slug();

		if ( $this->get_post_type() ) {
			$this->menu_slug .= '&post_type=' . $this->get_post_type();
		}

		return $this->menu_slug;
	}

	/**
	 * Get the associated post type slug, if it exists.
	 *
	 * @since string
	 */
	public function get_post_type() : ?string {
		return $this->post_type;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_url() : string {
		$args = [
			'taxonomy' => $this->get_tax_slug(),
		];

		if ( $this->get_post_type() ) {
			$args['post_type'] = $this->get_post_type();
		}

		return add_query_arg(
			$args,
			admin_url( 'edit-tags.php' )
		);
	}

	/**
	 * Callback MUST be empty for Taxonomy edit pages.
	 *
	 * @since TBD
	 */
	public function get_callback() : string|callable|null {
		return null;
	}

	/**
	 * Get the parent file slug.
	 *
	 * @since TBD
	 */
	public function get_parent_file() : string {
		$parent_file = apply_filters( 'tec_menus_parent_file', $this->parent_file, $this );

		return apply_filters( "tec_menus_{$this->get_slug()}_parent_file", $parent_file, $this );
	}

	/**
	 * Fix parent admin menu item highlight.
	 *
	 * @since TBD
	 *
	 * @param string $parent_file
	 */
	public function filter_parent_file( $parent_file ) : ?string {
		/* Get current screen */
		global $current_screen;

		if ( ! in_array( $current_screen->base, [ 'edit-tags' ] ) ) {
			return $parent_file;
		}

		if ( ! empty( $this->get_post_type() ) && $this->get_post_type() !== $current_screen->post_type ) {
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
	 */
	public function filter_submenu_file( $submenu_file ) : ?string {
		global $current_screen;

		if ( ! in_array( $current_screen->base, [ 'edit-tags' ] ) ) {
			return $submenu_file;
		}

		if ( ! empty( $this->get_post_type() ) && $this->get_post_type() !== $current_screen->post_type ) {
			return $submenu_file;
		}

		if ( $this->get_tax_slug() !== $current_screen->taxonomy ) {
			return $submenu_file;
		}

		return $this->get_slug();
	}
}
