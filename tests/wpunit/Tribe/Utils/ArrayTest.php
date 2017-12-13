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

	public function list_to_array_inputs() {
		return [
			[ '', ',', [] ],
			[ ',', ',', [] ],
			[ 'foo,bar', ',', [ 'foo', 'bar' ] ],
			[ 'foo;bar', ',', [ 'foo;bar' ] ],
			[ [ 'foo', 'bar' ], ',', [ 'foo', 'bar' ] ],
			[ false, ',', [] ],
			[ null, ',', [] ],
			[ 23, ',', [ '23' ] ],
			[ '23,89,2389', ',', [ '23', '89', '2389' ] ],
			[ [ '23', '89', '2389' ], ',', [ '23', '89', '2389' ] ],
			[ '23,89,2389,,,', ',', [ '23', '89', '2389' ] ],
			[ [ '23', '89', '2389','','' ], ',', [ '23', '89', '2389' ] ],
			[ [ '23', '89', '2389','false','' ], ',', [ '23', '89', '2389', 'false' ] ],
			[ '23, 89, 2389, false' , ',', [ '23', '89', '2389', 'false' ] ],
			[ '23, 89, 2389, false, , , ' , ',', [ '23', '89', '2389', 'false' ] ],
			[ 'false, 0 ,1' , ',', [ 'false', '0', '1' ] ],
			[ [ false, 0, 1 ], ',', [ false, 0, 1 ] ],
		];
	}

	/**
	 * Test get_any
	 *
	 * @test
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

	/**
	 * Test list_to_array
	 *
	 * @test
	 * @dataProvider list_to_array_inputs
	 */
	public function test_list_to_array( $input, $sep, $expected ) {
		$this->assertEquals( $expected, \Tribe__Utils__Array::list_to_array( $input, $sep ) );
	}

}