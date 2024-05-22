<?php
namespace Tribe\Utils;

use Tribe__Utils__JSON as JSON;

/**
 * Class JSONTest
 *
 * @package Tribe\Utils
 */
class JSONTest extends \Codeception\TestCase\WPTestCase {

	public function strings_and_escapes() {
		return [
			[ 'A string', 'A string' ],
			[ 'A string with "quotes"', 'A string with \"quotes\"' ],
			[ 'A string with "quotes" and /', 'A string with \"quotes\" and \/' ],
			[ 'http://some.url', 'http:\/\/some.url' ],
		];
	}

	public function strings_arrays_and_escapes() {
		return [
			[ [ 'foo', 'some "quotes"', 'bar' ], [ 'foo', 'some \"quotes\"', 'bar' ] ],
			[ [ 'foo', 'some "quotes"', 'really more "quotes"' ], [ 'foo', 'some \"quotes\"', 'really more \"quotes\"' ] ],
			[ [ 'http://some.url', 'some "quotes"', 'really more "quotes"' ], [ 'http:\/\/some.url', 'some \"quotes\"', 'really more \"quotes\"' ] ],
		];
	}

	public function naughty_strings() {
		return [
			[ "\"><script>alert(123)</script>", '"\"><script>alert(123)<\/script>"' ],
			[ "\"><script>alert(123);</script x=\"", '"\"><script>alert(123);<\/script x=\""' ],
			[ "' autofocus onkeyup='javascript:alert(123)", '"\' autofocus onkeyup=\'javascript:alert(123)"' ],
		];
	}

	/**
	 * @test
	 * it should escape quotes in strings
	 * @dataProvider strings_and_escapes
	 */
	public function it_should_escape_quotes_in_strings( $in, $expected ) {
		$out = JSON::escape_string( $in );

		$this->assertEquals( $expected, $out );
	}

	/**
	 * @test
	 * it should escape strings in arrays
	 * @dataProvider strings_arrays_and_escapes
	 */
	public function it_should_escape_strings_in_arrays( $in, $expected ) {
		$out = JSON::escape_string( $in );

		$this->assertEquals( $expected, $out );
	}


	/**
	 * @test
	 * it should preserve keys in associative arrays when escaping
	 */
	public function it_should_preserve_keys_in_associative_arrays_when_escaping() {
		$out = JSON::escape_string( [ 'foo' => 'http://some.url', 'baz' => 'some "quotes"', 'bar' => 'really more "quotes"' ] );

		$this->assertEquals( [ 'foo' => 'http:\/\/some.url', 'baz' => 'some \"quotes\"', 'bar' => 'really more \"quotes\"' ], $out );
	}

	/**
	 * @test
	 * it should escape nested arrays
	 */
	public function it_should_escape_nested_arrays() {
		$out = JSON::escape_string( [
			'foo' => 'http://some.url',
			'baz' => 'some "quotes"',
			'bar' => [
				'one'   => 'string',
				'two'   => 'string with "quotes"',
				'three' => 'http://some.url',
			]
		] );

		$expected = [
			'foo' => 'http:\/\/some.url',
			'baz' => 'some \"quotes\"',
			'bar' => [
				'one'   => 'string',
				'two'   => 'string with \"quotes\"',
				'three' => 'http:\/\/some.url',
			]
		];

		$this->assertEquals( $expected, $out );
	}

	/**
	 * @test
	 * it should not modify non array or string values
	 */
	public function it_should_not_modify_non_array_or_string_values() {
		$out = JSON::escape_string( [
			'foo'           => 'http://some.url',
			'baz'           => 'some "quotes"',
			'bar'           => [
				'one'   => 'string',
				'two'   => 'string with "quotes"',
				'three' => 'http://some.url',
			],
			'dont-touch-me' => (object) [ 'I am' => 'an object' ],
			'a-number'      => 23
		] );

		$expected = [
			'foo'           => 'http:\/\/some.url',
			'baz'           => 'some \"quotes\"',
			'bar'           => [
				'one'   => 'string',
				'two'   => 'string with \"quotes\"',
				'three' => 'http:\/\/some.url',
			],
			'dont-touch-me' => (object) [ 'I am' => 'an object' ],
			'a-number'      => 23
		];

		$this->assertEquals( $expected, $out );
	}

	/**
	 * @test
	 * it should not modify non array or string values in arrays
	 */
	public function it_should_not_modify_non_array_or_string_values_in_arrays() {
		$out = JSON::escape_string( [
			'foo' => 'http://some.url',
			'baz' => 'some "quotes"',
			'bar' => [
				'one'           => 'string',
				'two'           => 'string with "quotes"',
				'three'         => 'http://some.url',
				'dont-touch-me' => (object) [ 'I am' => 'an object' ],
				'a-number'      => 23
			],
		] );

		$expected = [
			'foo' => 'http:\/\/some.url',
			'baz' => 'some \"quotes\"',
			'bar' => [
				'one'           => 'string',
				'two'           => 'string with \"quotes\"',
				'three'         => 'http:\/\/some.url',
				'dont-touch-me' => (object) [ 'I am' => 'an object' ],
				'a-number'      => 23
			],
		];

		$this->assertEquals( $expected, $out );
	}

	/**
	 * @test It should not modify a passed int.
	 *
	 * @since 4.9.6
	 *
	 * @testWith [ 666 ]
	 *           [ 1 ]
	 */
	public function it_should_not_modify_int( $in ) {

		$expected = $in;
		$out      = JSON::escape_string( $in );

		$this->assertEquals( $expected, $out );
	}

	/**
	 * @test It should should escape naughty strings.
	 *
	 * @since 4.9.6
	 *
	 * @dataProvider naughty_strings
	 */
	public function it_should_escape_naughty_strings_accurately( $in, $expected ) {
		$out = json_encode( $in );

		$this->assertEquals( $expected, $out );
	}

}