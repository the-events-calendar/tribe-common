<?php
namespace Tribe;

use Tribe__Settings as Settings;

class SettingsTest extends \Codeception\TestCase\WPTestCase {

	public function setUp(): void {
		// before
		parent::setUp();

		// your set up methods here
	}

	public function tearDown(): void {
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
	 * @return Settings
	 */
	private function make_instance() {
		return new Settings();
	}
}
