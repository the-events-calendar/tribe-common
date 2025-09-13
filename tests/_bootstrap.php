<?php
// This is global bootstrap for autoloading

use Codeception\Util\Autoload;
use TEC\Common\Tests\Extensions\Suite_Env;

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

/*
 * Feature activation/deactivation per-suite.
 * Use hard-coded environment variables as the feature controller will not be loaded yet.
 */
Suite_Env::toggle_features( [
	'Classy Editor' => [
		'disable_env_var'    => 'TEC_CLASSY_EDITOR_DISABLED',
		'enabled_by_default' => false,
		'active_for_suites'  => [
			'classy_integration'
		]
	]
] );
