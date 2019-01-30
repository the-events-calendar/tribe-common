<?php

/**
 * Class Tribe__Service_Providers__Processes
 *
 * @since 4.9
 *
 * Handles the registration and creation of our async process handlers.
 */
class Tribe__Service_Providers__Promoter_Connector extends tad_DI52_ServiceProvider {

	/**
	 * Binds and sets up implementations.
	 */
	public function register() {
		tribe_singleton( 'promoter.auth', 'Tribe__Promoter__Auth' );
		tribe_singleton( 'promoter.connector', 'Tribe__Promoter__Connector' );
		tribe_singleton( 'promoter.pue', 'Tribe__Promoter__PUE', array( 'load' ) );
		tribe_singleton( 'promoter.view', 'Tribe__Promoter__View' );

		$this->hook();
	}

	/**
	 * Setup hooks for classes.
	 */
	private function hook() {
		add_action( 'template_redirect', tribe_callback( 'promoter.view', 'display_auth_check_view' ), 10, 0 );
		add_action( 'init', tribe_callback( 'promoter.view', 'add_rewrites' ) );
		add_action( 'save_post', tribe_callback( 'promoter.connector', 'notify_promoter_of_changes' ), 10, 1 );

		// Add early-firing filter for user auth on REST.
		add_filter( 'determine_current_user', tribe_callback( 'promoter.connector', 'authenticate_user_with_connector' ), 20, 1 );

		tribe( 'promoter.pue' );
	}
}
