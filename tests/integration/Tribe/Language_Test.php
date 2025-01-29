<?php

namespace Tribe;

use Codeception\TestCase\WPTestCase;
use Translation_Entry;
use Tribe\Tests\Traits\With_Uopz;
use Tribe__Events__Main;
use Tribe__Main;
use MO;
use PO;
use WP_Textdomain_Registry;

class Language_Test extends WPTestCase {
	use With_Uopz;

	/**
	 * Temporary directory for testing .mo file paths.
	 *
	 * @var string
	 */
	protected $temp_dir;

	/**
	 * Text domain being tested.
	 *
	 * @var string
	 */
	protected $text_domain = 'the-events-calendar';

	/**
	 * Locale being tested.
	 *
	 * @var string
	 */
	protected $locale = 'el_GR';

	protected static $load_plugin_textdomain_call_count = 0;

	protected $original_wp_locale_switcher;

	protected $original_wp_textdomain_registry;

	/**
	 * Set up the test environment before each test.
	 *
	 * @before
	 */
	protected function setup(): void {
		global $wp_locale_switcher;

		// Initialize WP_Locale_Switcher if not already set.
		if ( ! isset( $wp_locale_switcher ) ) {
			$wp_locale_switcher = new \WP_Locale_Switcher();
		}

		$this->original_wp_locale_switcher = $wp_locale_switcher;

		// Reset for tests.
		self::$load_plugin_textdomain_call_count = 0;

		// Switch to the test locale and override global `$locale`.
		switch_to_locale( $this->locale );
		$this->set_fn_return( 'get_locale', $this->locale );
		$this->set_fn_return( 'determine_locale', $this->locale );

		// Set up the temporary directory for .mo files and override WP_LANG_DIR.
		$this->temp_dir = codecept_data_dir( 'lang/' );
		$this->set_const_value( 'WP_LANG_DIR', $this->temp_dir );

		// Assert WP_LANG_DIR has been correctly set.
		$this->assertEquals( $this->temp_dir, WP_LANG_DIR, 'WP_LANG_DIR was not correctly set to the temporary directory.' );
	}

	/**
	 * Clean up the test environment after each test.
	 *
	 * @after
	 */
	protected function cleanup(): void {
		global $wp_locale_switcher, $wp_textdomain_registry;
		// Unset UOPZ returns to ensure no leakage.
		$this->unset_uopz_returns();

		// Switch back to the default locale.
		switch_to_locale( 'en_US' );
		$wp_locale_switcher     = $this->original_wp_locale_switcher;
		$wp_textdomain_registry = $this->original_wp_textdomain_registry;
	}

	/**
	 * Test: Ensure the text domain looks in the overridden WP_LANG_DIR.
	 *
	 * @test
	 */
	public function it_should_look_in_the_wp_lang_directory(): void {
		global $wp_textdomain_registry;
		$this->original_wp_textdomain_registry = $wp_textdomain_registry;

		// Set up the registry to use the temporary directory.
		$this->set_class_fn_return( WP_Textdomain_Registry::class, 'get', $this->temp_dir );

		$this->set_fn_return( 'is_textdomain_loaded', false, false );

		$temp_mo_file = $this->temp_dir . $this->text_domain . '-' . $this->locale . '.mo';

		// Create a dummy .mo file in the temporary directory.
		$this->create_mo_file_from_existing_pot( '', $this->locale, $temp_mo_file );

		// Add a filter to override the text domain path.
		add_filter( 'tribe_load_text_domain', [ $this, 'override_tribe_text_domain' ], 10, 4 );

		// Attempt to load the text domain.
		$result = tribe( Tribe__Main::class )->load_text_domain( $this->text_domain, 'fakepath' );

		// Retrieve the file location from the registry.
		$get_file_location = $wp_textdomain_registry->get( $this->text_domain, $this->locale );

		$this->assertEquals( $this->temp_dir, $get_file_location );

		$this->assertTrue( $result, 'Expected the text domain to load successfully.' );
	}

	/**
	 * Test: Ensure the text domain looks in the custom language directory.
	 *
	 * @test
	 */
	public function it_should_look_in_the_custom_lang_directory(): void {
		global $wp_textdomain_registry;
		$this->original_wp_textdomain_registry = $wp_textdomain_registry;

		$plugin_dir       = WP_PLUGIN_DIR . "/{$this->text_domain}/lang/";
		$mo_file_location = $plugin_dir . $this->text_domain . '-' . $this->locale . '.mo';
		$mopath           = Tribe__Events__Main::instance()->plugin_dir . 'lang/';

		// Create a dummy .mo file in the plugin's language directory.
		$this->create_mo_file_from_existing_pot( '', $this->locale, $mo_file_location );

		// Attempt to load the text domain.
		$result = tribe( Tribe__Main::class )->load_text_domain( $this->text_domain, $mopath );

		// Retrieve the file location from the registry.
		$get_file_location = $wp_textdomain_registry->get( $this->text_domain, $this->locale );

		$this->assertEquals( $plugin_dir, $get_file_location );

		$this->assertTrue( $result, 'Expected the text domain to load successfully.' );
	}

	/**
	 * Compiles an existing .pot file into a .mo file.
	 *
	 * @param string $pot_file The path to the .pot file (can be empty for the test).
	 * @param string $locale   The locale for the translations.
	 * @param string $mo_file  The path where the .mo file will be saved.
	 */
	private function create_mo_file_from_existing_pot( string $pot_file, string $locale, string $mo_file ): void {
		// Ensure the directory for the .mo file exists.
		if ( ! file_exists( dirname( $mo_file ) ) ) {
			mkdir( dirname( $mo_file ), 0777, true );
		}

		if ( ! class_exists( \PO::class ) ) {
			require_once ABSPATH . 'wp-includes/pomo/po.php';
		}
		if ( ! class_exists( \MO::class ) ) {
			require_once ABSPATH . 'wp-includes/pomo/mo.php';
		}

		$po = new \PO();

		// Skip importing from .pot file if it is empty or doesn't exist.
		if ( $pot_file && file_exists( $pot_file ) ) {
			$po->import_from_file( $pot_file );
		} else {
			// Add a default translation entry for testing.
			$po->add_entry(
				new Translation_Entry(
					[
						'singular'     => 'test',
						'translations' => [ 'essai' ],
					]
				)
			);
		}

		// Export to .mo file.
		$mo          = new \MO();
		$mo->entries = $po->entries;
		$mo->export_to_file( $mo_file );

		// Assert that the file was created.
		$this->assertFileExists( $mo_file, "The .mo file was not created at the expected location: {$mo_file}" );
	}

	/**
	 * Overrides the text domain path via the `tribe_load_text_domain` filter.
	 *
	 * @param string      $plugin_rel_path The relative path for the language files.
	 * @param string      $domain          The text domain being loaded.
	 * @param string      $locale          The locale being loaded.
	 * @param string|bool $dir             The directory passed to the loader.
	 *
	 * @return string The overridden path to the language directory.
	 */
	public function override_tribe_text_domain( $plugin_rel_path, $domain, $locale, $dir ) {
		return str_replace( WP_PLUGIN_DIR, '', $this->temp_dir );
	}
}
