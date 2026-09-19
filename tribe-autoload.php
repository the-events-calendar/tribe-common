<?php

if ( ! defined( 'ABSPATH' ) ) {
	// The test bootstraps load this file before WordPress; only block direct web requests.
	'cli' === PHP_SAPI || exit;
}

$common = __DIR__ . '/src';

require_once $common . '/Tribe/Autoloader.php';

$autoloader = Tribe__Autoloader::instance();
$autoloader->register_prefix( 'Tribe__', $common . '/Tribe' );
$autoloader->register_prefix( 'TEC\\Common\\', $common . '/Common' );
$autoloader->register_autoloader();
