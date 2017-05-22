<?php

/**
 * Interface Tribe__Formatter__Interface
 *
 * Models any class providing methods to format an input array into an output one.
 */
interface Tribe__Formatter__Interface {
	/**
	 * Processes an array of raw input validating, converting and pruning its elements.
	 *
	 * @param array $raw The input to format.
	 *
	 * @return array The formatted input.
	 *
	 * @throws InvalidArgumentException If a required argument is missing or not valid.
	 */
	public function process( array $raw = array() );

	/**
	 * Returns this formatter name.
	 *
	 * @return string
	 */
	public function get_name();

	/**
	 * Sets the formatter name.
	 *
	 * @param $name
	 */
	public function set_name( $name );

	/**
	 * Returns the format map the formatter is using.
	 *
	 * @return array
	 */
	public function get_format_map();

	/**
	 * Returns the formatter current context.
	 *
	 * @return array
	 */
	public function get_context();

	/**
	 * Sets the context for this formatter.
	 *
	 * The context will be used by the formatter to provide insightful error messages.
	 *
	 * @param array|string $context
	 */
	public function set_context( $context );

	/**
	 * Sets the format map for this formatter.
	 *
	 * @param array $format_map
	 */
	public function set_format_map( array $format_map );
}