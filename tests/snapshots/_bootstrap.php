<?php

use Codeception\Util\Autoload;

// Register the suite namespace in Codeception auto-loader.
Autoload::addNamespace( 'Tribe\\Tests\\Snapshots', __DIR__ );


if ( ! extension_loaded( 'uopz' ) ) {
	throw new \RuntimeException( 'The snapshots suite cannot run if the uopz extension is not loaded.' );
}

// Let's make sure to set rewrite rules and have pretty permalinks enabled.
global $wp_rewrite;
$wp_rewrite->permalink_structure = '/%postname%/';
$wp_rewrite->rewrite_rules();
