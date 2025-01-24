<?php

namespace Tribe;

use Closure;
use Codeception\TestCase\WPTestCase;
use MO;
use PO;
use Tribe__Main;
use Tribe__Events__Main;
use Tribe\Tests\Traits\With_Uopz;

class Language_Test extends WPTestCase {
	use With_Uopz;

	/**
	 * @dataProvider load_text_domain_data_provider
	 * @test
	 */
	public function it_should_load_text_domain_based_on_conditions( Closure $setup, string $expected_result, ?string $expected_message = null ): void {
		// Execute the setup logic and retrieve the result.
		$result = $setup();

		// Assert the result matches the expected result.
		if ( $expected_result === 'success' ) {
			$this->assertTrue( $result, $expected_message ?? 'Expected the text domain to load successfully.' );
		} elseif ( $expected_result === 'failure' ) {
			$this->assertFalse( $result, $expected_message ?? 'Expected the text domain to fail to load.' );
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
				$text_domain = 'the-events-calendar';
				$locale      = get_locale();
				$plugin_dir  = WP_PLUGIN_DIR . '/the-events-calendar/lang/';
				$pot_file    = $plugin_dir . $text_domain . '.pot';
				$mo_file     = $plugin_dir . $text_domain . '-' . $locale . '.mo';

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
			'success',
		];

		yield 'Missing .pot file' => [
			function () {
				$text_domain = 'nonexistent-domain';
				$plugin_dir  = WP_PLUGIN_DIR . '/nonexistent-plugin/lang/';
				$pot_file    = $plugin_dir . $text_domain . '.pot';

				// Assert the .pot file does not exist.
				$this->assertFileNotExists( $pot_file, 'Expected .pot file to not exist.' );

				// Attempt to load the text domain.
				return tribe( Tribe__Main::class )->load_text_domain( $text_domain, $plugin_dir );
			},
			'failure',
		];

		yield 'Valid .mo file in default WP_LANG_DIR' => [
			function () {
				$text_domain = 'the-events-calendar';
				$locale      = get_locale();
				$temp_dir    = sys_get_temp_dir() . '/wp-languages/plugins/';
				$mo_file     = $temp_dir . $text_domain . '-' . $locale . '.mo';

				// Simulate WP_LANG_DIR with a temporary directory.
				$mocked_wp_lang_dir = sys_get_temp_dir() . '/wp-languages';

				// Ensure the temporary directory and MO file are created.
				if ( ! is_dir( $temp_dir ) ) {
					mkdir( $temp_dir, 0777, true );
				}
				file_put_contents( $mo_file, '' );

				// Run the method and return the result.
				$result = tribe( Tribe__Main::class )->load_text_domain( $text_domain, $mocked_wp_lang_dir . '/plugins/' );

				// Cleanup the .mo file and temporary directory.
				unlink( $mo_file );
				if ( is_dir( $temp_dir ) ) {
					rmdir( $temp_dir );
				}

				return $result;
			},
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
