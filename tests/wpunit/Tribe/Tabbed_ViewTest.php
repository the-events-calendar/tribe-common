<?php

namespace Tribe;

require_once codecept_data_dir( 'classes/Tabbed_View_Extension.php' );

use Tabbed_View_Extension as Tabbed_View;

class Tabbed_ViewTest extends \Codeception\TestCase\WPTestCase {

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

		$this->assertInstanceOf( \Tribe__Tabbed_View::class, $sut );
	}

	/**
	 * @return Tabbed_View
	 */
	private function make_instance() {
		return new Tabbed_View();
	}

}