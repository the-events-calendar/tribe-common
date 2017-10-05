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

}