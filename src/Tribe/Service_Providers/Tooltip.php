<?php

/**
 * Class Tribe__Service_Providers__Tooltip
 *
 * @since 4.9.8
 *
 * Handles the registration and creation of our async process handlers.
 */
class Tribe__Service_Providers__Tooltip extends tad_DI52_ServiceProvider {

	/**
	 * Binds and sets up implementations.
	 *
	 * @since 4.9.8
	 */
	public function register() {
		tribe_singleton( 'tooltip.view', 'Tribe__Tooltip__View' );

		$this->hook();
	}

	/**
	 * Setup hooks for classes.
	 *
	 * @since 4.9.8
	 */
	private function hook() {
		add_action( 'tribe_common_loaded', [ $this, 'add_tooltip_assets' ] );
	}

	/**
	 * Register assets associated with tooltip
	 *
	 * @since 4.9.8
	 */
	public function add_tooltip_assets() {
		wp_enqueue_style(
			'tribe-tooltip',
			plugins_url( 'resources/css/', dirname( dirname( __FILE__ ) ) ) .'tooltip.css'
		);
	}
}
