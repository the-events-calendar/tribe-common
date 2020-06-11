<?php

namespace Tribe;

use Tribe__Cache as Cache;

class Body_ClassesTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * @return Body_Classes
	 */
	protected function make_instance() {
		return Body_Classes::instance();
	}

	/**
	 * It should be instantiatable
	 *
	 * @test
	 */
	public function be_instantiatable() {
		$this->assertInstanceOf( Body_Classes::class, $this->make_instance() );
	}

	public function it_should_add_a_single_class() {
		$body_classes = $this->make_instance();

		$body_classes->add_class( 'fnord' );

		$this->assertContains( 'fnord', $body_classes->classes );
	}
}
