<?php
namespace Tribe\JSON_LD;

require_once codecept_data_dir( 'classes/Tribe__JSON_LD__Test_Class.php' );

use Tribe__JSON_LD__Test_Class as Jsonld;

class AbstractTest extends \Codeception\TestCase\WPTestCase {

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

		$this->assertInstanceOf( Jsonld::class, $sut );
	}

	private function make_instance() {
		return new Jsonld();
	}

}