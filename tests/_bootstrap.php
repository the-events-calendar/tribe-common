<?php
// This is global bootstrap for autoloading

use Codeception\Util\Autoload;

require_once dirname( __FILE__, 2 ) . '/tribe-autoload.php';
Autoload::addNamespace( 'Tribe\\Tests', __DIR__ . '/_support' );
