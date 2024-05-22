<?php
// This is global bootstrap for autoloading

use Codeception\Util\Autoload;

require_once dirname( __FILE__, 2 ) . '/tribe-autoload.php';
Autoload::addNamespace( 'Tribe\\Tests', __DIR__ . '/_support' );
Autoload::addNamespace( 'TEC\\Event_Automator\\Tests', __DIR__ . '/_support' );
// Silence the logger in the tests.
$_ENV['TEC_DISABLE_LOGGING'] = 1;

