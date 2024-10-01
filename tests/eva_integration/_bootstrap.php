<?php
// Here you can initialize variables that will be available to your tests
use Codeception\Util\Autoload;
use EDD\Database\Tables\Customers;
use Tribe\Tickets\Promoter\Triggers\Dispatcher;
use function tad\WPBrowser\addListener;
use Codeception\Events;
use TEC\Common\StellarWP\DB\DB;


tests_add_filter( 'tribe_common_log_to_wpcli', '__return_false' );

Autoload::addNamespace( '\\TEC\\Common\\Integrations\\', __DIR__ . '/_data/classes/Integrations' );

require_once __DIR__ . '/Snapshot_Test_Case.php';

// Start the posts auto-increment from a high number to make it easier to replace the post IDs in HTML snapshots.
global $wpdb;
DB::query( "ALTER TABLE $wpdb->posts AUTO_INCREMENT = 5096" );


// Set the default WordPress theme.
update_option( 'theme', 'twentytwenty' );
update_option( 'stylesheet', 'twentytwenty' );

// Suppress errors while we, somewhat forcefully, upgrade the database.
global $wpdb;
$wpdb->suppress_errors = true;
foreach ( EDD()->components as $component ) {
	// Install the main component table.
	/** @var Customers $table */
	$table = $component->get_interface( 'table' );
	$table->maybe_upgrade();

	/**
	 * @var EDD\Database\Table $meta_table
	 */
	$meta_table = $component->get_interface( 'meta' );

	if ( $meta_table instanceof EDD\Database\Table ) {
		$meta_table->maybe_upgrade();
	}
}
$wpdb->suppress_errors = false;

// Avoid Promoter license issues.
remove_action( 'tribe_tickets_promoter_trigger', [ tribe( Dispatcher::class ), 'trigger' ] );

// increment EDD order sequence.
edd_update_option( 'sequential_start', 8011 );

addListener( Events::TEST_BEFORE, function () {
	// Ensure that EDD is not triggering emails.
	if ( has_filter( 'pre_wp_mail', '__return_true' ) ) {
		return;
	}
	add_filter( 'pre_wp_mail', '__return_true' );

	// Remove deprecated action for EDD 3.2.0
	remove_action( 'edd_complete_purchase', 'edd_trigger_purchase_receipt', 999, 3 );
} );
