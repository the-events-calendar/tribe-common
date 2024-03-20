<?php
namespace Tribe;

use Tribe__Assets as Assets;
use Tribe__Main as Plugin;

class AssetsTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * @test
	 *
	 * @since 4.12.13
	 */
	public function it_should_have_translations() {
		$locale = 'en';
		add_filter( 'pre_determine_locale', static function() use ( $locale ) {
			return $locale;
		} );

		$expected_msgid = 'Translations MSGID';
		$expected_msgstr = 'Translations MSGID';
		$domain = 'tribe-common';

		$plugin = Plugin::instance();
		$assets = new Assets;

		$asset_slug = 'test-script';

		$assets->register(
			$plugin,
			$asset_slug,
			codecept_data_dir( 'resources/test-script-1.js' ),
			[],
			null,
			[
				'translations' => [
					'domain' => $domain,
					'path'   => codecept_data_dir( 'lang/' ),
				]
			]
		);

		$assets->register_in_wp( [ $assets->get( $asset_slug ) ] );
		$assets->enqueue( $asset_slug );

		$translations_string = wp_scripts()->print_translations( $asset_slug, false );

		$this->assertContains( $expected_msgid, $translations_string );
		$this->assertContains( $expected_msgstr, $translations_string );
	}

	public function get_script_tags() {
		yield 'simple-script' => [
			'<script src="https://localhost/tec-ky.js?ver=5.1.13.1" id="tec-ky-js"></script>'
		];
		yield 'simple-with-existing-type' => [
			'<script type="text/javascript" src="https://localhost/tec-ky.js?ver=5.1.13.1" id="tec-ky-js"></script>'
		];
		yield 'simple-without-src-or-id' => [
			'<script></script>'
		];
		yield 'simple-with-existing-type-simple-quotes' => [
			'<script type=\'text/javascript\' src="https://localhost/tec-ky.js?ver=5.1.13.1" id="tec-ky-js"></script>'
		];
		yield 'simple-with-existing-type-no-quotes' => [
			'<script type=text/javascript src="https://localhost/tec-ky.js?ver=5.1.13.1" id="tec-ky-js"></script>'
		];
	}

	/**
	 * @test
	 * @dataProvider get_script_tags
	 */
	public function it_should_properly_had_module_type( $script_tag ) {
		$plugin = Plugin::instance();
		$assets = new Assets;

		// Register generic script to ensure the module type is added.
		$assets->register(
			$plugin,
			'test-script',
			codecept_data_dir( 'resources/test-script-1.js' ),
			[],
			null,
			[
				'module' => true,
			]
		);

		$script_tag = $assets->filter_modify_to_module( $script_tag, 'test-script' );

		$this->assertContains( 'type="module"', $script_tag );
	}
}
