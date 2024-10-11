<?php
/**
 * Handles admin conditional content.
 *
 * @since   4.14.7
 * @package Tribe\Admin\Conditional_Content;
 */

namespace TEC\Common\Admin\Conditional_Content;

use TEC\Common\Contracts\Service_Provider as Provider_Contract;

/**
 * Conditional Content Controller.
 *
 * @since TBD
 */
class Controller extends Provider_Contract {


	/**
	 * Registers the required objects and filters.
	 *
	 * @since TBD
	 */
	public function register() {
		// This is specifically for the admin, bail if we're not in the admin.
		if ( ! is_admin() ) {
			return;
		}

		$this->container->singleton(  Black_Friday::class, Black_Friday::class, [ 'hook' ] );

		$this->hooks();
	}

	/**
	 * Set up hooks for classes.
	 *
	 * @since 4.14.7
	 */
	protected function hooks(): void {
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
