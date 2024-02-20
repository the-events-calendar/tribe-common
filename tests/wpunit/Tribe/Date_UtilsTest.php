<?php

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

		// Default timezone to UTC at beginning of each test
		date_default_timezone_set( 'UTC' );
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
				'get_weekday_timestamp'
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
				'get_weekday_timestamp'
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
				'get_weekday_timestamp'
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
				'get_weekday_timestamp'
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
				'get_weekday_timestamp'
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
				'get_weekday_timestamp'
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
			[ 'tomorrow 9am', false ],
			[ 'tomorrow 9am', null ],
			[ 'tomorrow 9am', 0 ],
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

	/**
	 * Test reformat w/ timezone.
	 *
	 * @test
	 * @dataProvider reformat_inputs
	 */
	public function test_reformat_with_timezone( $input, $format ) {
		$tz   = new DateTimeZone( 'America/New_York' );
		$date = new DateTime( $input, $tz );

		$this->assertEquals( $date->format( $format ), Date_Utils::reformat( $input, $format, $tz->getName() ) );
		$this->assertEquals( $date->format( 'U' ), Date_Utils::reformat( $input, 'U', $tz->getName() ) );

		// Defaults to wp_timezone
		$date = new DateTime( $input, wp_timezone() );
		$this->assertEquals( $date->format( $format ), Date_Utils::reformat( $input, $format ) );
		$this->assertEquals( $date->format( 'U' ), Date_Utils::reformat( $input, 'U' ) );
	}

	public function build_date_object_empty_data_set() {
		return [
			'zero'           => [ 0 ],
			'empty_string'   => [ '' ],
			'false'          => [ false ],
			'foo_bar_string' => [ 'foo bar' ],
		];
	}

	/**
	 * @dataProvider build_date_object_empty_data_set
	 */
	public function test_building_date_object_for_empty_will_return_today_date( $input ) {
		$expected = ( new \DateTime( 'now' ) )->format( 'Y-m-d' );
		// Do not test to the second as run times might yield false negatives.
		$this->assertEquals( $expected, Date_Utils::build_date_object( $input )->format( Date_Utils::DBDATEFORMAT ) );
		$this->assertEquals( $expected, Date_Utils::mutable( $input )->format( Date_Utils::DBDATEFORMAT ) );
		$this->assertEquals( $expected, Date_Utils::immutable( $input )->format( Date_Utils::DBDATEFORMAT ) );
	}

	public function build_date_object_data_set() {
		yield '2019-12-01 08:00:00 string' => [ '2019-12-01 08:00:00', '2019-12-01 08:00:00' ];
		yield '2019-12-01 08:00:00 DateTime' => [ new DateTime( '2019-12-01 08:00:00' ), '2019-12-01 08:00:00' ];
		yield '2019-12-01 08:00:00 DateTimeImmutable' => [
			new DateTimeImmutable( '2019-12-01 08:00:00' ),
			'2019-12-01 08:00:00'
		];
		yield '2019-12-01 08:00:00 timestamp' => [
			( new DateTime( '2019-12-01 08:00:00' ) )->getTimestamp(),
			'2019-12-01 08:00:00'
		];

		$timezone_str = 'Europe/Paris';
		$timezone_obj = new DateTimeZone($timezone_str);

		yield '2019-12-01 08:00:00 string w/ timezone' => [
			'2019-12-01 08:00:00',
			'2019-12-01 08:00:00',
			$timezone_str,
		];
		yield '2019-12-01 08:00:00 DateTime w/timezone' => [
			new DateTime( '2019-12-01 08:00:00', $timezone_obj ),
			'2019-12-01 08:00:00',
			$timezone_str,
		];
		yield '2019-12-01 08:00:00 DateTimeImmutable w/ timezone' => [
			new DateTimeImmutable( '2019-12-01 08:00:00', $timezone_obj ),
			'2019-12-01 08:00:00',
			$timezone_str,
		];
		yield '2019-12-01 08:00:00 timestamp w/ timezone' => [
			( new DateTime( '2019-12-01 08:00:00', $timezone_obj ) )->getTimestamp(),
			'2019-12-01 07:00:00',
			$timezone_str,
		];

		yield '2019-12-01 08:00:00 string w/ timezone obj' => [
			'2019-12-01 08:00:00',
			'2019-12-01 08:00:00',
			$timezone_obj,
		];
		yield '2019-12-01 08:00:00 DateTime w/timezone' => [
			new DateTime( '2019-12-01 08:00:00', $timezone_obj ),
			'2019-12-01 08:00:00',
			$timezone_obj,
		];
		yield '2019-12-01 08:00:00 DateTimeImmutable w/ timezone obj' => [
			new DateTimeImmutable( '2019-12-01 08:00:00', $timezone_obj ),
			'2019-12-01 08:00:00',
			$timezone_obj,
		];
		yield '2019-12-01 08:00:00 timestamp w/ timezone obj' => [
			( new DateTimeImmutable( '2019-12-01 08:00:00', $timezone_obj ) )->getTimestamp(),
			'2019-12-01 07:00:00',
			$timezone_obj,
		];
	}

	/**
	 * @dataProvider build_date_object_data_set
	 */
	public function test_build_date_object( $input, $expected, $timezone = null ) {
		$this->assertEquals(
			$expected,
			Date_Utils::build_date_object( $input, $timezone )->format( Date_Utils::DBDATETIMEFORMAT )
		);
		$this->assertEquals(
			$expected,
			Date_Utils::mutable( $input, $timezone )->format( Date_Utils::DBDATETIMEFORMAT )
		);
		$this->assertEquals(
			$expected,
			Date_Utils::immutable( $input, $timezone )->format( Date_Utils::DBDATETIMEFORMAT )
		);
	}


	/**
	 * @todo 2020 March 8 America/Chicago 2am day-light savings starts
	 * @todo 2020 November 1 America/Chicago 2am day-light savings ends
	 */
	public function build_shifted_start_of_day() {
		$date           = '2020-02-05';
		$date_plus_one  = '2020-02-06';
		$date_minus_one = '2020-02-04';
		$array_of_tests = [
			[ "$date 00:00:00", "$date 00:00:00", "00:00" ],
			[ "$date 00:00:00", "$date_minus_one 00:30:00", "00:30" ],
			[ "$date 00:00:00", "$date_minus_one 01:00:00", "01:00" ],
			[ "$date 00:00:00", "$date_minus_one 01:30:00", "01:30" ],
			[ "$date 00:00:00", "$date_minus_one 02:00:00", "02:00" ],
			[ "$date 00:00:00", "$date_minus_one 02:30:00", "02:30" ],
			[ "$date 00:00:00", "$date_minus_one 03:00:00", "03:00" ],
			[ "$date 00:00:00", "$date_minus_one 03:30:00", "03:30" ],
			[ "$date 00:00:00", "$date_minus_one 04:00:00", "04:00" ],
			[ "$date 00:00:00", "$date_minus_one 04:30:00", "04:30" ],
			[ "$date 00:00:00", "$date_minus_one 05:00:00", "05:00" ],
			[ "$date 00:00:00", "$date_minus_one 05:30:00", "05:30" ],
			[ "$date 00:00:00", "$date_minus_one 06:00:00", "06:00" ],
			[ "$date 00:00:00", "$date_minus_one 06:30:00", "06:30" ],
			[ "$date 00:00:00", "$date_minus_one 07:00:00", "07:00" ],
			[ "$date 00:00:00", "$date_minus_one 07:30:00", "07:30" ],
			[ "$date 00:00:00", "$date_minus_one 08:00:00", "08:00" ],
			[ "$date 00:00:00", "$date_minus_one 08:30:00", "08:30" ],
			[ "$date 00:00:00", "$date_minus_one 09:00:00", "09:00" ],
			[ "$date 00:00:00", "$date_minus_one 09:30:00", "09:30" ],
			[ "$date 00:00:00", "$date_minus_one 10:00:00", "10:00" ],
			[ "$date 00:00:00", "$date_minus_one 10:30:00", "10:30" ],
			[ "$date 00:00:00", "$date_minus_one 11:00:00", "11:00" ],
			[ "$date 00:00:00", "$date_minus_one 11:30:00", "11:30" ],
			[ "$date 00:00:00", "$date_minus_one 12:00:00", "12:00" ],

			// On a given day after the end so move to next day
			[ "$date 03:00:00", "$date 02:00:00", "02:00" ],

			// On a next day before the end so move to given day
			[ "$date_plus_one 01:00:00", "$date 02:00:00", "02:00" ],
		];

		return $array_of_tests;
	}

	/**
	 * @dataProvider build_shifted_start_of_day
	 */
	public function test_get_shifted_start_of_day( $input, $expected, $cutoff ){
		$start_of_day = Date_Utils::get_shifted_start_of_day( $input, $cutoff );

		$this->assertEquals( $expected, $start_of_day->format( Date_Utils::DBDATETIMEFORMAT ) );
	}

	/**
	 * @todo 2020 March 8 America/Chicago 2am day-light savings starts
	 * @todo 2020 November 1 America/Chicago 2am day-light savings ends
	 */
	public function build_shifted_end_of_day() {
		$date           = '2020-02-05';
		$date_plus_one  = '2020-02-06';
		$array_of_tests = [
			[ "$date 00:00:00", "$date 23:59:59", "00:00" ],
			[ "$date 00:00:00", "$date 00:29:59", "00:30" ],
			[ "$date 00:00:00", "$date 00:59:59", "01:00" ],
			[ "$date 00:00:00", "$date 01:29:59", "01:30" ],
			[ "$date 00:00:00", "$date 01:59:59", "02:00" ],
			[ "$date 00:00:00", "$date 02:29:59", "02:30" ],
			[ "$date 00:00:00", "$date 02:59:59", "03:00" ],
			[ "$date 00:00:00", "$date 03:29:59", "03:30" ],
			[ "$date 00:00:00", "$date 03:59:59", "04:00" ],
			[ "$date 00:00:00", "$date 04:29:59", "04:30" ],
			[ "$date 00:00:00", "$date 04:59:59", "05:00" ],
			[ "$date 00:00:00", "$date 05:29:59", "05:30" ],
			[ "$date 00:00:00", "$date 05:59:59", "06:00" ],
			[ "$date 00:00:00", "$date 06:29:59", "06:30" ],
			[ "$date 00:00:00", "$date 06:59:59", "07:00" ],
			[ "$date 00:00:00", "$date 07:29:59", "07:30" ],
			[ "$date 00:00:00", "$date 07:59:59", "08:00" ],
			[ "$date 00:00:00", "$date 08:29:59", "08:30" ],
			[ "$date 00:00:00", "$date 08:59:59", "09:00" ],
			[ "$date 00:00:00", "$date 09:29:59", "09:30" ],
			[ "$date 00:00:00", "$date 09:59:59", "10:00" ],
			[ "$date 00:00:00", "$date 10:29:59", "10:30" ],
			[ "$date 00:00:00", "$date 10:59:59", "11:00" ],
			[ "$date 00:00:00", "$date 11:29:59", "11:30" ],
			[ "$date 00:00:00", "$date 11:59:59", "12:00" ],

			// On a given day after the end so move to next day
			[ "$date 03:00:00", "$date_plus_one 01:59:59", "02:00" ],

			// On a next day before the end so move to given day
			[ "$date_plus_one 01:00:00", "$date_plus_one 01:59:59", "02:00" ],

			// On a next day before the end so move to given day
			[ "$date_plus_one 01:59:59", "$date_plus_one 01:59:59", "02:00" ],

			// On a next day before the end so move to given day
			[ "$date 02:00:00", "$date_plus_one 01:59:59", "02:00" ],
		];

		return $array_of_tests;
	}

	/**
	 * @dataProvider build_shifted_end_of_day
	 */
	public function test_shifted_end_start_of_day( $input, $expected, $cutoff ){
		$end_of_day = Date_Utils::get_shifted_end_of_day( $input, $cutoff );

		$this->assertEquals( $expected, $end_of_day->format( Date_Utils::DBDATETIMEFORMAT ) );
	}
}
