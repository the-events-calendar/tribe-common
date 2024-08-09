<?php
// This is global bootstrap for autoloading

use Codeception\Util\Autoload;

require_once dirname( __DIR__, 1 ) . '/tribe-autoload.php';
Autoload::addNamespace( 'Tribe\\Tests', __DIR__ . '/_support' );
// Silence the logger in the tests.
$_ENV['TEC_DISABLE_LOGGING'] = 1;

require_once __DIR__ . '/_support/_eva_boostrap.php';
