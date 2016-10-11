<?php


abstract class Tribe__Tabbed_View {

	/**
	 * A list of all the tabs registered for the tabbed view.
	 *
	 * @var array An associative array in the [<slug> => <instance>] format.
	 */
	protected $items = array();

	/**
	 * The slug of the default tab
	 *
	 * @var string
	 */
	protected $default_tab;

	/**
	 * @var string The absolute path to this tabbed view template file.
	 */
	protected $template;

	/**
	 * An array or value object of data that should be used to render the tabbed view.
	 *
	 * @var array|object
	 */
	protected $data = array();

	/**
	 * Returns the main admin settings URL.
	 *
	 * @param array|string $args     Query String or Array with the arguments
	 * @param boolean      $relative Return a relative URL or absolute
	 *
	 * @return string
	 */
	abstract public function get_url( $args, $relative );

	/**
	 * @return string
	 */
	public function get_template() {
		return $this->template;
	}

	/**
	 * @param string $template
	 */
	public function set_template( $template ) {
		$this->template = $template;
	}

	/**
	 * A method to sort tabs by priority
	 *
	 * @access private
	 *
	 * @param  object $a First tab to compare
	 * @param  object $b Second tab to compare
	 *
	 * @return int
	 */
	protected function _sort_by_priority( $a, $b ) {
		if ( $a->priority == $b->priority ) {
			return 0;
		}

		return ( $a->priority < $b->priority ) ? - 1 : 1;
	}

	/**
	 * Removes a tab from the tabbed view items.
	 *
	 * @param  string $slug The slug of the tab to remove
	 *
	 * @return boolean `true` if the slug was registered and removed, `false` otherwise
	 */
	public function remove( $slug ) {
		if ( ! $this->exists( $slug ) ) {
			return false;
		}

		unset( $this->items[ $slug ] );

		return true;
	}

	/**
	 * Checks if a given tab exist
	 *
	 * @param  string $slug The slug of the tab
	 *
	 * @return boolean
	 */
	public function exists( $slug ) {
		return is_object( $this->get( $slug ) ) ? true : false;
	}

	/**
	 * Fetches the Instance of the Tab or all the tabs
	 *
	 * @param  string $slug (optional) The Slug of the Tab
	 *
	 * @return null|array|object        If we couldn't find the tab it will be null, if the slug is null will return all tabs
	 */
	public function get( $slug = null ) {
		// Sort Tabs by priority
		uasort( $this->items, array( $this, '_sort_by_priority' ) );

		if ( is_null( $slug ) ) {
			return $this->items;
		}

		// Prevent weird stuff here
		$slug = sanitize_title_with_dashes( $slug );

		if ( ! empty( $this->items[ $slug ] ) ) {
			return $this->items[ $slug ];
		}

		return null;
	}

	/**
	 * Checks if a given Tab (slug) is active
	 *
	 * @param  string $slug The Slug of the Tab
	 *
	 * @return boolean       Is this tab active?
	 */
	public function is_active( $slug = null ) {
		$slug = $this->get_requested_slug( $slug );
		$tab  = $this->get_active();

		return $slug === $tab->get_slug();
	}

	/**
	 * Returns the slug of tab requested in the `_GET` array or the default one.
	 *
	 * @param string|null $slug
	 * @param mixed       $default A default value to return if the tab was not requested.
	 *
	 * @return string|bool Either the slug of the requested tab or `false` if no slug was requested
	 *                     and no default tab is set.
	 */
	protected function get_requested_slug( $slug = null, $default = null ) {
		if ( is_null( $slug ) ) {
			$default = null === $default ? $this->get_default_tab() : $default;
			// Set the slug
			$slug = ! empty( $_GET['tab'] ) && $this->exists( $_GET['tab'] ) ? $_GET['tab'] : $default;
		}

		return $slug;
	}

	/**
	 * Fetches the current active tab instance.
	 *
	 * @return Tribe__Tabbed_View__Tab
	 */
	public function get_active() {
		$tab = ! empty( $_GET['tab'] ) && $this->exists( $_GET['tab'] ) ? $_GET['tab'] : $this->get_default_tab();

		// Return the active tab or the default one
		return $this->get( $tab );
	}

	/**
	 * Returns the slug of the default tab for this tabbed view.
	 *
	 * @return string The slug of the default tab, the slug of the first tab if
	 *                a default tab is not set, `false` otherwise.
	 */
	public function get_default_tab() {
		if ( ! empty( $this->default_tab ) ) {
			return $this->default_tab;
		}

		$tabs = $this->get_tabs();

		if ( empty( $tabs ) ) {
			return false;
		}

		return reset( $tabs )->get_slug();
	}

	/**
	 * @param Tribe__Tabbed_View__Tab|string $tab
	 *
	 * @return Tribe__Tabbed_View__Tab
	 */
	public function register( $tab ) {
		$is_object = is_a( $tab, 'Tribe__Tabbed_View__Tab' );
		if ( ! ( $is_object || is_string( $tab ) ) ) {
			return false;
		}

		if ( ! $is_object ) {
			$tab = $this->get_new_tab_instance( $tab );
		}

		// Set the Tab Item on the array of Tabs
		$this->items[ $tab->get_slug() ] = $tab;

		// Return the tab
		return $tab;
	}

	/**
	 * Returns all the registered tabs.
	 *
	 * @return Tribe__Tabbed_View__Tab[]
	 */
	public function get_tabs() {
		return array_values( $this->items );
	}

	/**
	 * Builds an instance of the specified tab class.
	 *
	 * @param string $tab
	 *
	 * @return Tribe__Tabbed_View__Tab
	 */
	protected function get_new_tab_instance( $tab ) {
		$tab = call_user_func( array( $tab, '__construct' ) );

		return $tab;
	}

	/**
	 * Renders the tabbed view and returns the resulting HTML.
	 *
	 * @return string
	 */
	public function render() {
		ob_start();

		$template = $this->template;

		if ( empty( $template ) ) {
			return '';
		}

		$default_data = array(
			'tabbed_view' => $this,
		);

		$data = array_merge( $default_data, (array) $this->data );

		extract( $data );

		include $template;

		$html = ob_get_clean();

		return $htm	}
}