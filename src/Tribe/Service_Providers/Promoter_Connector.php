<?php

/**
 * Class Tribe__Service_Providers__Processes
 *
 * @since TBD
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
		tribe_singleton( 'promoter.rewrite', 'Tribe__Promoter__Rewrite' );
		tribe_singleton( 'promoter.view', 'Tribe__Promoter__View' );

		$this->hook();
	}

	/**
	 * Setup hooks for classes.
	 */
	private function hook() {
		add_action( 'template_redirect', tribe_callback( 'promoter.view', 'display_auth_check_view' ), 10, 0 );
		add_action( 'tribe_common_promoter_pre_rewrite', tribe_callback( 'promoter.rewrite', 'generate_core_rules' ) );
		add_action( 'init', tribe_callback( 'promoter.rewrite', 'add_rewrite_tags' ) );
		add_filter( 'generate_rewrite_rules', tribe_callback( 'promoter.rewrite', 'filter_generate' ) );
		add_filter( 'rewrite_rules_array', tribe_callback( 'promoter.rewrite', 'remove_percent_placeholders' ), 25 );
		add_action( 'save_post', tribe_callback( 'promoter.connector', 'notify_promoter_of_changes' ), 10, 1 );

		tribe( 'promoter.pue' );
	}
}
