<?php
// This is global bootstrap for autoloading

use Codeception\Util\Autoload;
use TEC\Common\StellarWP\DB\DB;

$GLOBALS['wp_filter']['lw_harbor/premium_plugin_exists'][ 10 ][ md5( 'lw_harbor/premium_plugin_exists' . '10' . '1' . '__return_true' ) ] = [
	'function'      => '__return_true',
	'accepted_args' => 1,
];

require_once dirname( __DIR__, 1 ) . '/tribe-autoload.php';
Autoload::addNamespace( 'Tribe\\Tests', __DIR__ . '/_support' );
// Silence the logger in the tests.
$_ENV['TEC_DISABLE_LOGGING'] = 1;

function tec_common_tests_fake_transactions_enable() {
	uopz_set_return( DB::class, 'beginTransaction', true, false );
	uopz_set_return( DB::class, 'rollback', true, false );
	uopz_set_return( DB::class, 'commit', true, false );
}

function tec_common_tests_fake_transactions_disable() {
	uopz_unset_return( DB::class, 'beginTransaction' );
	uopz_unset_return( DB::class, 'rollback' );
	uopz_unset_return( DB::class, 'commit' );
}

/**
 * Clears the flag TEC sets on activation to greet a new install with its Guided Setup wizard.
 *
 * WPLoader activates TEC when it installs WordPress, so `Tribe__Events__Main::activate()` leaves
 * `_tribe_events_activation_redirect` set for the whole run. TEC consumes it from
 * `tec_admin_headers_about_to_be_sent`, which Common fires from `current_screen` at `PHP_INT_MAX`,
 * and answers with `wp_safe_redirect()` + `tribe_exit()`. The first test to call
 * `set_current_screen()` on an admin screen as an admin-capable user therefore hits a real `exit()`
 * that takes the runner down mid-suite, abandoning every remaining test.
 *
 * @since TBD
 *
 * @return void
 */
function tec_common_tests_clear_activation_redirects() {
	delete_transient( '_tribe_events_activation_redirect' );
}

if (
	in_array( 'eva_integration', $GLOBALS['argv'] )
	|| in_array( 'restv1', $GLOBALS['argv'] )
	|| in_array( 'restv1_et', $GLOBALS['argv'] )
	|| in_array( 'end2end', $GLOBALS['argv'] )
) {
	require_once __DIR__ . '/_support/_eva_boostrap.php';
}
