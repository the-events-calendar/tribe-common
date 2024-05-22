<?php

namespace Tribe\Utils;

class Element_AttributesTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * Test w/ empty attributes
	 */
	public function test_w_empty_attributes() {
		$attributes = new Element_Attributes();

		$this->assertEquals( [], $attributes->get_attributes_array() );
		$this->assertEquals( '', $attributes->get_attributes_as_string() );
		$this->assertEquals( '', $attributes->get_attributes() );
	}

	/**
	 * Test with numeric values
	 */
	public function test_with_numeric_values() {
		$attributes = new Element_Attributes( 23, 89 );

		$this->assertEquals( [], $attributes->get_attributes_array() );
		$this->assertEquals( '', $attributes->get_attributes_as_string() );
		$this->assertEquals( '', $attributes->get_attributes() );
	}

	/**
	 * Test w/ array argument
	 */
	public function test_w_array_argument() {
		$attributes = new Element_Attributes( [ 'disabled', 'checked' ] );

		$this->assertEquals( [ 'disabled', 'checked' ], $attributes->get_attributes_array() );
		$this->assertEquals( 'disabled checked', $attributes->get_attributes_as_string() );
		$this->assertEquals( ' disabled checked ', $attributes->get_attributes() );
	}

	/**
	 * Test with associative array arguments
	 */
	public function test_with_associative_array_arguments() {
		$attributes = new Element_Attributes( [
			'disabled' => false,
			'checked'  => true,
			'foo'      => 'bar',
			'baz'      => 'woot'
		] );

		$this->assertEquals( [ 'checked', 'foo="bar"', 'baz="woot"' ], $attributes->get_attributes_array() );
		$this->assertEquals( 'checked foo="bar" baz="woot"', $attributes->get_attributes_as_string() );
		$this->assertEquals( ' checked foo="bar" baz="woot" ', $attributes->get_attributes() );
	}

	/**
	 * Test with string arguments
	 */
	public function test_with_string_arguments() {
		$attributes = new Element_Attributes( 'checked foo="bar" baz="woot"' );

		$this->assertEquals( [ 'checked', 'foo="bar"', 'baz="woot"' ], $attributes->get_attributes_array() );
		$this->assertEquals( 'checked foo="bar" baz="woot"', $attributes->get_attributes_as_string() );
		$this->assertEquals( ' checked foo="bar" baz="woot" ', $attributes->get_attributes() );
	}

	/**
	 * Test with callable arguments
	 */
	public function test_with_callable_arguments() {
		$prefix = static function ( array $attrs ) {
			$prefixed = [];

			foreach ( $attrs as $key => $value ) {
				$prefixed[ 'tribe_' . $key ] = $value;
			}

			return $prefixed;
		};

		$attributes = new Element_Attributes( 'checked foo="bar"', $prefix );

		$this->assertEquals( [ 'tribe_checked', 'tribe_foo="bar"' ],
			$attributes->get_attributes_array() );
		$this->assertEquals( 'tribe_checked tribe_foo="bar"',
			$attributes->get_attributes_as_string() );
		$this->assertEquals( ' tribe_checked tribe_foo="bar" ', $attributes->get_attributes() );
	}

	/**
	 * Test with object
	 */
	public function test_with_object() {
		$attributes          = new \stdClass();
		$attributes->checked = true;
		$attributes->foo     = 'bar';
		$attributes          = new Element_Attributes( $attributes );

		$this->assertEquals( [ 'checked', 'foo="bar"' ],
			$attributes->get_attributes_array() );
		$this->assertEquals( 'checked foo="bar"',
			$attributes->get_attributes_as_string() );
		$this->assertEquals( ' checked foo="bar" ', $attributes->get_attributes() );
	}

	/**
	 * Test invoke
	 */
	public function test_invoke() {
		$attributes = new Element_Attributes();

		$this->assertEquals( ' checked foo="bar" ', $attributes( [ 'checked', 'foo="bar"' ] ) );
	}

	/**
	 * Test __toString
	 */
	public function test__to_string() {
		$attributes = new Element_Attributes( 'checked foo="bar"' );

		$this->assertEquals( ' checked foo="bar" ', '' . $attributes );
	}

	/**
	 * Test with combinations
	 */
	public function test_with_combinations() {
		$disable_if_foo = static function ( array $attributes ) {
			if ( isset( $attributes['foo'] ) ) {
				$attributes['disabled'] = true;
			}

			return $attributes;
		};
		$prefix = static function ( array $attributes ) {
			$prefixed = [];
			foreach ( $attributes as $key => $value ) {
				$prefixed_key              = is_bool( $value ) ? $key : 'tribe_' . $key;
				$prefixed[ $prefixed_key ] = $value;
			}

			return $prefixed;
		};

		$attributes = new Element_Attributes( 'checked foo="bar"', $disable_if_foo, $prefix );

		$this->assertEquals( [ 'checked', 'tribe_foo="bar"', 'disabled' ],
			$attributes->get_attributes_array() );
		$this->assertEquals( 'checked tribe_foo="bar" disabled',
			$attributes->get_attributes_as_string() );
		$this->assertEquals( ' checked tribe_foo="bar" disabled ', $attributes->get_attributes() );
	}
}
