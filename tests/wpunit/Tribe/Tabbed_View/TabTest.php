<?php
namespace Tribe\Tabbed_View;

require_once codecept_data_dir( 'classes/Tab_Extension.php' );


use Tab_Extension as Tab;

class TabTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @var \Tribe__Tabbed_View
	 */
	protected $tabbed_view;

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		$this->tabbed_view = $this->prophesize( \Tribe__Tabbed_View::class );
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

		$this->assertInstanceOf( \Tribe__Tabbed_View__Tab::class, $sut );
	}

	/**
	 * @return Tab
	 */
	private function make_instance() {
		return new Tab( $this->tabbed_view->reveal() );
	}
}
