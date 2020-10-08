<?php
/**
 * Widgets manager for Tribe plugins.
 *
 * @package Tribe\Widget
 * @since   TBD
 */

namespace Tribe\Widget;

/**
 * Class Widget Manager.
 *
 * @package Tribe\Widget
 *
 * @since  TBD
 */
class Manager {

	/**
	 * Current widgets.
	 *
	 * @since TBD
	 *
	 * @var array $current_widget An array containing the current widgets being executed.
	 */
	public $current_widget = [];

	/**
	 * Get the list of widgets available for handling.
	 *
	 * @since  TBD
	 *
	 * @return array An associative array of widgets in the shape `[ <slug> => <class> ]`
	 */
	public function get_registered_widgets() {
		$widgets = [];

		/**
		 * Allow the registering of widgets into the our Tribe plugins.
		 *
		 * @since  TBD
		 *
		 * @var array An associative array of widgets in the shape `[ <slug> => <class> ]`.
		 */
		$widgets = apply_filters( 'tribe_widgets', $widgets );

		return $widgets;
	}

	/**
	 * Verifies if a given widget slug is registered for handling.
	 *
	 * @since  TBD
	 *
	 * @param  string $slug Which slug we are checking if is registered.
	 *
	 * @return bool Whether a widget is registered or not.
	 */
	public function is_widget_registered( $slug ) {
		$registered_widgets = $this->get_registered_widgets();
		
		return isset( $registered_widgets[ $slug ] );
	}

	/**
	 * Verifies if a given widget class name is registered for handling.
	 *
	 * @since  TBD
	 *
	 * @param  string $class_name Which class name we are checking if is registered.
	 *
	 * @return bool Whether a widget is registered, by class.
	 */
	public function is_widget_registered_by_class( $class_name ) {
		$registered_widgets = $this->get_registered_widgets();
		
		return in_array( $class_name, $registered_widgets );
	}

	/**
	 * Add new widgets handler to catch the correct strings.
	 *
	 * @since  TBD
	 */
	public function register_widgets() {
		$registered_widgets = $this->get_registered_widgets();

		// Add to WordPress all of the registered Widgets
		foreach ( $registered_widgets as $widget => $class_name ) {
			register_widget( $class_name );
		}
	}

	/**
	 * Remove Widget from WordPress widget register by class name.
	 *
	 * @since  TBD
	 *
	 * @param string $class_name The class name of the widget to unregister.
	 */
	public function unregister_widget( $class_name ) {
		unregister_widget( $class_name );
	}
}
