<?php
// Here you can initialize variables that will be available to your tests
use Codeception\Util\Autoload;
use Tribe\Tickets\Promoter\Triggers\Dispatcher;
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


// Avoid Promoter license issues.
remove_action( 'tribe_tickets_promoter_trigger', [ tribe( Dispatcher::class ), 'trigger' ] );
