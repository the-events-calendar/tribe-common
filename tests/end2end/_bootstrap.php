<?php

add_action( 'plugins_loaded', function () {
	remove_action( 'admin_init', '_maybe_update_core' );
	remove_action( 'wp_version_check', 'wp_version_check' );

	remove_action( 'load-plugins.php', 'wp_update_plugins' );
	remove_action( 'load-update.php', 'wp_update_plugins' );
	remove_action( 'load-update-core.php', 'wp_update_plugins' );
	remove_action( 'admin_init', '_maybe_update_plugins' );
	remove_action( 'wp_update_plugins', 'wp_update_plugins' );

	remove_action( 'load-themes.php', 'wp_update_themes' );
	remove_action( 'load-update.php', 'wp_update_themes' );
	remove_action( 'load-update-core.php', 'wp_update_themes' );
	remove_action( 'admin_init', '_maybe_update_themes' );
	remove_action( 'wp_update_themes', 'wp_update_themes' );

	remove_action( 'update_option_WPLANG', 'wp_clean_update_cache', 10, 0 );

	remove_action( 'wp_maybe_auto_update', 'wp_maybe_auto_update' );

	remove_action( 'init', 'wp_schedule_update_checks' );
} );
