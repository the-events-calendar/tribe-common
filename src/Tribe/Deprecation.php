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
	 * Use the format `<new_filter_tag> => array(<version>, <deprecated_filter_tag>)`.
	 * e.g. `'tribe_current' => array ('4.3', 'tribe_deprecated')`
	 *
	 * For performance reasons this array is manually set and **not**
	 * dynamically populated.
	 *
	 * @var array
	 */
	protected $deprecated_filters = array(
		'tribe_cost_regex' => array( '4.3', 'tribe_events_cost_regex' ),
	);

	/**
	 * An array specifying the tag, version and optional replacements
	 * for deprecated actions.
	 *
	 * Use the format `<new_action_tag> => array(<version>, <deprecated_action_tag>)`.
	 * e.g. `'tribe_current' => array ('4.3', 'tribe_deprecated')`
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

	/**
	 * Hooks the deprecation notices for actions.
	 *
	 * @internal
	 */
	public function deprecate_actions() {
		foreach ( array_keys( $this->deprecated_actions ) as $deprecated_action ) {
			add_action( $deprecated_action, array( $this, 'deprecated_action_message' ), 99 );
		}
	}

	/**
	 * Hooks the deprecation notices for filters.
	 *
	 * @internal
	 */
	public function deprecate_filters() {
		foreach ( array_keys( $this->deprecated_filters ) as $deprecated_filter ) {
			add_filter( $deprecated_filter, array( $this, 'deprecated_filter_message' ), 99 );
		}
	}

	/**
	 * Triggers a deprecation notice if there is any callback hooked on a deprecated action.
	 */
	public function deprecated_action_message() {
		$action         = current_action();
		$deprecated_tag = $this->deprecated_actions[ $action ][1];
		if ( has_action( $deprecated_tag ) ) {
			_deprecated_function(
				'The ' . $deprecated_tag . ' action', $this->deprecated_actions[ $action ][0], $action
			);
		}
	}

	/**
	 * Triggers a deprecation notice if there is any callback hooked on a deprecated filter.
	 */
	public function deprecated_filter_message() {
		$filter         = current_filter();
		$deprecated_tag = $this->deprecated_filters[ $filter ][1];
		if ( has_filter( $deprecated_tag ) ) {
			_deprecated_function(
				'The ' . $deprecated_tag . ' filter', $this->deprecated_filters[ $filter ][0], $filter
			);
		}
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
