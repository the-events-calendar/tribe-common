<?php

namespace Tribe\Utils;

class Lazy_StringTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * It should return the raw string value with __toString
	 *
	 * @test
	 */
	public function should_return_the_raw_string_value_with_to_string() {
		$string = new Lazy_String(
			static function () {
				return 'test';
			}
		);

		$this->assertEquals( 'test', $string->__toString() );
	}

	/**
	 * It should call the value callback just once
	 *
	 * @test
	 */
	public function should_call_the_value_callback_just_once() {
		$string = new Lazy_String(
			function () {
				yield 'test';
				$this->fail( 'The value callback should be called just once.' );
			}
		);

		$string->__toString();
		$string->__toString();
		$string->__toString();
	}

	/**
	 * It should allow getting the escaped version of the string
	 *
	 * @test
	 */
	public function should_allow_getting_the_escaped_version_of_the_string() {
		$string = new Lazy_String(
			static function () {
				return 'Dungeons & Dragons';
			},
			'esc_html'
		);

		$this->assertEquals( 'Dungeons & Dragons', $string->value() );
		$this->assertEquals( 'Dungeons &amp; Dragons', $string->escaped() );
	}

	/**
	 * It should be serializable
	 *
	 * @test
	 */
	public function should_be_serializable() {
		$string = new Lazy_String(
			static function () {
				return 'Dungeons & Dragons';
			},
			'esc_html'
		);

		$serialized = serialize($string);
		$unserialized = unserialize($serialized);

		$this->assertEquals('Dungeons & Dragons', $unserialized->value() );
		$this->assertEquals('Dungeons &amp; Dragons', $unserialized->escaped() );
	}

	/**
	 * It should return the string when setting the escaped callback to false
	 *
	 * @test
	 */
	public function should_return_the_string_when_setting_the_escaped_callback_to_false() {
		$string = new Lazy_String(
			static function () {
				return 'Dungeons & Dragons';
			},
			false
		);

		$this->assertEquals('Dungeons & Dragons', $string->value() );
		$this->assertEquals('Dungeons & Dragons', $string->escaped() );
	}

	/**
	 * It should expand when json_encoded
	 *
	 * @test
	 */
	public function should_expand_when_json_encoded() {
		$string = new Lazy_String(
			static function () {
				return 'Dungeons & Dragons';
			},
			false
		);

		$json = json_encode( [ 'string' => $string ] );

		$this->assertEquals( json_encode( [ 'string' => 'Dungeons & Dragons' ] ), $json );
	}
}