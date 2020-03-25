<?php

class Tribe__Utils__Array {
	public function get( $a, $b, $c ) {
		return 'Yes';
	}
}

class Tribe__Utils__Array_Test extends \Codeception\Test\Unit {

	/**
	 * It should overwrite the default class
	 *
	 * @test
	 */
	public function should_overwrite_the_default_class() {
		$faked = new \Tribe__Utils__Array();
		$this->assertEquals( 'Yes', $faked->get( [], [ 0, 1, 2 ], 4 ) );
	}

	/**
	 * It should not have methods on overwritten class
	 *
	 * @dataProvider notDefinedMethodsProvider
	 *
	 * @test
	 */
	public function ShouldNotHaveMethodOnOverwrittenClass( $method ) {
		$faked = new \Tribe__Utils__Array();
		$this->assertFalse( method_exists( $faked, $method ) );
	}

	public function notDefinedMethodsProvider() {
		return [
			[ 'set' ],
			[ 'get_in_any' ],
			[ 'strpos' ],
			[ 'list_to_array' ],
			[ 'to_list' ],
			[ 'escape_multidimensional_array' ],
			[ 'map_or_discard' ],
			[ 'add_unprefixed_keys_to' ],
			[ 'filter_prefixed' ],
			[ 'flatten' ],
			[ 'add_prefixed_keys_to' ],
			[ 'recursive_ksort' ],
			[ 'get_first_set' ],
		];
	}
}
