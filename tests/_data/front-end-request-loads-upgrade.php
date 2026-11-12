<?php
/**
 * Loads WordPress the way a front-end request does and prints whether Shepherd registered (which picks
 * its logger through stellarwp/db) and whether `wp-admin/includes/upgrade.php` was loaded.
 *
 * Runs as its own process because the wpunit bootstrap loads every `wp-admin/includes` file up front.
 * The database settings come from the environment, read by the slic `wp-config.php`.
 *
 * Usage: php front-end-request-loads-upgrade.php <abspath>
 */

$abspath = rtrim( $argv[1], '/' );

// Same as the suite bootstrap: this copy of common wins over the one bundled in The Events Calendar.
require_once dirname( __DIR__, 2 ) . '/tribe-autoload.php';
require $abspath . '/wp-load.php';

echo json_encode(
	[
		'shepherd_registered' => \TEC\Common\StellarWP\Shepherd\Provider::is_registered(),
		'upgrade_loaded'      => in_array( $abspath . '/wp-admin/includes/upgrade.php', get_included_files(), true ),
	]
);
