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
	 * Test list_to_array
	 *
	 * @test
	 * @dataProvider list_to_array_inputs
	 */
	public function test_list_to_array( $input, $sep, $expected ) {
		$this->assertEquals( $expected, \Tribe__Utils__Array::list_to_array( $input, $sep ) );
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

	public function associative_arrays() {
		return [
			[ [], false ],
			[ '', false ],
			[ 'foo', false ],
			[ 'foo,bar', false ],
			[ new \stdClass(), false ],
			[ [ 23, 89 ], false ],
			[ [ 'foo' => 23, 'baz' => 89 ], true ],
			[ [ 'foo' => 23 ], true ],
			[ [ 'foo' => '' ], true ],
			[ [ '0' => '', '1' => 'bar' ], false ],
			[ [ 0 => '', 1 => 'bar', 5 => 'baz' ], false ],
		];
	}

	/**
	 * It should correctly mark associative arrays
	 *
	 * @test
	 *
	 * @dataProvider associative_arrays
	 */
	public function should_correctly_mark_associative_arrays( $arr, $is_assoc ) {
		$this->assertEquals( $is_assoc, Arr::is_associative( $arr ) );
	}

	public function extract_values_input() {
		return [
			[ [], [] ],
			[ [ '' ], [ '' ] ],
			[ [ '', 'foo' ], [ '', 'foo' ] ],
			[ [ '', 'foo' => 'bar' ], [ '', 'bar' ] ],
			[ [ 'foo' => 'bar', 'baz' => 23 ], [ 'bar', 23 ] ],
			[ [ 'foo' => [ 'bar' ], 'baz' => 23 ], [ 'bar', 23 ] ],
			[ [ 'foo' => [ 'bar' ], 'baz' => [ 23 ] ], [ 'bar', 23 ] ],
			[ [ 'foo' => [ 'bar', 89 ], 'baz' => [ 23 ] ], [ 'bar', 89, 23 ] ],
			[ [ 'foo' => [ 'bar', 89 ], 'baz' => [ 23, 54 ] ], [ 'bar', 89, 23, 54 ] ],
			[ [ 'foo' => 'bar', 'baz' => [ 23, 54 ] ], [ 'bar', 23, 54 ] ],
			// one level deep
			[ [ 'foo' => 'bar', 'baz' => [ 'sub1' => 23, 'sub2' => 19 ] ], [ 'bar', 23, 19 ] ],
			// two levels deep
			[ [ 'foo' => 'bar', 'baz' => [ 'sub1' => [ 23, 89 ], 'sub2' => 19 ] ], [ 'bar', [ 23, 89 ], 19 ] ],
		];
	}

	/**
	 * Test extract_values
	 *
	 * @dataProvider extract_values_input
	 */
	public function test_extract_values( $input, $expected ) {
		$this->assertEquals( $expected, \Tribe__Utils__Array::extract_values( $input ) );
	}

	public function filter_null_input() {
		return [
			[ [ '' ], [ '' ] ],
			[ [ '', null ], [ '' ] ],
			[ [ '', 'foo' ], [ '', 'foo' ] ],
			[ [ '', 'foo' => 'bar' ], [ '', 'foo' => 'bar' ] ],
			[ [ '', 0, false ], [ '', 0, false ] ],
			[ [ '', 0, null, false, 'null' ], [ 0 => '', 1 => 0, 3 => false, 4 => 'null' ] ],
			[ [ 'foo' => 'bar', 'baz' => 23, 'empty' => [ 'test' => null ] ], [ 'foo' => 'bar', 'baz' => 23, 'empty' => [ 'test' => null ] ] ],
		];
	}

	/**
	 * Test extract_values
	 *
	 * @dataProvider filter_null_input
	 */
	public function test_filter_null( $input, $expected ) {
		$this->assertEquals( $expected, \Tribe__Utils__Array::filter_null( $input ) );
	}
}
