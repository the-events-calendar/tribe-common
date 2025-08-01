<?php
/**
 * Standalone script to ensure shepherd functions.php stub exists.
 *
 * This can be run manually in CI environments if needed:
 * php bin/ensure-shepherd-stub.php
 *
 * @since TBD
 */

$shepherd_functions = 'vendor/stellarwp/shepherd/src/functions.php';

if ( ! file_exists( $shepherd_functions ) && file_exists( 'vendor/stellarwp/shepherd/composer.json' ) ) {
	$dir = dirname( $shepherd_functions );
	if ( ! is_dir( $dir ) ) {
		mkdir( $dir, 0755, true );
		echo "Created directory: {$dir}\n";
	}

	$stub_content = '<?php // This file was deleted by {@see https://github.com/BrianHenryIE/strauss}.';
	if ( file_put_contents( $shepherd_functions, $stub_content ) !== false ) {
		echo "Created stub functions.php file: {$shepherd_functions}\n";
	} else {
		echo "ERROR: Failed to create stub functions.php file: {$shepherd_functions}\n";
		exit( 1 );
	}
} else {
	echo "Stub functions.php file already exists or shepherd not installed: {$shepherd_functions}\n";
}

echo "Shepherd stub check completed.\n";
