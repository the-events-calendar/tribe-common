<?php

namespace Tribe\Utils;

class Boolean_TransformationTest extends \Codeception\TestCase\WPTestCase {

	/**
	 */
	public function test_bool_to_string_true() {
		$bool = true;
		$string = tec_bool_to_string( $bool );

		// assertSame is used to check the type as well as the value.
		$this->assertSame( $string, 'true' );
	}

	/**
	 */
	public function test_bool_to_string_false() {
		$bool = false;
		$string = tec_bool_to_string( $bool );

		$this->assertSame( $string, 'false' );
	}

	/**
	 */
	public function test_bool_to_int_true() {
		$bool = true;
		$string = tec_bool_to_int( $bool );

		$this->assertSame( $string, 1 );
	}

	/**
	 */
	public function test_bool_to_int_false() {
		$bool = false;
		$string = tec_bool_to_int( $bool );

		$this->assertSame( $string, 0 );
	}
}
