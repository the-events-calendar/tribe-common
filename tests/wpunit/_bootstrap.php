<?php
// Here you can initialize variables that will be available to your tests
use Codeception\Util\Autoload;

tests_add_filter( 'tribe_common_log_to_wpcli', '__return_false' );


Autoload::addNamespace( '\\TEC\\Common\\Integrations\\', __DIR__ . '/_data/classes/Integrations' );