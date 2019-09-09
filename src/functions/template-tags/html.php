<?php
/**
 * HTML functions (template-tags) for use in WordPress templates.
 */
use Tribe\Utils\Element_Classes;

/**
 * Parse input values into a valid array of classes to be used in the templates.
 *
 * @since  4.9.13
 *
 * @param  mixed $classes,... unlimited Any amount of params to be rendered as classes.
 *
 * @return array
 */
function tribe_get_classes() {
	$element_classes = new Element_Classes( func_get_args() );
	return $element_classes->get_classes();
}

/**
 * Parses input values into a valid class html attribute to be used in the templates.
 *
 * @since  4.9.13
 *
 * @param  mixed $classes,... unlimited Any amount of params to be rendered as classes.
 *
 * @return string
 */
function tribe_classes() {
	$element_classes = new Element_Classes( func_get_args() );
	echo $element_classes->get_attribute();
}

/**
 * Get attributes for required fields.
 *
 * @since TBD
 *
 * @param boolean $required If the field is required.
 * @param boolean $echo     Whether to echo the string or return it.
 *
 * @return string|void If echo is false, returns $required_string.
 */
function tribe_required( $required, $echo = true ) {
	if ( $required ) {
		$required_string = 'required aria-required="true"';

		if ( ! $echo ) {
			return $required_string;
		} else {
			echo $required_string;
		}
	}
}

/**
 * Get string for required field labels.
 *
 * @since TBD
 *
 * @param boolean $required If the field is required.
 * @param boolean $echo     Whether to echo the string or return it.
 *
 * @return string|void If echo is false, returns $required_string.
 */
function tribe_required_label( $required, $echo = true ) {
	if ( $required ) {
		$required_string = '<span class="screen-reader-text">' . esc_html_x( '(required)', 'The associated field is required.', 'tribe-common' ) . '</span>';

		if ( ! $echo ) {
			return $required_string;
		} else {
			echo $required_string;
		}
	}
}
