<?php

namespace TEC\Common;

use Codeception\TestCase\WPTestCase;
use Tribe\Tests\Traits\With_Uopz;

class Translations_LoaderTest extends WPTestCase {
	use With_Uopz;

	/**
	 * Text domain being tested.
	 *
	 * @var string
	 */
	protected $text_domain = 'test-text-domain';

	/**
	 * Temporary directory for .mo files.
	 *
	 * @var string
	 */
	protected $temp_dir;

	/**
	 * Set up the temporary directory for .mo files.
	 *
	 * @before
	 */
	public function setup(): void {
		$this->temp_dir = sys_get_temp_dir() . '/wp-languages/plugins/';
		if ( ! is_dir( $this->temp_dir ) ) {
			mkdir( $this->temp_dir, 0777, true );
		}
	}

	/**
	 * Retrieves the relative directory for text domain translations.
	 *
	 * @return string The relative path to the translations directory.
	 */
	private function get_text_domain_translations_dir(): string {
		return str_replace( WP_PLUGIN_DIR, '', $this->temp_dir );
	}

	/**
	 * It should allow switching locale between en_US and other languages
	 *
	 * @test
	 */
	public function should_allow_switching_locale_between_en_us_and_other_languages() {
		$locale       = 'it_IT';
		$temp_mo_file = $this->temp_dir . $this->text_domain . '-' . $locale . '.mo';

		// Create the .mo file with translations.
		$this->create_mo_file( $locale, $temp_mo_file );

		$translations_loader = new Translations_Loader();

		// Ensure no translations are loaded initially.
		$this->assertFalse(
			$translations_loader->has_loaded_translations(),
			'Translations should not be loaded initially.'
		);

		// Verify the default untranslated string.
		$this->assertEquals(
			'test',
			__( 'test', $this->text_domain ),
			'Default untranslated string should match the original.'
		);

		// Load the translations.
		$translations_loader->load( $locale, [ $this->text_domain => $this->get_text_domain_translations_dir() ] );
		// Force unload the text domain before loading it again.
		unload_textdomain( $this->text_domain );
		load_textdomain( $this->text_domain, $temp_mo_file );

		// Verify translations have been loaded.
		$this->assertTrue(
			$translations_loader->has_loaded_translations(),
			'Translations should be loaded after calling the loader.'
		);

		// Verify the translated string.
		$this->assertEquals(
			'prova',
			__( 'test', $this->text_domain ),
			'Translated string should match the Italian translation.'
		);

		// Restore the default locale and verify the state.
		$translations_loader->restore();

		$this->assertFalse(
			$translations_loader->has_loaded_translations(),
			'Translations should not be loaded after restore.'
		);

		$this->assertEquals(
			'test',
			__( 'test', $this->text_domain ),
			'Default untranslated string should be restored.'
		);
	}

	/**
	 * Creates a .mo file with translations for testing.
	 *
	 * @param string $locale  The locale for the translations.
	 * @param string $mo_file The path where the .mo file will be saved.
	 */
	private function create_mo_file( string $locale, string $mo_file ): void {
		if ( ! class_exists( \PO::class ) ) {
			require_once ABSPATH . 'wp-includes/pomo/po.php';
		}
		if ( ! class_exists( \MO::class ) ) {
			require_once ABSPATH . 'wp-includes/pomo/mo.php';
		}

		// Create a new PO object and add entries.
		$po = new \PO();
		$po->add_entry(
			new \Translation_Entry(
				[
					'singular'     => 'test',
					'translations' => [ 'prova' ],
				]
			)
		);

		// Convert the PO object to a MO object and export it.
		$mo          = new \MO();
		$mo->entries = $po->entries;
		$mo->export_to_file( $mo_file );

		$this->assertFileExists(
			$mo_file,
			'The .mo file was not created at the expected location.'
		);
	}
}
