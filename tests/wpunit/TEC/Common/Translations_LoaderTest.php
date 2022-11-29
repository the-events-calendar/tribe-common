<?php

namespace TEC\Common;

class Translations_LoaderTest extends \Codeception\TestCase\WPTestCase {
	private function get_text_domain_translations_dir(): string {
		return str_replace( WP_PLUGIN_DIR, '', __DIR__ . '/__text-domain-plugin' );
	}

	/**
	 * It should allow switching locale between en_US and other languages
	 *
	 * @test
	 */
	public function should_allow_switching_locale_between_en_us_and_other_languages() {
		$translations_loader = new Translations_Loader();

		// Use a translation we know will be there in the default text-domain.
		$this->assertEquals( 'test', __( 'test', 'test-text-domain' ) );

		$translations_loader->load( 'it_IT', [ 'test-text-domain' => $this->get_text_domain_translations_dir() ] );

		$this->assertEquals( 'prova', __( 'test', 'test-text-domain' ) );
	}
}
