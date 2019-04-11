<?php

/**
 * Class Tribe__Service_Providers__Tooltip
 *
 * @since 4.9
 *
 * Handles the registration and creation of our async process handlers.
 */
class Tribe__Service_Providers__Tooltip extends tad_DI52_ServiceProvider {

	/**
	 * Binds and sets up implementations.
	 *
	 * @since TBD
	 */
	public function register() {
		tribe_singleton( 'tooltip.view', 'Tribe__Tooltip__View' );

		$this->hook();
	}

	/**
	 * Setup hooks for classes.
	 *
	 * @since TBD
	 */
	private function hook() {
		add_action( 'tribe_common_loaded', [ $this, 'add_tooltip_assets' ] );
	}

	/**
	 * Register assets associated with tooltip
	 *
	 * @since TBD
	 */
	public function add_tooltip_assets() {
		tribe_asset(
			Tribe__Main::instance(),
			'tooltip',
			'tooltip.css',
			[],
			[ 'wp_enqueue_scripts', 'admin_enqueue_scripts' ]
		);
	}
}
