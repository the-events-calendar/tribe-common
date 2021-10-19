<?php

namespace Tribe\Editor\Compatibility;

use Classic_Editor as Editor;
use Tribe\Tests\Traits\With_Uopz;

class EditorTest extends \Codeception\TestCase\WPTestCase {
	use With_Uopz;

	function setUp() {
		parent::setUp();

		$this->editor = $this->make_instance();
	}

	public function tearDown() {
		unset( $this->editor );

		// then
		parent::tearDown();
	}

	/**
	 * It should be instantiatable
	 *
	 * @test
	 */
	public function be_instantiatable() {
		$this->assertInstanceOf( Editor::class, $this->make_instance() );
	}

	/**
	 * @return Editor
	 */
	protected function make_instance() {
		return new Editor( [] );
	}

}
