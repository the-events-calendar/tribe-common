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
	 * @test
	 * @dataProvider localizes_date_timestamp_inputs
	 */
	public function test_localize_date_with_timestamps( $timestamp, $timezone_string ) {
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
	 * @test
	 */
	public function test_localize_date_sanity_check() {
		$format = 'Y-m-d H:i:s';

		update_option( 'timezone_string', 'UTC' );
		$utc_date = Timezones::localize_date( $format, strtotime( 'tomorrow' ) );

		update_option( 'timezone_string', 'America/New_York' );
		$offset_date = Timezones::localize_date( $format, strtotime( 'tomorrow' ) );

		$this->assertNotEquals( $utc_date, $offset_date );
		$this->assertLessThan( $utc_date, $offset_date );
	}
}