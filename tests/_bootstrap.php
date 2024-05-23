<?php
// This is global bootstrap for autoloading

use Codeception\Util\Autoload;

require_once dirname( __FILE__, 2 ) . '/tribe-autoload.php';
Autoload::addNamespace( 'Tribe\\Tests', __DIR__ . '/_support' );
// Silence the logger in the tests.
$_ENV['TEC_DISABLE_LOGGING'] = 1;


/**
 * Bootstrap for Event Automator Tests.
 *
 */

// TEC Test Support.
$tec_dir         = dirname( __DIR__, 2 );
$tec_tests_dir   = $tec_dir . '/tests';
$tec_support_dir = $tec_tests_dir . '/_support';
if ( ! is_dir( $tec_tests_dir ) ) {
	throw new RuntimeException( "Event Automator tests require The Events Calendar installed in a \"the-events-calendar\" sibling folder: {$tec_tests_dir} not found." );
}

// ET Test Support.
$et_dir         = dirname( __DIR__, 3 ) . '/event-tickets';
$et_tests_dir   = $et_dir . '/tests';
$et_support_dir = $et_tests_dir . '/_support';
if ( ! is_dir( $et_tests_dir ) ) {
	throw new RuntimeException( "Event Automator tests require Event Tickets installed in a \"event-tickets\" sibling folder: {$et_tests_dir} not found." );
}

// ET+ Test Support.
$etplus_dir         = dirname( __DIR__, 3 ) . '/event-tickets-plus';
$etplus_tests_dir   = $etplus_dir . '/tests';
$etplus_support_dir = $etplus_tests_dir . '/_support';
if ( ! is_dir( $etplus_tests_dir ) ) {
	throw new RuntimeException( "Event Automator tests require Event Tickets Plus installed in a \"event-tickets-plus\" sibling folder: {$etplus_tests_dir} not found." );
}

/**
 * Manually include the file here as we might need it in a suite configuration file.
 * Suites fire before the autoload below is used so we need to gather what we need without
 * autoloading.
 */
require_once $tec_support_dir . '/Helper/TribeDb.php';

Autoload::addNamespace( '\\Tribe\\Events\\Test', $tec_support_dir );
Autoload::addNamespace( '\\Helper', $tec_support_dir );
Autoload::addNamespace( '\\TEC\\Event_Automator\\Tests', __DIR__ . '/_support' );
Autoload::addNamespace( '\\Tribe\\Extensions', __DIR__ . '/_data/classes/Tribe/Extensions' );
Autoload::addNamespace( '\Tribe\Tickets\Test', $et_support_dir );
Autoload::addNamespace( '\Tribe\Tickets_Plus\Test', $etplus_support_dir );


/**
 * Codeception will regenerate snapshots on `--debug`, while the `spatie/snapshot-assertions`
 * library will do the same on `--update-snapshots`.
 * Since Codeception has strict check on the CLI arguments appending `--update-snapshots` to the
 * `vendor/bin/codecept run` command will throw an error.
 * We handle that intention here.
 */
if ( in_array( '--debug', $_SERVER['argv'], true ) ) {
	$_SERVER['argv'][] = '--update-snapshots';
}

// If the `uopz` extension is installed, then ensure `exit` and `die` to work normally.
if ( function_exists( 'uopz_allow_exit' ) ) {
	uopz_allow_exit( true );
}

// Let's make sure Tickets Commerce is activated.
putenv( 'TEC_TICKETS_COMMERCE=1' );
$_ENV['TEC_TICKETS_COMMERCE'] = 1;

// Disabled SSL check.
putenv( 'TEC_EVENT_AUTOMATOR_INTEGRATION_SSL_DISABLED=1' );
$_ENV['TEC_EVENT_AUTOMATOR_INTEGRATION_SSL_DISABLED'] = 1;


// By default, do not enable the Custom Tables v1 implementation in tests.
putenv( 'TEC_CUSTOM_TABLES_V1_DISABLED=1' );
$_ENV['TEC_CUSTOM_TABLES_V1_DISABLED'] = 1;
