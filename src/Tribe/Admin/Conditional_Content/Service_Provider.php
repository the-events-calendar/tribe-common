<?php
/**
 * Handles admin conditional content.
 *
 * @since   TBD
 * @package Tribe\Admin\Conditional_Content;
 */

namespace Tribe\Admin\Conditional_Content;

/**
 * Conditional Content Provider.
 *
 * @since TBD
 */
class Service_Provider extends \tad_DI52_ServiceProvider {

	/**
	 * Registers the required objects and filters.
	 *
	 * @since TBD
	 */
	public function register() {
		$this->container->singleton(  Black_Friday::class, Black_Friday::class, [ 'hook' ] );
		$this->hooks();
	}

	/**
	 * Set up hooks for classes.
	 *
	 * @since TBD
	 */
	protected function hooks() {
		add_action( 'tribe_plugins_loaded', [ $this, 'plugins_loaded' ] );
	}

	/**
	 * Setup for things that require plugins loaded first.
	 *
	 * @since TBD
	 */
	public function plugins_loaded() {
		$this->container->make( Black_Friday::class );
	}
}
