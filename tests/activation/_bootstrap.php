<?php

use Codeception\Event\TestEvent;

use function tad\WPBrowser\addListener;
use function tad\WPBrowser\importDumpWithMysqlBin;

// If the `uopz` extension is installed, let's make sure to `exit` and `die` will work properly.
if ( function_exists( 'uopz_allow_exit' ) ) {
	uopz_allow_exit( true );
}

// Since we do not drop and import the DB dump after each test, let's do a lighter cleanup here.
$clean_after_test = static function ( TestEvent $event ) {
	// Empty options, posts and postmeta tables.
	$credentials = [ $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], $_ENV['DB_HOST'] ];
	importDumpWithMysqlBin( __DIR__ . '/../_data/empty_cleanup.sql', ...$credentials );
};
addListener( Codeception\Events::TEST_AFTER, $clean_after_test );
