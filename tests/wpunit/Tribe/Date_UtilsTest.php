<?php

namespace Tribe;

use Tribe__Date_Utils as Date_Utils;

class Date_UtilsTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @var string
	 */
	protected static $tz_backup;

	protected $backupGlobals = false;

	public static function setUpBeforeClass() {
		self::$tz_backup = date_default_timezone_get();

		return parent::setUpBeforeClass();
	}

	public static function tearDownAfterClass() {
		date_default_timezone_set( self::$tz_backup );

		return parent::tearDownAfterClass();
	}

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
	}

	public function tearDown() {
		// your tear down methods here

		// then
		parent::tearDown();
	}

	public function bad_argument_formats() {
		return array_map( function ( $arr ) {
			return [ $arr ];
		},
			[
				[ 'day', 2, 3, 2012, 1 ],
				[ 2, 'week', 3, 2012, 1 ],
				[ 2, 2, 'month', 2012, 1 ],
				[ 2, 2, 3, 'year', 1 ],
				[ 2, 2, 3, 2012, 'direction' ],
				[ 2, 2, 3, 2012, 23 ],
				[ 2, 2, 3, 2012, - 2 ],
			] );
	}

	/**
	 * get_weekday_timestamp returns false for wrong argument format
	 *
	 * @dataProvider  bad_argument_formats
	 */
	public function test_get_weekday_timestamp_returns_false_if_day_of_week_is_not_int( $args ) {
		$this->assertFalse( call_user_func_array( [ 'Tribe__Date_Utils', 'get_weekday_timestamp' ], $args ) );
	}

	public function etc_natural_direction_expected_timestamps() {
		return [
			[ 1420416000, [ 1, 1, 1, 2015, 1 ] ], // Mon, first week of Jan 2015
			[ 1423094400, [ 4, 1, 2, 2015, 1 ] ], // Thursday, first week of Feb 2015
			[ 1425081600, [ 6, 4, 2, 2015, 1 ] ], // Saturday, 4th week of Feb 2015
		];
	}

	/**
	 * get_weekday_timestamp returns right timestamp etc in natural direction
	 *
	 * @dataProvider etc_natural_direction_expected_timestamps
	 */
	public function test_get_weekday_timestamp_returns_right_timestamp_in_etc_natural_direction( $expected, $args ) {
		date_default_timezone_set( 'Etc/GMT+0' );
		$this->assertEquals( $expected,
			call_user_func_array( [
				'Tribe__Date_Utils',
				'get_weekday_timestamp',
			],
				$args ) );
	}

	/**
	 * get_weekday_timestamp returns right timestamp etc -9 in natural direction
	 *
	 * @dataProvider etc_natural_direction_expected_timestamps
	 */
	public function test_get_weekday_timestamp_returns_right_timestamp_etc_minus_9_in_natural_direction( $expected, $args ) {
		date_default_timezone_set( 'Etc/GMT-9' );
		$nine_hours = 60 * 60 * 9;
		$this->assertEquals( $expected - $nine_hours,
			call_user_func_array( [
				'Tribe__Date_Utils',
				'get_weekday_timestamp',
			],
				$args ) );
	}

	/**
	 * get_weekday_timestamp returns right timestamp etc +9 in natural direction
	 *
	 * @dataProvider etc_natural_direction_expected_timestamps
	 */
	public function test_get_weekday_timestamp_returns_right_timestamp_etc_plus_9_in_natural_direction( $expected, $args ) {
		date_default_timezone_set( 'Etc/GMT+9' );
		$nine_hours = 60 * 60 * 9;
		$this->assertEquals( $expected + $nine_hours,
			call_user_func_array( [
				'Tribe__Date_Utils',
				'get_weekday_timestamp',
			],
				$args ) );
	}

	public function etc_reverse_direction_expected_timestamps() {
		return [
			[ 1422230400, [ 1, 1, 1, 2015, - 1 ] ], // Mon, last week of Jan 2015
			[ 1424908800, [ 4, 1, 2, 2015, - 1 ] ], // Thursday, last week of Feb 2015
			[ 1424476800, [ 6, 2, 2, 2015, - 1 ] ], // Saturday, penultimate week of Feb 2015
			[ 1423872000, [ 6, 3, 2, 2015, - 1 ] ], // Saturday, antepenultimate week of Feb 2015
		];
	}

	/**
	 * get_weekday_timestamp returns right timestamp etc in reverse direction
	 *
	 * @dataProvider etc_reverse_direction_expected_timestamps
	 */
	public function test_get_weekday_timestamp_returns_right_timestamp_in_etc_reverse_direction( $expected, $args ) {
		date_default_timezone_set( 'Etc/GMT+0' );
		$this->assertEquals( $expected,
			call_user_func_array( [
				'Tribe__Date_Utils',
				'get_weekday_timestamp',
			],
				$args ) );
	}

	/**
	 * get_weekday_timestamp returns right timestamp etc -9 in reverse direction
	 *
	 * @dataProvider etc_reverse_direction_expected_timestamps
	 */
	public function test_get_weekday_timestamp_returns_right_timestamp_etc_minus_9_in_reverse_direction( $expected, $args ) {
		date_default_timezone_set( 'Etc/GMT-9' );
		$nine_hours = 60 * 60 * 9;
		$this->assertEquals( $expected - $nine_hours,
			call_user_func_array( [
				'Tribe__Date_Utils',
				'get_weekday_timestamp',
			],
				$args ) );
	}

	/**
	 * get_weekday_timestamp returns right timestamp etc +9 in reverse direction
	 *
	 * @dataProvider etc_reverse_direction_expected_timestamps
	 */
	public function test_get_weekday_timestamp_returns_right_timestamp_etc_plus_9_in_reverse_direction( $expected, $args ) {
		date_default_timezone_set( 'Etc/GMT+9' );
		$nine_hours = 60 * 60 * 9;
		$this->assertEquals( $expected + $nine_hours,
			call_user_func_array( [
				'Tribe__Date_Utils',
				'get_weekday_timestamp',
			],
				$args ) );
	}

	/**
	 * unescape_date_format will return input if not a string
	 */
	public function test_unescape_date_format_will_return_input_if_not_a_string() {
		$bad_input = array( 23 );
		$this->assertEquals( $bad_input, Date_Utils::unescape_date_format( $bad_input ) );
	}

	public function date_formats_not_to_escape() {
		return [
			[ 'tribe', 'tribe' ],
			[ 'j \d\e F', 'j \d\e F' ],
			[ 'F, \e\l j', 'F, \e\l j' ],
			[ '\hH', '\hH' ],
			[ 'i\m, s\s', 'i\m, s\s' ],
			[ '\T\Z: T ', '\T\Z: T' ],
		];
	}

	/**
	 * unescape_date_format will return same string when nothing to escape
	 *
	 * @dataProvider date_formats_not_to_escape
	 */
	public function test_unescape_date_format_will_return_same_string_when_nothing_to_escape( $in ) {
		$out = Date_Utils::unescape_date_format( $in );
		$this->assertEquals( $in, $out );
	}

	public function date_formats_to_escape() {
		return [
			[ 'j \\d\\e F', 'j \d\e F' ],
			[ 'F, \\e\\l j', 'F, \e\l j' ],
			[ '\\hH', '\hH' ],
			[ 'i\\m, s\\s', 'i\m, s\s' ],
			[ '\\T\\Z: T', '\T\Z: T' ],
			[ 'j \d\\e F', 'j \d\e F' ],
			[ 'F, \e\\l j', 'F, \e\l j' ],
			[ 'i\m, s\\s', 'i\m, s\s' ],
			[ '\T\\Z: T', '\T\Z: T' ],
		];
	}

	/**
	 * unescape_date_format will return escaped date format
	 *
	 * @dataProvider date_formats_to_escape
	 */
	public function test_unescape_date_format_will_return_escaped_date_format( $in, $expected_out ) {
		$out = Date_Utils::unescape_date_format( $in );
		$this->assertEquals( $expected_out, $out );
	}

	public function reformat_inputs() {
		return [
			[ 'tomorrow 9am', 'U' ],
			[ 'tomorrow 9am', 'Y-m-d' ],
			[ 'tomorrow 9am', 'H:i:s' ],
			[ 'tomorrow 9am', 'Y-m-d H:i:s' ],
		];
	}

	/**
	 * Test reformat
	 *
	 * @test
	 * @dataProvider reformat_inputs
	 */
	public function test_reformat( $input, $format ) {
		$date = new DateTime( $input );

		$this->assertEquals( $date->format( $format ), Date_Utils::reformat( $input, $format ) );
		$this->assertEquals( $date->format( 'U' ), Date_Utils::reformat( $input, 'U' ) );
		$this->assertEquals( $date->format( $format ), Date_Utils::reformat( $date->format( 'U' ), $format ) );
		$this->assertEquals( $date->format( 'U' ), Date_Utils::reformat( $date->format( 'U' ), 'U' ) );
	}

	public function ordinal_to_number_inputs() {
		return [
			[ 'first', 1 ],
			[ 'second', 2 ],
			[ 'third', 3 ],
			[ 'fourth', 4 ],
			[ 'fifth', 5 ],
			[ 'sixth', 6 ],
			[ 'seventh', 7 ],
			[ 'eighth', 8 ],
			[ 'ninth', 9 ],
			[ 'tenth', 10 ],
			[ 'foo', false ],
			[ 23, 23 ],
			[ '23', 23 ],
			[ 23.5, 23 ],
			[ '23.5', 23 ],
		];
	}

	/**
	 * Test ordinal_to_number
	 *
	 * @test
	 * @dataProvider ordinal_to_number_inputs
	 */
	public function test_ordinal_to_number( $input, $expected ) {
		$this->assertEquals( $expected, Date_Utils::ordinal_to_number( $input ) );
	}

	public function format_date_from_datepicker_inputs() {
		return [
			[ 'today', 'Y-m-d H:i:s' ],
			[ '+20 days', 'Y-m-d H:i:s' ],
			[ '-3 days', 'Y-m-d H:i:s' ],
			[ '2016-04-05', 'Y-m-d' ],
			[ '2016-04-05 09:30:00', 'Y-m-d H:i:s' ],
			[ 1488326400, 'Y-m-d H:i:s' ] // 2016-3-1
		];
	}

	/**
	 * Test format_date_from_datepicker
	 *
	 * @dataProvider  format_date_from_datepicker_inputs
	 */
	public function test_format_date_from_datepicker( $input, $output_format ) {
		$input_timestamp = ! is_numeric( $input ) ? strtotime( $input ) : $input;
		$expected        = date( $output_format, $input_timestamp );

		$this->assertEquals( $expected, Date_Utils::format_date_from_datepicker( $input ) );
	}
}