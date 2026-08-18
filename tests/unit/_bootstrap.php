<?php
require_once __DIR__ . '/../../tribe-autoload.php';

$functions = __DIR__ . '/../../src/functions';
foreach ( glob( $functions . '/*.php', GLOB_NOSORT ) as $file ) {
	require_once $file;
}
