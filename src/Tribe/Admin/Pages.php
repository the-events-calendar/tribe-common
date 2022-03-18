<?php
namespace Tribe\Admin;

/**
 * Class Pages.
 *
 * @since TBD
 */
class Pages {
	/**
	 * Current page ID (or false if not registered with this controller).
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	private $current_page = null;

	/**
	 * Registered pages
	 * Contains information (breadcrumbs, menu info) about TEC admin pages.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	private $pages = [];

	/**
	 * Get registered pages.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function get_pages() {
		/**
		 * Filters the list of registered TEC admin pages.
		 *
		 * @since TBD
		 *
		 * @param array $pages The pages.
		 */
		$pages = apply_filters( 'tec_admin_pages', $this->pages );

		return $pages;
	}

	/**
	 * Adds a page to `tec-admin`.
	 *
	 * @since TBD
	 *
	 * @param array $options {
	 *   Array describing the page.
	 *
	 *   @type string      id           Id to reference the page.
	 *   @type string      title        Page title. Used in menus and breadcrumbs.
	 *   @type string|null parent       Parent ID. Null for new top level page.
	 *   @type string      path         Path for this page, full path in app context; ex /analytics/report
	 *   @type string      capability   Capability needed to access the page.
	 *   @type string      icon         Icon. Dashicons helper class, base64-encoded SVG, or 'none'.
	 *   @type int         position     Menu item position.
	 *   @type int         order        Navigation item order.
	 * }
	 */
	public function register_page( $options ) {
		$defaults = [
			'id'         => null,
			'parent'     => null,
			'title'      => '',
			'capability' => self::get_capability(),
			'path'       => '',
			'icon'       => '',
			'position'   => null,
			'callback'   => array( __CLASS__, 'render_page' ),
		];

		$options = wp_parse_args( $options, $defaults );

		if ( is_null( $options['parent'] ) ) {
			$page = add_menu_page(
				$options['title'],
				$options['title'],
				$options['capability'],
				$options['path'],
				$options['callback'],
				$options['icon'],
				$options['position']
			);
		} else {
			$page = add_submenu_page(
				$options['parent'],
				$options['title'],
				$options['title'],
				$options['capability'],
				$options['path'],
				$options['callback'],
			);
		}

		$this->connect_page( $options );

		return $page;
	}

	/**
	 * Get the current page.
	 *
	 * @since TBD
	 *
	 * @return array|boolean Current page or false if not registered with this controller.
	 */
	public function get_current_page() {
		if ( is_null( $this->current_page ) ) {
			$this->determine_current_page();
		}

		return $this->current_page;
	}

	/**
	 * Determine the current page.
	 *
	 * @since TBD
	 *
	 * @return string|boolean Current page or false if not registered with this controller.
	 */
	public function determine_current_page() {
		$current_screen = get_current_screen();

		if ( is_null( $current_screen ) ) {
			$this->current_page = isset( $_GET['page'] ) ? $_GET['page'] : null;
			return $this->current_page;
		}

		$this->current_page = $current_screen->id;

		return $this->current_page;
	}

	/**
	 * Connect an existing page to wc-admin.
	 *
	 * @since TBD
	 *
	 * @param array $options {
	 *   Array describing the page.
	 *
	 *   @type string       id           Id to reference the page.
	 *   @type string|array title        Page title. Used in menus and breadcrumbs.
	 *   @type string|null  parent       Parent ID. Null for new top level page.
	 *   @type string       path         Path for this page. E.g. admin.php?page=wc-settings&tab=checkout
	 *   @type string       capability   Capability needed to access the page.
	 *   @type string       icon         Icon. Dashicons helper class, base64-encoded SVG, or 'none'.
	 *   @type int          position     Menu item position.
	 * }
	 */
	public function connect_page( $options ) {
		if ( ! is_array( $options['title'] ) ) {
			$options['title'] = array( $options['title'] );
		}

		/**
		 * Filter the options when connecting or registering a page.
		 *
		 * @param array $options {
		 *   Array describing the page.
		 *
		 *   @type string       id           Id to reference the page.
		 *   @type string|array title        Page title. Used in menus and breadcrumbs.
		 *   @type string|null  parent       Parent ID. Null for new top level page.
		 *   @type string       screen_id    The screen ID that represents the connected page. (Not required for registering).
		 *   @type string       path         Path for this page. E.g. admin.php?page=wc-settings&tab=checkout
		 *   @type string       capability   Capability needed to access the page.
		 *   @type string       icon         Icon. Dashicons helper class, base64-encoded SVG, or 'none'.
		 *   @type int          position     Menu item position.
		 *   @type boolean      js_page      If this is a JS-powered page.
		 * }
		 */
		$options = apply_filters( 'tec_admin_pages_connect_page_options', $options );

		$this->pages[ $options['id'] ] = $options;
	}

	/**
	 * Get the capability.
	 *
	 * @since TBD
	 *
	 * @return string|null
	 */
	public static function get_capability( $capability = 'manage_options' ) {
		/**
		 * Filters the default capability for Tribe admin pages.
		 *
		 * @todo: We'll need to deprecate this one in favor of the one below.
		 */
		$capability = apply_filters( 'tribe_common_event_page_capability', $capability );

		/**
		 * Filters the default capability for TEC admin pages.
		 *
		 * @since TBD
		 */
		$capability = apply_filters( 'tec_admin_pages_capability', $capability );

		return $capability;
	}

	/**
	 * Define if is a `tec` admin page (registered).
	 *
	 * @return boolean
	 */
	public function is_tec_page() {
		// @todo @juanfra: Check if it's part of the array of `tec_admin_pages`.
		return true;
	}

	/**
	 * Get pages with tabs.
	 *
	 * @param array $pages The list of pages with tabs.
	 * @return array $pages The list of pages with tabs, filtered.
	 */
	public function get_pages_with_tabs( $pages = [] ) {
		/**
		* Filters the pages with tabs.
		*
		* @param array $pages Pages with tabs.
		*
		* @since TBD
		*/
		return apply_filters(
			'tec_admin_pages_with_tabs',
			$pages
		);
	}

	/**
	 * Check if the current page has tabs.
	 *
	 * @param string $page The page slug.
	 * @return boolean True if the page has tabs, false otherwise.
	 */
	public function has_tabs( $page = '' ) {
		if ( empty( $page ) ) {
			$page = $this->get_current_page();
		}

		return in_array( $page, $this->get_pages_with_tabs() );
	}

	/**
	 * Generic page.
	 *
	 * @since TBD
	 */
	public static function render_page() {
		?>
		<div class="wrap"></div>
		<?php
	}
}