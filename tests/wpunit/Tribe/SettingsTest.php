<?php
namespace Tribe;

use Faker\Factory;
use Tribe__Settings as Settings;

class SettingsTest extends \Codeception\TestCase\WPTestCase {

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
	}

	public function tearDown() {
		// your tear down methods here

		// then
		parent::tearDown();
	}

	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->make_instance();

		$this->assertInstanceOf( Settings::class, $sut );
	}

	/**
	 * @test
	 * it should not add network pages on non multisite installation
	 */
	public function it_should_not_add_network_pages_on_non_multisite_installation() {
		$sut = $this->make_instance();

		$this->assertFalse( $sut->should_setup_network_pages() );
	}

	/**
	 * @test
	 */
	public function it_should_print_the_default_closing_form_element() {
		$sut = $this->make_instance();

		ob_start();
		$sut->settings_form_element_close();

		$this->assertSame( '</form>', ob_get_clean() );
	}

	/**
	 * @test
	 */
	public function it_should_strip_disallowed_markup_from_the_filtered_closing_form_element() {
		$text    = Factory::create()->sentence();
		$allowed = '<p>' . $text . '</p></form>';
		add_filter(
			'tribe_settings_closing_form_element',
			static function () use ( $allowed, $text ) {
				return '<script>alert("' . $text . '")</script>' . $allowed;
			}
		);
		$sut = $this->make_instance();

		ob_start();
		$sut->settings_form_element_close();
		$output = ob_get_clean();

		$this->assertStringNotContainsString( '<script>', $output );
		$this->assertStringEndsWith( $allowed, $output );
	}

	/**
	 * @return Settings
	 */
	private function make_instance() {
		return new Settings();
	}
}
