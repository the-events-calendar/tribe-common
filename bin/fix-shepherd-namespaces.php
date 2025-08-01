<?php
/**
 * Fix Shepherd namespace imports after Strauss processing.
 *
 * This script fixes the stellarwp/shepherd library namespace imports
 * that aren't properly prefixed by Strauss.
 *
 * @since TBD
 */

$shepherd_files = [
	'vendor/vendor-prefixed/stellarwp/shepherd/src/Config.php',
	'vendor/vendor-prefixed/stellarwp/shepherd/src/Regulator.php',
	'vendor/vendor-prefixed/stellarwp/shepherd/src/Abstracts/Provider_Abstract.php',
	'vendor/vendor-prefixed/stellarwp/shepherd/src/Provider.php',
];

$replacements = [
	'use StellarWP\ContainerContract\ContainerInterface;'              => 'use TEC\Common\StellarWP\ContainerContract\ContainerInterface;',
	'use StellarWP\ContainerContract\ContainerInterface as Container;' => 'use TEC\Common\StellarWP\ContainerContract\ContainerInterface as Container;',
	'use StellarWP\Schema\Config as Schema_Config;'                    => 'use TEC\Common\StellarWP\Schema\Config as Schema_Config;',
	'use StellarWP\DB\DB;'                                             => 'use TEC\Common\StellarWP\DB\DB;',
];

foreach ( $shepherd_files as $file ) {
	if ( ! file_exists( $file ) ) {
		continue;
	}

	$content          = file_get_contents( $file );
	$original_content = $content;

	foreach ( $replacements as $search => $replace ) {
		$content = str_replace( $search, $replace, $content );
	}

	if ( $content !== $original_content ) {
		file_put_contents( $file, $content );
		echo "Fixed namespace imports in: {$file}\n";
	}
}

echo "Shepherd namespace fix completed.\n";
