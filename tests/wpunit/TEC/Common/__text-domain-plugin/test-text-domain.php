<?php
/**
 * Plugin Name: Test Text Domain
 * text-domain: test-text-domain
 */

add_action( 'plugins_loaded', function () {
	// List below the strings you would like to translate.
	__( 'test', 'test-text-domain' );
} );
