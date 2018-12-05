<?php
// This is global bootstrap for autoloading

use Codeception\Util\Autoload;

require_once dirname( dirname( __FILE__ ) ) . '/tribe-autoload.php';
Autoload::addNamespace( 'Tribe\\Tests', __DIR__ . '/_support' );