<?php
namespace Tribe;

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
	 * it should not setup network pages if no root plugin is network activated
	 */
	public function it_should_not_setup_network_pages_if_no_root_plugin_is_network_activated() {
		$sut = $this->make_instance();
		$sut->set_root_plugins(['foo/bar.php']);

		$this->assertFalse($sut->should_setup_network_pages());
	}

	/**
	 * @test
	 * it should setup network pages if all root plugins are network activated
	 */
	public function it_should_setup_network_pages_if_all_root_plugins_are_network_activated() {
		$sut = $this->make_instance();
		$plugins = [ 'foo/bar.php', 'baz/foo.php', 'tec/tribe.php' ];
		$sut->set_root_plugins( $plugins );
		update_network_option(null,'active_sitewide_plugins',array_flip($plugins));


		$this->assertTrue($sut->should_setup_network_pages());
	}

	/**
	 * @test
	 * it should setup network pages if at least one root plugin is network activated
	 */
	public function it_should_setup_network_pages_if_at_least_one_root_plugin_is_network_activated() {
		$sut     = $this->make_instance();
		$plugins = [ 'foo/bar.php', 'baz/foo.php', 'tec/tribe.php' ];
		$sut->set_root_plugins( $plugins );
		update_network_option( null, 'active_sitewide_plugins', [ 'baz/foo.php' => 0 ] );


		$this->assertTrue($sut->should_setup_network_pages());
	}

	/**
	 * @return Settings
	 */
	private function make_instance() {
		return new Settings();
	}
}
