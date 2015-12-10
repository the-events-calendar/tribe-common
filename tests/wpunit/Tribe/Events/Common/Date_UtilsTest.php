<?php
namespace Tribe\Events\Common;

use \Tribe__Date_Utils as Date_Utils;

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
		}, [
			[ 'day', 2, 3, 2012, 1 ], [ 2, 'week', 3, 2012, 1 ], [ 2, 2, 'month', 2012, 1 ], [ 2, 2, 3, 'year', 1 ],
			[ 2, 2, 3, 2012, 'direction' ], [ 2, 2, 3, 2012, 23 ], [ 2, 2, 3, 2012, - 2 ],
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
		$this->assertEquals( $expected, call_user_func_array( [
			'Tribe__Date_Utils', 'get_weekday_timestamp'
		], $args ) );
	}

	/**
	 * get_weekday_timestamp returns right timestamp etc -9 in natural direction
	 *
	 * @dataProvider etc_natural_direction_expected_timestamps
	 */
	public function test_get_weekday_timestamp_returns_right_timestamp_etc_minus_9_in_natural_direction( $expected, $args ) {
		date_default_timezone_set( 'Etc/GMT-9' );
		$nine_hours = 60 * 60 * 9;
		$this->assertEquals( $expected - $nine_hours, call_user_func_array( [
			'Tribe__Date_Utils', 'get_weekday_timestamp'
		], $args ) );
	}

	/**
	 * get_weekday_timestamp returns right timestamp etc +9 in natural direction
	 *
	 * @dataProvider etc_natural_direction_expected_timestamps
	 */
	public function test_get_weekday_timestamp_returns_right_timestamp_etc_plus_9_in_natural_direction( $expected, $args ) {
		date_default_timezone_set( 'Etc/GMT+9' );
		$nine_hours = 60 * 60 * 9;
		$this->assertEquals( $expected + $nine_hours, call_user_func_array( [
			'Tribe__Date_Utils', 'get_weekday_timestamp'
		], $args ) );
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
		$this->assertEquals( $expected, call_user_func_array( [
			'Tribe__Date_Utils', 'get_weekday_timestamp'
		], $args ) );
	}

	/**
	 * get_weekday_timestamp returns right timestamp etc -9 in reverse direction
	 *
	 * @dataProvider etc_reverse_direction_expected_timestamps
	 */
	public function test_get_weekday_timestamp_returns_right_timestamp_etc_minus_9_in_reverse_direction( $expected, $args ) {
		date_default_timezone_set( 'Etc/GMT-9' );
		$nine_hours = 60 * 60 * 9;
		$this->assertEquals( $expected - $nine_hours, call_user_func_array( [
			'Tribe__Date_Utils', 'get_weekday_timestamp'
		], $args ) );
	}

	/**
	 * get_weekday_timestamp returns right timestamp etc +9 in reverse direction
	 *
	 * @dataProvider etc_reverse_direction_expected_timestamps
	 */
	public function test_get_weekday_timestamp_returns_right_timestamp_etc_plus_9_in_reverse_direction( $expected, $args ) {
		date_default_timezone_set( 'Etc/GMT+9' );
		$nine_hours = 60 * 60 * 9;
		$this->assertEquals( $expected + $nine_hours, call_user_func_array( [
			'Tribe__Date_Utils', 'get_weekday_timestamp'
		], $args ) );
	}
}