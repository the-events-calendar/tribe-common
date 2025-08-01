<?php
// This is global bootstrap for autoloading

use Codeception\Util\Autoload;

// Ensure shepherd functions.php stub exists before any autoloading
$shepherd_functions = dirname( __DIR__ ) . '/vendor/stellarwp/shepherd/src/functions.php';
if ( ! file_exists( $shepherd_functions ) && file_exists( dirname( __DIR__ ) . '/vendor/stellarwp/shepherd/composer.json' ) ) {
	$shepherd_dir = dirname( $shepherd_functions );
	if ( ! is_dir( $shepherd_dir ) ) {
		mkdir( $shepherd_dir, 0755, true );
	}
	file_put_contents( $shepherd_functions, '<?php // This file was deleted by {@see https://github.com/BrianHenryIE/strauss}.' );
}

require_once dirname( __DIR__, 1 ) . '/tribe-autoload.php';
Autoload::addNamespace( 'Tribe\\Tests', __DIR__ . '/_support' );
// Silence the logger in the tests.
$_ENV['TEC_DISABLE_LOGGING'] = 1;

if (
	in_array( 'eva_integration', $GLOBALS['argv'] )
	|| in_array( 'restv1', $GLOBALS['argv'] )
	|| in_array( 'restv1_et', $GLOBALS['argv'] )
	|| in_array( 'end2end', $GLOBALS['argv'] )
) {
	require_once __DIR__ . '/_support/_eva_boostrap.php';
}
