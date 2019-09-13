<?php

use Codeception\Test\Unit;

class utilsTest extends Unit {

	/**
	 * @dataProvider provider_get_successful_class_instance
	 */
	public function test_successfully_getting_a_class_instance( $class ) {
		$this->assertTrue( is_object( tribe_get_class_instance( $class ) ) );
	}

	/**
	 * @dataProvider provider_get_unsuccessful_class_instance
	 */
	public function test_unsuccessfully_getting_a_class_instance( $class ) {
		$this->assertNull( tribe_get_class_instance( $class ) );
	}

	/**
	 * Different ways to successfully get a class instance.
	 *
	 * @see \tribe_get_class_instance()
	 *
	 * @return Generator
	 */
	private function provider_get_successful_class_instance() {
		yield 'class slug registered with tribe()' => [ 'assets' ];
		yield 'class string not registered with tribe()' => [ 'Tribe__App_Shop' ];
		yield 'class string that has instance()' => [ new Tribe__App_Shop() ];
		yield 'class string that has get_instance()' => [ 'WP_Post' ];
	}

	/**
	 * Different ways to fail at getting a class instance (should all return null).
	 *
	 * @see \tribe_get_class_instance()
	 *
	 * @return Generator
	 */
	private function provider_get_unsuccessful_class_instance() {
		yield 'empty string' => [ '' ];
		yield 'class not found' => [ 'ABC_123_XYZ' ];
		yield 'not an object or a string' => [ [] ];
	}

}