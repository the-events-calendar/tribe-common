<?php
/*
Plugin Name: The Events Calendar REST API Tester
Plugin URI: https://theeventscalendar.com/
Description: Test The Events Calendar REST API with a fancy UI
Version: 0.1.0
Author: Modern Tribe
*/

include 'src/autoload.php';

// after TEC
add_action( 'plugins_loaded', 'trap_init', 99 );

function trap_notice() {
	?>
	<div class="error notice">
		<p><b>The Events Calendar plugin is not activated!</b></p>
		<p>The Events Calendar REST API Tester plugin will not work until The Events Calendar is not activated.</p>
	</div>
	<?php
}

function trap_init() {
	if ( ! class_exists( 'Tribe__Events__REST__V1__Main' ) ) {
		add_action( 'admin_notices', 'trap_notice' );

		return;
	}

	tribe()->setVar( 'trap.main-file', __FILE__ );
	tribe()->setVar( 'trap.templates', dirname( __FILE__ ) . '/src/templates' );

	tribe_singleton( 'trap.options', 'Tribe__RAP__Options_Page' );
	tribe_singleton( 'trap.endpoint.nonce', 'Tribe__RAP__Endpoints__Nonce' );

	add_action( 'admin_menu', array( tribe( 'trap.options' ), 'register_menu' ) );
	add_action( 'admin_enqueue_scripts', array( tribe( 'trap.options' ), 'enqueue_scripts' ) );
	add_action( 'rest_api_init', array( tribe( 'trap.endpoint.nonce' ), 'register' ) );
	add_filter( 'determine_current_user', array( tribe( 'trap.endpoint.nonce' ), 'set_current_user' ) );
}
