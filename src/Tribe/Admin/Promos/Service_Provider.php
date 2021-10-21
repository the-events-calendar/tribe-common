<?php
/**
 * Handles admin promo functions.
 *
 * @since   TBD
 * @package Tribe\Admin\Promos;
 */

namespace Tribe\Admin\Promos;

/**
 * Promos Provider.
 *
 * @since TBD
 */
class Service_Provider extends \tad_DI52_ServiceProvider {

	/**
	 * Registers the objects and filters required by the provider to manage admin notices.
	 *
	 * @since TBD
	 */
	public function register() {
		tribe_singleton(  Black_Friday::class, Black_Friday::class, ['hook'] );

		$this->hooks();
	}

	/**
	 * Set up hooks for classes.
	 *
	 * @since TBD
	 */
	private function hooks() {
		add_action( 'tribe_plugins_loaded', [ $this, 'plugins_loaded' ] );
	}

	/**
	 * Setup for things that require plugins loaded first.
	 *
	 * @since TBD
	 */
	public function plugins_loaded() {
		tribe( Black_Friday::class );
	}
}
