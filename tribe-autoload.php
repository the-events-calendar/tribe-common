<?php
$src = dirname( __FILE__ ) . '/src';
require_once $src . '/Tribe/Autoloader.php';

$autoloader = Tribe__Autoloader::instance();
$autoloader->register_prefix('Tribe__',$src);
$autoloader->register_autoloader();
