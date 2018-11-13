<?php

/**
 * Class Tribe__Service_Providers__Processes
 *
 * @since 4.7.12
 *
 * Handles the registration and creation of our async process handlers.
 */
class Tribe__Service_Providers__Promoter_Connector extends tad_DI52_ServiceProvider {

	/**
	 * Binds and sets up implementations.
	 */
	public function register() {
		tribe_singleton( 'common.promoter.view', 'Tribe__Promoter__View' );
		tribe_singleton( 'common.promoter.rewrite', 'Tribe__Promoter__Rewrite' );
		tribe_singleton( 'common.promoter.connector', 'Tribe__Promoter__Connector' );
		tribe_singleton( 'common.promoter.auth', 'Tribe__Promoter__Auth' );
		$this->hook();
	}

	private function hook() {
		add_action( 'template_redirect', array( tribe( 'common.promoter.view' ), 'display_auth_check_view' ), 10, 0 );
		add_action( 'tribe_common_promoter_pre_rewrite', array( tribe( 'common.promoter.rewrite' ), 'generate_core_rules' ) );
		add_action( 'init', array( tribe( 'common.promoter.rewrite' ), 'add_rewrite_tags' ) );
		add_filter( 'generate_rewrite_rules', array( tribe( 'common.promoter.rewrite' ), 'filter_generate' ) );
		add_filter( 'rewrite_rules_array', array( tribe( 'common.promoter.rewrite' ), 'remove_percent_placeholders' ), 25 );
		add_action( 'save_post', array( tribe( 'common.promoter.connector' ), 'notify_promoter_of_changes' ), 10, 1 );
	}
}
