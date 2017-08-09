<?php

namespace Tribe\Utils;

use Tribe__Utils__Array as Arr;

class ArrayTest extends \Codeception\TestCase\WPTestCase {

	public function get_any_inputs() {
		// indexes, default, expected
		return [
			[ [ 'four', 'one' ], 'foo', 4 ],
			[ [], 'foo', 'foo' ],
			[ [ 'bar' ], 'foo', 'foo' ],
			[ [ 'one', 'two' ], 'foo', 1 ],
			[ [ 'bar', 'woo', 'five' ], 'foo', 5 ],
			[ [ 'bar', 'woo', 'barbaz' ], 'foo', 'foo' ],
		];
	}

	/**
	 * Test get_any
	 *
	 * @dataProvider get_any_inputs
	 */
	public function test_get_any( $indexes, $default, $expected ) {
		$input = [
			'one'   => 1,
			'two'   => 2,
			'three' => 3,
			'four'  => 4,
			'five'  => 5,
		];

		$this->assertEquals( $expected, Arr::get_any( $input, $indexes, $default ) );
	}

}