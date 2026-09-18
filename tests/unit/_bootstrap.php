<?php
// This suite runs without WordPress; the files it loads exit when ABSPATH is undefined.
defined( 'ABSPATH' ) || define( 'ABSPATH', dirname( __DIR__, 2 ) . '/' );

require_once __DIR__ . '/../../tribe-autoload.php';

$functions = __DIR__ . '/../../src/functions';
foreach ( glob( $functions . '/*.php', GLOB_NOSORT ) as $file ) {
	require_once $file;
}
