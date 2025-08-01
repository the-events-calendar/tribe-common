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

// Create stub functions.php file if missing (Strauss deletes it but composer autoload still references it)
$original_functions_file = 'vendor/stellarwp/shepherd/src/functions.php';
if ( ! file_exists( $original_functions_file ) ) {
	// Ensure the directory exists
	$dir = dirname( $original_functions_file );
	if ( ! is_dir( $dir ) ) {
		mkdir( $dir, 0755, true );
	}
	$stub_content = '<?php // This file was deleted by {@see https://github.com/BrianHenryIE/strauss}.';
	if ( file_put_contents( $original_functions_file, $stub_content ) !== false ) {
		echo "Created stub functions.php file: {$original_functions_file}\n";
	} else {
		echo "ERROR: Failed to create stub functions.php file: {$original_functions_file}\n";
	}
} else {
	echo "Stub functions.php file already exists: {$original_functions_file}\n";
}

echo "Shepherd namespace fix completed.\n";
