<?php
/*
Plugin Name: Dependency Test plugin
Description: a dependency check test plugin.
*/

define('DT_FILE', __FILE__);

add_action( 'tribe_common_loaded', 'dt_register_plugin' );
function dt_register_plugin() {
	include_once dirname( __FILE__ ) . '/src/Plugin.php';
	include_once dirname( __FILE__ ) . '/src/Plugin_Register.php';
	$plugin_register = new DT_Plugin_Register();
	$plugin_register->add_active_plugin();
	tribe_check_plugin( 'DT_Plugin' );
}
