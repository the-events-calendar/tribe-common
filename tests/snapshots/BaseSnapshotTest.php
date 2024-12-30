<?php

use tad\Codeception\SnapshotAssertions\SnapshotAssertions;
use Tribe\Tests\Traits\With_Uopz;

class BaseSnapshotTest extends \Codeception\TestCase\WPTestCase {
	use With_Uopz;
	use SnapshotAssertions;

	/**
	 * It should correctly test a string snapshot
	 *
	 * @test
	 */
	public function should_correctly_test_a_string_snapshot() {
		$this->set_fn_return( 'time', '1577750400' );
		$string = 'test the date function can be mocked: ' . date( 'Y-m-d H:i:s', time() );

		$this->assertMatchesStringSnapshot( $string );
	}
}
