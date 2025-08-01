<?php
/**
 * Test helper for handling Shepherd-related issues in CI environments.
 *
 * @since TBD
 */

namespace Helper;

/**
 * Class Shepherd
 *
 * Helper class to handle Shepherd/Strauss compatibility issues during testing.
 */
class Shepherd {

	/**
	 * Ensure the shepherd functions.php stub exists for testing.
	 *
	 * This should be called in test setup/bootstrap to prevent autoload failures.
	 *
	 * @since TBD
	 */
	public static function ensure_functions_stub() {
		$shepherd_functions = codecept_root_dir( 'vendor/stellarwp/shepherd/src/functions.php' );

		if ( ! file_exists( $shepherd_functions ) && file_exists( codecept_root_dir( 'vendor/stellarwp/shepherd/composer.json' ) ) ) {
			$shepherd_dir = dirname( $shepherd_functions );
			if ( ! is_dir( $shepherd_dir ) ) {
				mkdir( $shepherd_dir, 0755, true );
			}

			$stub_content = '<?php // This file was deleted by {@see https://github.com/BrianHenryIE/strauss}.';
			file_put_contents( $shepherd_functions, $stub_content );

			codecept_debug( "Created shepherd functions.php stub for testing: {$shepherd_functions}" );
		}
	}
}
