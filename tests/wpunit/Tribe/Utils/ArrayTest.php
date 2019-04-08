<?php

namespace Tribe\Utils;

class ArrayTest extends \Codeception\TestCase\WPTestCase {
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
			[ [ '23', '89', '2389', '', '' ], ',', [ '23', '89', '2389' ] ],
			[ [ '23', '89', '2389', 'false', '' ], ',', [ '23', '89', '2389', 'false' ] ],
			[ '23, 89, 2389, false', ',', [ '23', '89', '2389', 'false' ] ],
			[ '23, 89, 2389, false, , , ', ',', [ '23', '89', '2389', 'false' ] ],
			[ 'false, 0 ,1', ',', [ 'false', '0', '1' ] ],
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

	public function map_or_discard_inputs() {
		return [
			'all-mapped'              => [
				[ 'a', 'b', 'c' ],
				[ 'a' => 'foo', 'b' => 'baz', 'c' => 'bar' ],
				[ 'foo', 'baz', 'bar' ],
				true
			],
			'some-mapped'             => [ [ 'a', 'b', 'c' ], [ 'a' => 'foo', 'b' => 'baz' ], [ 'foo', 'baz' ], true ],
			'one-mapped'              => [ [ 'a', 'b', 'c' ], [ 'a' => 'foo' ], [ 'foo' ], true ],
			'one-key-mapped'          => [ 'a', [ 'a' => 'foo', 'b' => 'baz' ], 'foo', true ],
			'one-key-not-mapped'      => [ 'd', [ 'a' => 'foo', 'b' => 'baz' ], false, false ],
			'one-key-mapped-to-false' => [ 'a', [ 'a' => false, 'b' => 'baz' ], false, true ],
		];
	}

	/**
	 * Test map_or_discard
	 *
	 * @dataProvider map_or_discard_inputs
	 */
	public function test_map_or_discard( $input, $map, $expected, $expected_found ) {
		$this->assertEquals( $expected, \Tribe__Utils__Array::map_or_discard( $input, $map, $found ) );
		$this->assertEquals( $expected_found, $found );
	}

	public function unprefix_keys() {
		return [
			'a_string'  => [ 'foo', 'foo' ],
			'null'      => [ null, null ],
			'an_object' => [ (object) [ '_foo' => 'bar' ], (object) [ '_foo' => 'bar' ] ],
			'no_prefix' => [ [ 'foo' => 'bar' ], [ 'foo' => 'bar' ] ],
			'w_prefix'  => [ [ '_foo' => 'bar' ], [ '_foo' => 'bar', 'foo' => 'bar' ] ],
			'w_prefix_nested' => [
				[ '_foo' => [ 'bar' => 23, '_baz' => 89 ] ],
				[
					'_foo' => [ 'bar' => 23, '_baz' => 89, 'baz' => 89 ],
					'foo'  => [ 'bar' => 23, '_baz' => 89, 'baz' => 89 ]
				],
				true
			],
			'w_prefix_nested_no_recursion' => [
				[ '_foo' => [ 'bar' => 23, '_baz' => 89 ] ],
				[
					'_foo' => [ 'bar' => 23, '_baz' => 89 ],
					'foo'  => [ 'bar' => 23, '_baz' => 89 ]
				]
			],
		];
}
	/**
	 * Test unprefix array keys
	 * @dataProvider unprefix_keys
	 */
	public function test_unprefix_array_keys($input, $expected, bool $recursive = false) {
		$this->assertEquals( $expected, \Tribe__Utils__Array::add_unprefixed_keys_to( $input, $recursive ) );
	}
}