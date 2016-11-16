<?php
namespace Tribe\PUE;

use Tribe__PUE__Checker as Checker;

class CheckerTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @var string
	 */
	protected $pue_update_url = 'pue_update_url';

	/**
	 * @var string
	 */
	protected $slug = 'event-aggregator';

	/**
	 * @var string
	 */
	protected $plugin_file = 'event-aggregator/event-aggregator.php';

	/**
	 * @var string
	 */
	protected $network_plugin_file = 'the-events-calendar/the-events-calendar.php';

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

		$this->assertInstanceOf( Checker::class, $sut );
	}

	/**
	 * @test
	 * it should always show editable license field on non network installations
	 */
	public function it_should_always_show_editable_license_field_on_non_network_installations() {
		$sut = $this->make_instance();

		$this->assertTrue( $sut->should_show_subsite_editable_license() );
	}

	/**
	 * @return Checker
	 */
	private function make_instance() {
		return new Checker( $this->pue_update_url, $this->slug, $this->plugin_file );
	}

}