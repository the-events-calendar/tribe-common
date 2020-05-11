<?php

namespace Tribe\Shortcode;

/**
 * Interface Shortcode_Interface
 *
 * @package Tribe\Shortcode
 *
 * @since   4.12.0
 */
interface Shortcode_Interface {

	/**
	 * Returns the shortcode slug that allows the shortcode to be built via the shortcode class by slug.
	 *
	 * @since 4.12.0
	 *
	 * @return string The shortcode slug.
	 */
	public function get_registration_slug();

	/**
	 * Configures the base variables for an instance of shortcode.
	 *
	 * @since 4.12.0
	 *
	 * @param array|string  $arguments Set of arguments passed to the Shortcode at hand. Empty string if no args.
	 * @param string $content   Contents passed to the shortcode, inside of the open and close brackets.
	 */
	public function setup( $arguments, $content );

	/**
	 * Returns the arguments for the shortcode parsed correctly with defaults applied.
	 *
	 * @since 4.12.0
	 *
	 * @param array $arguments Set of arguments passed to the Shortcode at hand.
	 *
	 * @return array<string,mixed> The parsed shortcode arguments map.
	 */
	public function parse_arguments( $arguments );

	/**
	 * Returns the array of arguments for this shortcode after applying the validation callbacks.
	 *
	 * @since 4.12.0
	 *
	 * @param array $arguments Set of arguments passed to the Shortcode at hand.
	 *
	 * @return array<string,mixed> The validated shortcode arguments map.
	 */
	public function validate_arguments( $arguments );

	/**
	 * Returns the array of callbacks for this shortcode's arguments.
	 *
	 * @since 4.12.0
	 *
	 * @return array<string,mixed> A map of the shortcode arguments that have survived validation.
	 */
	public function get_validated_arguments_map();

	/**
	 * Returns a shortcode default arguments.
	 *
	 * @since 4.12.0
	 *
	 * @return array<string,mixed> The shortcode default arguments map.
	 */
	public function get_default_arguments();

	/**
	 * Returns a shortcode arguments after been parsed.
	 *
	 * @since 4.12.0
	 *
	 * @return array<string,mixed> The shortcode arguments, as set by the user in the shortcode string.
	 */
	public function get_arguments();

	/**
	 * Returns a shortcode argument after it has been parsed.
	 *
	 * @since 4.12.0
	 *
	 * @param array|string $index   Which index we indent to fetch from the arguments.
	 * @param array        $default Default value if it doesn't exist.
	 *
	 * @uses  Tribe__Utils__Array::get For index fetching and Default.
	 *
	 * @return mixed Value for the Index passed as the first argument.
	 */
	public function get_argument( $index, $default = null );

	/**
	 * Returns a shortcode's HTML.
	 *
	 * @since 4.12.0
	 *
	 * @return string The shortcode rendered HTML code.
	 */
	public function get_html();
}
