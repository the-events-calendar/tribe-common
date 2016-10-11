<?php


abstract class Tribe__Tabbed_View__Tab {

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

	public function __construct( Tribe__Tabbed_View $tabbed_view ) {
		$this->tabbed_view = $tabbed_view;
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
	abstract public function is_visible();

	/**
	 * Enforces a method to return the Label of the Tab
	 *
	 * @return string
	 */
	abstract public function get_label();

	/**
	 * Creates a way to include the this tab HTML easily
	 *
	 * @return string Content of the tab
	 */
	public function render() {

		ob_start();

		$template = $this->template;

		if ( empty( $template ) ) {
			return '';
		}

		$default_data = array(
			'tab' => $this,
		);

		$data = array_merge( $default_data, (array) $this->data );

		extract( $data );

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
	abstract public function get_slug();

	/**
	 * Determines if this Tab is currently displayed
	 *
	 * @return boolean
	 */
	public function is_active() {
		return $this->slug === $this->tabbed_view->get_active()->get_slug();
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
}