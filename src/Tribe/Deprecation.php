<?php


/**
 * Class Tribe__Deprecation
 *
 * Utilities to deprecate code.
 */
class Tribe__Deprecation {

	/**
	 * @var self
	 */
	protected static $instance;

	/**
	 * An array specifying the tag, version and optional replacements
	 * for deprecated filters.
	 *
	 * Use the format `<tag> => array(<version> [, <replacement>])`.
	 * e.g. `'tribe_deprecated' => array ('4.3', 'tribe_use_this')`
	 * e.g. `'tribe_deprecated' => array ('4.3')`
	 *
	 * For performance reasons this array is manually set and **not**
	 * dynamically populated.
	 *
	 * @var array
	 */
	protected $deprecated_filters = array();

	/**
	 * An array specifying the tag, version and optional replacements
	 * for deprecated actions.
	 *
	 * Use the format `<tag> => array(<version> [, <replacement>])`.
	 * e.g. `'tribe_deprecated' => array ('4.3', 'tribe_use_this')`
	 * e.g. `'tribe_deprecated' => array ('4.3')`
	 *
	 * For performance reasons this array is manually set and **not**
	 * dynamically populated.
	 *
	 * @var array
	 */
	protected $deprecated_actions = array();

	/**
	 * @return Tribe__Deprecation
	 */
	public static function instance() {
		if ( empty( self::$instance ) ) {
			$instance = new self();

			$instance->deprecate_actions();
			$instance->deprecate_filters();

			self::$instance = $instance;
		}

		return self::$instance;
	}

	protected function deprecate_actions() {
		foreach ( array_keys( $this->deprecated_actions ) as $deprecated_action ) {
			add_action( $deprecated_action, array( $this, 'deprecated_action_message' ), 1 );
		}
	}

	protected function deprecate_filters() {
		foreach ( array_keys( $this->deprecated_filters ) as $deprecated_filter ) {
			add_filter( $deprecated_filter, array( $this, 'deprecated_filter_message' ), 1 );
		}
	}

	public function deprecated_action_message() {
		$action      = current_action();
		$replacement = ! empty( $this->deprecated_actions[ $action ][1] ) ? $this->deprecated_actions[ $action ][1] :
			null;
		_deprecated_function( 'The ' . $action . 'action', $this->deprecated_actions[ $action ][0], $replacement );
	}

	public function deprecated_filter_message() {
		$filter      = current_filter();
		$replacement = ! empty( $this->deprecated_filters[ $filter ][1] ) ? $this->deprecated_filters[ $filter ][1] :
			null;
		_deprecated_function( 'The ' . $filter . 'filter', $this->deprecated_filters[ $filter ][0], $replacement );
	}

	/**
	 * @param array $deprecated_filters
	 *
	 * @internal
	 */
	public function set_deprecated_filters( $deprecated_filters ) {
		$this->deprecated_filters = $deprecated_filters;
	}

	/**
	 * @param array $deprecated_actions
	 *
	 * @internal
	 */
	public function set_deprecated_actions( $deprecated_actions ) {
		$this->deprecated_actions = $deprecated_actions;
	}
}
