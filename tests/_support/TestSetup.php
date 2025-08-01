<?php
/**
 * Test setup utilities.
 *
 * @since TBD
 */

class TestSetup {

	/**
	 * Initialize test environment.
	 *
	 * Called before tests run to ensure proper setup.
	 *
	 * @since TBD
	 */
	public static function init() {
		self::ensure_shepherd_stub();
	}

	/**
	 * Ensure shepherd functions.php stub exists.
	 *
	 * @since TBD
	 */
	private static function ensure_shepherd_stub() {
		$shepherd_functions = codecept_root_dir( 'vendor/stellarwp/shepherd/src/functions.php' );

		if ( ! file_exists( $shepherd_functions ) && file_exists( codecept_root_dir( 'vendor/stellarwp/shepherd/composer.json' ) ) ) {
			$shepherd_dir = dirname( $shepherd_functions );
			if ( ! is_dir( $shepherd_dir ) ) {
				mkdir( $shepherd_dir, 0755, true );
			}

			$stub_content = '<?php // This file was deleted by {@see https://github.com/BrianHenryIE/strauss}.';
			file_put_contents( $shepherd_functions, $stub_content );
		}
	}
}
