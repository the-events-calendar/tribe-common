<?php

namespace Tribe;

use Closure;
use Codeception\TestCase\WPTestCase;
use MO;
use PO;
use Tribe\Tests\Traits\With_Uopz;
use Tribe__Main;
use Tribe__Events__Main;

class Language_Test extends WPTestCase {
	use With_Uopz;

	/**
	 * Paths to directories where .mo files are stored.
	 *
	 * @var string[]
	 */
	protected $mo_directories = [];

	/**
	 * Path to the .mo file created during the test.
	 *
	 * @var string|null
	 */
	protected $mo_file_path;

	protected static $load_plugin_textdomain_call_count = 0;

	/**
	 * @before
	 * @return void
	 */
	protected function setup(): void {
		global $wp_textdomain_registry, $l10n;

		// Reset the text domain registry to prevent state leakage.
		if ( isset( $wp_textdomain_registry ) ) {
			unset( $wp_textdomain_registry );
			$wp_textdomain_registry = new \WP_Textdomain_Registry();
		}

		// Clear the l10n global.
		$l10n = [];

		// Reset the call counter for load_plugin_textdomain.
		self::$load_plugin_textdomain_call_count = 0;

		$load_plugin_textdomain_call_count = &self::$load_plugin_textdomain_call_count;

		// Mock the load_plugin_textdomain function to count its calls.
		$this->set_fn_return(
			'load_plugin_textdomain',
			function ( $domain, $deprecated = false, $plugin_rel_path = false ) use ( &$load_plugin_textdomain_call_count ) {
				$load_plugin_textdomain_call_count++;

				return load_plugin_textdomain( $domain, $deprecated, $plugin_rel_path );
			},
			true
		);
	}

	/**
	 * Cleanup after each test.
	 * @after
	 */
	protected function after(): void {
		$this->cleanup_mo_files();
	}

	/**
	 * Deletes all .mo files in the target directories.
	 */
	protected function cleanup_mo_files(): void {
		foreach ( $this->mo_directories as $dir ) {
			if ( is_dir( $dir ) ) {
				$files = glob( $dir . '*.mo' );
				foreach ( $files as $file ) {
					unlink( $file );
				}

				// Optionally clean up empty directories.
				if ( count( scandir( $dir ) ) === 2 ) { // Only '.' and '..'
					rmdir( $dir );
				}
			}
		}
	}

	/**
	 * @dataProvider load_text_domain_data_provider
	 * @test
	 */
	public function it_should_load_text_domain_based_on_conditions( Closure $setup, int $expected_load_plugin_textdomain_call_count, string $expected_result ): void {
		// Mock `is_textdomain_loaded` to return false by default.
		$this->set_fn_return( 'is_textdomain_loaded', false );

		// Ensure a clean state for the `l10n` global.
		global $GLOBALS;
		$GLOBALS['l10n'] = [
			'mock-text-domain' => null,
		];

		// Execute the setup logic and retrieve the result.
		$result = $setup();

		$this->assertEquals( $expected_load_plugin_textdomain_call_count, self::$load_plugin_textdomain_call_count );

		// Assert the result matches the expected outcome.
		if ( $expected_result === 'success' ) {
			$this->assertTrue( $result, 'Expected the text domain to load successfully.' );
		} elseif ( $expected_result === 'failure' ) {
			$this->assertFalse( $result, 'Expected the text domain to fail to load.' );
		}
	}

	/**
	 * Data provider for `it_should_load_text_domain_based_on_conditions`.
	 *
	 * @return \Generator
	 */
	public function load_text_domain_data_provider(): \Generator {
		yield 'Valid .mo file in custom directory' => [
			function () {
				$text_domain        = 'the-events-calendar';
				$locale             = get_locale();
				$plugin_dir         = WP_PLUGIN_DIR . '/the-events-calendar/lang/';
				$pot_file           = $plugin_dir . $text_domain . '.pot';
				$mo_file            = $plugin_dir . $text_domain . '-' . $locale . '.mo';
				$this->mo_file_path = $mo_file;

				$mopath = Tribe__Events__Main::instance()->plugin_dir . 'lang/';

				// Ensure the .pot file exists.
				$this->assertFileExists( $pot_file, 'Expected .pot file to exist for the text domain.' );

				// Compile the .pot file into a .mo file.
				$this->create_mo_file_from_existing_pot( $pot_file, $locale, $mo_file );

				// Assert the .mo file exists.
				$this->assertFileExists( $mo_file, 'Expected .mo file to be created.' );

				// Run the method and return the result.
				return tribe( Tribe__Main::class )->load_text_domain( $text_domain, $mopath );
			},
			1,
			'success',
		];

		yield 'Valid .mo file in overridden WP_LANG_DIR' => [
			function () {
				$text_domain = 'the-events-calendar';
				$locale      = get_locale();
				$temp_dir    = sys_get_temp_dir() . '/wp-languages/plugins/';
				$plugin_dir  = WP_PLUGIN_DIR . '/the-events-calendar/lang/';
				$pot_file    = $plugin_dir . $text_domain . '.pot';
				$mo_file     = $temp_dir . $text_domain . '-' . $locale . '.mo';

				// Override WP_LANG_DIR for this test.
				if ( defined( 'WP_LANG_DIR' ) ) {
					$this->set_fn_return( 'WP_LANG_DIR', $temp_dir );
				}

				// Ensure the temporary directory exists.
				if ( ! is_dir( $temp_dir ) ) {
					mkdir( $temp_dir, 0777, true );
				}

				// Pre-cleanup: Remove any lingering .mo file.
				if ( file_exists( $mo_file ) ) {
					unlink( $mo_file );
				}

				// Create an actual .mo file in the overridden WP_LANG_DIR.
				$this->create_mo_file_from_existing_pot( $pot_file, $locale, $mo_file );

				// Run the method and return the result.
				return tribe( Tribe__Main::class )->load_text_domain( $text_domain, 'fakepath' );
			},
			1,
			'success',
		];
	}

	/**
	 * Compiles an existing .pot file into a .mo file.
	 *
	 * @param string $pot_file The path to the .pot file.
	 * @param string $locale   The locale for the translations.
	 * @param string $mo_file  The path where the .mo file will be saved.
	 *
	 * @return void
	 */
	private function create_mo_file_from_existing_pot( string $pot_file, string $locale, string $mo_file ): void {
		if ( ! class_exists( PO::class ) ) {
			require_once ABSPATH . 'wp-includes/pomo/po.php';
		}
		if ( ! class_exists( MO::class ) ) {
			require_once ABSPATH . 'wp-includes/pomo/mo.php';
		}

		$po = new PO();
		$po->import_from_file( $pot_file );

		$mo          = new MO();
		$mo->entries = $po->entries;
		$mo->export_to_file( $mo_file );
	}
}
