<?php

interface Tribe__Editor__Blocks__Interface {

	/**
	 * Which is the name/slug of this block
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function slug();

	/**
	 * Which is the name/slug of this block
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function name();

	/**
	 * What are the default attributes for this block
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function default_attributes();

	/**
	 * Since we are dealing with a Dynamic type of Block we need a PHP method to render it
	 *
	 * @since TBD
	 *
	 * @param  array $attributes
	 *
	 * @return string
	 */
	public function render( $attributes = array() );

	/**
	 * Does the registration for PHP rendering for the Block, important due to been
	 * an dynamic Block
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function register();

	/**
	 * Used to include any Assets for the Block we are registering
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function assets();

	/**
	 * Fetches which ever is the plugin we are dealing with
	 *
	 * @since TBD
	 *
	 * @return mixed
	 */
	public function plugin();

	/**
	 * Attach any specific hook to the current block.
	 *
	 * @since TBD
	 *
	 * @return mixed
	 */
	public function hook();
}
