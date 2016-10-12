<?php


class Tribe__Tabbed_View__Tab {

	/**
	 * To Order the Tabs on the UI you need to change the priority
	 *
	 * @var integer
	 */
	public $priority = 50;

	/**
	 * An array or value object of data that should be used to render the tabbed view.
	 *
	 * @var array|object
	 */
	protected $data = array();

	/**
	 * The template file that should be used to render the tab.
	 *
	 * @var string
	 */
	protected $template;

	/**
	 * @var Tribe__Tabbed_View
	 */
	protected $tabbed_view;

	/**
	 * @var string
	 */
	protected $slug;

	/**
	 * @var bool
	 */
	protected $visible = true;

	/**
	 * @var string
	 */
	protected $label = '';

	/**
	 * @var string
	 */
	protected $url = '';

	/**
	 * Tribe__Tabbed_View__Tab constructor.
	 *
	 * @param Tribe__Tabbed_View $tabbed_view
	 * @param string             $slug
	 */
	public function __construct( Tribe__Tabbed_View $tabbed_view, $slug = null ) {
		$this->tabbed_view = $tabbed_view;
		$this->slug        = ! empty( $slug ) ? $slug : $this->slug;
	}

	public function get_priority() {
		return $this->priority;
	}

	public function set_priority( $priority ) {
		$this->priority = $priority;
	}

	public function get_data() {
		return $this->data;
	}

	/**
	 * @param array $data
	 */
	public function set_data( $data ) {
		$this->data = $data;
	}

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
	 * Enforces a method to display the tab or not
	 *
	 * @return boolean
	 */
	public function is_visible() {
		return $this->visible;
	}

	/**
	 * Enforces a method to return the Label of the Tab
	 *
	 * @return string
	 */
	public function get_label() {
		return $this->label;
	}

	/**
	 * Creates a way to include the this tab HTML easily
	 *
	 * @return string Content of the tab
	 */
	public function render() {
		if ( empty( $this->template ) ) {
			$this->template = Tribe__Main::instance()->plugin_path . '/src/admin-views/tabbed-view/tab.php';
		}

		$template = $this->template;

		if ( empty( $template ) ) {
			return '';
		}

		$default_data = array(
			'tab' => $this,
		);

		$data = array_merge( $default_data, (array) $this->data );

		extract( $data );

		ob_start();

		include $template;

		$html = ob_get_clean();

		return $html;
	}

	/**
	 * Fetches the link to this tab
	 *
	 * @param array|string $args     Query String or Array with the arguments
	 * @param boolean      $relative Return a relative URL or absolute
	 *
	 * @return string
	 */
	public function get_url( $args = array(), $relative = false ) {
		if ( ! empty( $this->url ) ) {
			return $this->url;
		}

		$defaults = array(
			'tab' => $this->get_slug(),
		);

		// Allow the link to be "changed" on the fly
		$args = wp_parse_args( $args, $defaults );

		// Escape after the filter
		return $this->tabbed_view->get_url( $args, $relative );
	}

	/**
	 * Enforces a method to return the Tab Slug
	 *
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * Determines if this Tab is currently displayed
	 *
	 * @return boolean
	 */
	public function is_active() {
		$active = $this->tabbed_view->get_active();

		return ! empty( $active ) ? $this->get_slug() === $active->get_slug() : false;
	}

	/**
	 * @param Tribe__Tabbed_View $tabbed_view
	 *
	 * @return Tribe__Tabbed_View__Tab
	 */
	public function set_tabbed_view( $tabbed_view ) {
		$this->tabbed_view = $tabbed_view;

		return $this;
	}

	/**
	 * @param boolean $visible
	 */
	public function set_visible( $visible ) {
		$this->visible = $visible;
	}

	/**
	 * @param string $label
	 */
	public function set_label( $label ) {
		$this->label = $label;
	}

	/**
	 * @param string $url
	 */
	public function set_url( $url ) {
		$this->url = $url;
	}
}