<?php

namespace Tribe;

use Tribe__Timezones as Timezones;

class TimezonesTest extends \Codeception\TestCase\WPTestCase {
	public function localizes_date_timestamp_inputs() {
		return [
			[ strtotime( 'today' ), 'UTC' ],
			[ strtotime( 'today' ), 'America/New_York' ],
			[ strtotime( 'tomorrow 9am' ), 'UTC' ],
			[ strtotime( 'tomorrow 9am' ), 'America/New_York' ],
		];
	}

	/**
	 * Test localize_date with timestamps
	 *
	 * @dataProvider localizes_date_timestamp_inputs
	 */
	public function test_localize_date_with_timestamps( $timestamp, $timezone_string ) {
		tribe( 'cache' )->reset();

		update_option( 'timezone_string', $timezone_string );

		$format = 'Y-m-d H:i:s';

		$date = new \DateTime();
		$date->setTimestamp( $timestamp );
		$date->setTimezone( new \DateTimeZone( $timezone_string ) );
		$expected = $date->format( $format );

		$this->assertEquals( $expected, Timezones::localize_date( $format, $timestamp ) );
	}

	/**
	 * Test localize_date sanity check
	 *
	 */
	public function test_localize_date_sanity_check() {
		tribe( 'cache' )->reset();

		$format = 'Y-m-d H:i:s';

		update_option( 'timezone_string', 'UTC' );
		$utc_date = Timezones::localize_date( $format, strtotime( 'tomorrow' ) );

		tribe( 'cache' )->reset();

		update_option( 'timezone_string', 'America/New_York' );
		$offset_date = Timezones::localize_date( $format, strtotime( 'tomorrow' ) );

		$this->assertNotEquals( $utc_date, $offset_date );
		$this->assertLessThan( $utc_date, $offset_date );
	}

	/**
	 * Test localize_date return false if date string is not parseable
	 *
	 */
	public function test_localize_date_return_false_if_date_string_is_not_parseable() {
		$this->assertFalse( Timezones::localize_date( 'Y-m-d H:i:s', 'foo bar' ) );
	}

	/**
	 * Test localize_date returns false if timezone string is not valid
	 *
	 */
	public function test_localize_date_returns_false_if_timezone_string_is_not_valid() {
		$this->assertFalse( Timezones::localize_date( 'Y-m-d H:i:s', 'tomorrow 9am', 'This is not a timezone' ) );
	}

	public function convert_from_timezone_inputs() {
		return [
			[ 'tomorrow 11am', 'America/New_York', 'UTC' ],
			[ 'tomorrow 11am', 'America/New_York', 'America/New_York' ],
			[ 'tomorrow 11am', 'UTC', 'UTC' ],
			[ 'tomorrow 11am', 'UTC', 'America/New_York' ],
		];
	}

	/**
	 * Test convert_date_from_timezone
	 *
	 * @dataProvider convert_from_timezone_inputs
	 */
	public function test_convert_date_from_timezone( $date, $from_timezone, $to_timezone ) {
		$from_date = new \DateTime( $date, new \DateTimeZone( $from_timezone ) );
		$from_date_timestamp = $from_date->format( 'U' );
		$to_date = new \DateTime( "@{$from_date_timestamp}", new \DateTimeZone( $to_timezone ) );

		$format = 'Y-m-d H:i:s';
		$expected = $to_date->format( $format );
		$expected_timestamp = $to_date->format( 'U' );

		$this->assertEquals( $expected, Timezones::convert_date_from_timezone( $date, $from_timezone, $to_timezone, $format ) );
		$this->assertEquals( $expected_timestamp, Timezones::convert_date_from_timezone( $date, $from_timezone, $to_timezone, 'U' ) );
		$this->assertEquals( $expected, Timezones::convert_date_from_timezone( $from_date_timestamp, $from_timezone, $to_timezone, $format ) );
		$this->assertEquals( $expected_timestamp, Timezones::convert_date_from_timezone( $from_date_timestamp, $from_timezone, $to_timezone, 'U' ) );
		if ( $from_timezone === $to_timezone ) {
			$this->assertEquals( $from_date_timestamp, Timezones::convert_date_from_timezone( $date, $from_timezone, $to_timezone, 'U' ) );
		}
	}

	public function is_valid_timezone_inputs() {
		return [
			[ '', false ],
			[ 'foo', false ],
			[ 'foo bar', false ],
			[ 23, false ],
			[ '23', false ],
			[ 'Europe/Rome', true ],
			[ 'America/New_York', true ],
			[ 'UTC', true ],
			[ 'UTC+5', true ],
			[ 'UTC-5', true ],
			[ 'UTC+5.5', true ],
			[ 'UTC-5.5', true ],
		];
	}

	/**
	 * Test is_valid_timezone
	 * @dataProvider is_valid_timezone_inputs
	 */
	public function test_is_valid_timezone( $input, $expected ) {
		$this->assertEquals( $expected, \Tribe__Timezones::is_valid_timezone( $input ) );
	}

	public function is_utc_offset_input() {
		return [
			[ 'UTC', 'UTC' ],
			[ 'UTC-123', 'UTC' ],
			[ 'UTC+123', 'UTC' ],
			[ 'UTC+9:30', 'Australia/Adelaide' ],
			[ 'UTC+9.5', 'Australia/Adelaide' ],
		];
	}

	/**
	 * Test get_valid_timezone
	 *
	 * @dataProvider get_valid_timezone
	 *
	 * @param $input
	 * @param $expected
	 */
	public function test_get_valid_timezone( $input, $expected ) {
		$this->assertEquals( $expected, \Tribe__Timezones::get_valid_timezone( $input ) );
	}

	public function get_valid_timezone() {
		return [
			[ 'UTC', 'UTC' ],
			[ 'UTC-0', 'UTC' ],
			[ 'UTC+0', 'UTC' ],
		];
	}

	/**
	 * Test generate_timezone_string_from_utc_offset
	 *
	 * @dataProvider  is_utc_offset_input
	 * @since 4.7.12
	 *
	 */
	public function test_generate_timezone_string_from_utc_offset( $input, $expected ) {
		$this->assertEquals( $expected, \Tribe__Timezones::generate_timezone_string_from_utc_offset( $input ) );
	}
}
