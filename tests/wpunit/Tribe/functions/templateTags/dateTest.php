<?php

namespace Tribe\functions\templateTags;

class dateTest extends \Codeception\TestCase\WPTestCase {

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

	public function tribe_normalize_manual_utc_offset_inputs_and_outputs() {
		return [
			[ 'foo', 'foo' ],
			[ 'utc+2', 'UTC+2' ],
			[ 'utc-2', 'UTC-2' ],
			[ 'UTC+2', 'UTC+2' ],
			[ 'UTC-2', 'UTC-2' ],
			[ 'utc +2', 'UTC+2' ],
			[ 'utc -2', 'UTC-2' ],
			[ 'UTC +2', 'UTC+2' ],
			[ 'UTC -2', 'UTC-2' ],
			[ 'utc+2.5', 'UTC+2.5' ],
			[ 'UTC+2.5', 'UTC+2.5' ],
			[ 'utc-2.5', 'UTC-2.5' ],
			[ 'UTC-2.5', 'UTC-2.5' ],
			[ 'utc +2.5', 'UTC+2.5' ],
			[ 'UTC +2.5', 'UTC+2.5' ],
			[ 'utc -2.5', 'UTC-2.5' ],
			[ 'UTC -2.5', 'UTC-2.5' ],
			[ 'utc+2:30', 'UTC+2.5' ],
			[ 'UTC+2:30', 'UTC+2.5' ],
			[ 'utc-2:30', 'UTC-2.5' ],
			[ 'UTC-2:30', 'UTC-2.5' ],
			[ 'utc +2:30', 'UTC+2.5' ],
			[ 'UTC +2:30', 'UTC+2.5' ],
			[ 'utc -2:30', 'UTC-2.5' ],
			[ 'UTC -2:30', 'UTC-2.5' ],
			[ 'utc+2.30', 'UTC+2.5' ],
			[ 'UTC+2.30', 'UTC+2.5' ],
			[ 'utc-2.30', 'UTC-2.5' ],
			[ 'UTC-2.30', 'UTC-2.5' ],
			[ 'utc +2.30', 'UTC+2.5' ],
			[ 'UTC +2.30', 'UTC+2.5' ],
			[ 'utc -2.30', 'UTC-2.5' ],
			[ 'UTC -2.30', 'UTC-2.5' ],
			[ 'utc+2,5', 'UTC+2.5' ],
			[ 'UTC+2,5', 'UTC+2.5' ],
			[ 'utc-2,5', 'UTC-2.5' ],
			[ 'UTC-2,5', 'UTC-2.5' ],
			[ 'utc +2,5', 'UTC+2.5' ],
			[ 'UTC +2,5', 'UTC+2.5' ],
			[ 'utc -2,5', 'UTC-2.5' ],
			[ 'UTC -2,5', 'UTC-2.5' ],
			[ 'utc+2:45', 'UTC+2.75' ],
			[ 'UTC+2:45', 'UTC+2.75' ],
			[ 'utc-2:45', 'UTC-2.75' ],
			[ 'UTC-2:45', 'UTC-2.75' ],
			[ 'utc +2:45', 'UTC+2.75' ],
			[ 'UTC +2:45', 'UTC+2.75' ],
			[ 'utc -2:45', 'UTC-2.75' ],
			[ 'UTC -2:45', 'UTC-2.75' ],
			[ 'utc+2.75', 'UTC+2.75' ],
			[ 'UTC+2.75', 'UTC+2.75' ],
			[ 'utc-2.75', 'UTC-2.75' ],
			[ 'UTC-2.75', 'UTC-2.75' ],
			[ 'utc +2.75', 'UTC+2.75' ],
			[ 'UTC +2.75', 'UTC+2.75' ],
			[ 'utc -2.75', 'UTC-2.75' ],
			[ 'UTC -2.75', 'UTC-2.75' ],
		];
	}

	/**
	 * tribe_normalize_manual_utc_offset
	 *
	 * @dataProvider  tribe_normalize_manual_utc_offset_inputs_and_outputs
	 */
	public function test_tribe_normalize_manual_utc_offset( $input, $expected ) {
		$this->assertEquals( $expected, tribe_normalize_manual_utc_offset( $input ) );
	}

	public function tribe_end_of_day_inputs() {
		return [
			[ 'today', ( new \DateTime( 'today' ) )->format( 'Y-m-d' ) . ' 23:59:59' ],
			[ 'today 9am', ( new \DateTime( 'today' ) )->format( 'Y-m-d' ) . ' 23:59:59' ],
			[ 'today midnight', ( new \DateTime( 'today' ) )->format( 'Y-m-d' ) . ' 23:59:59' ],
			[ 'tomorrow', ( new \DateTime( 'tomorrow' ) )->format( 'Y-m-d' ) . ' 23:59:59' ],
			[ 'tomorrow 9am', ( new \DateTime( 'tomorrow' ) )->format( 'Y-m-d' ) . ' 23:59:59' ],
			[ 'tomorrow midnight', ( new \DateTime( 'tomorrow' ) )->format( 'Y-m-d' ) . ' 23:59:59' ],
			[ '+1 week', ( new \DateTime( '+1 week' ) )->format( 'Y-m-d' ) . ' 23:59:59' ],
			[ '+1 week 9am', ( new \DateTime( '+1 week' ) )->format( 'Y-m-d' ) . ' 23:59:59' ],
			[ '+1 week midnight', ( new \DateTime( '+1 week' ) )->format( 'Y-m-d' ) . ' 23:59:59' ],
			[ ( new \DateTime( 'today' ) )->format( 'U' ), ( new \DateTime( 'today' ) )->format( 'Y-m-d' ) . ' 23:59:59' ],
			[ ( new \DateTime( 'today 9am' ) )->format( 'U' ), ( new \DateTime( 'today' ) )->format( 'Y-m-d' ) . ' 23:59:59' ],
			[ ( new \DateTime( 'today midnight' ) )->format( 'U' ), ( new \DateTime( 'today' ) )->format( 'Y-m-d' ) . ' 23:59:59' ],
			[ ( new \DateTime( 'tomorrow' ) )->format( 'U' ), ( new \DateTime( 'tomorrow' ) )->format( 'Y-m-d' ) . ' 23:59:59' ],
			[ ( new \DateTime( 'tomorrow 9am' ) )->format( 'U' ), ( new \DateTime( 'tomorrow' ) )->format( 'Y-m-d' ) . ' 23:59:59' ],
			[ ( new \DateTime( 'tomorrow midnight' ) )->format( 'U' ), ( new \DateTime( 'tomorrow' ) )->format( 'Y-m-d' ) . ' 23:59:59' ],
			[ ( new \DateTime( '+1 week' ) )->format( 'U' ), ( new \DateTime( '+1 week' ) )->format( 'Y-m-d' ) . ' 23:59:59' ],
			[ ( new \DateTime( '+1 week 9am' ) )->format( 'U' ), ( new \DateTime( '+1 week' ) )->format( 'Y-m-d' ) . ' 23:59:59' ],
			[ ( new \DateTime( '+1 week midnight' ) )->format( 'U' ), ( new \DateTime( '+1 week' ) )->format( 'Y-m-d' ) . ' 23:59:59' ],
		];
	}

	/**
	 * Test tribe_end_of_day
	 *
	 * @test
	 * @dataProvider tribe_end_of_day_inputs
	 */
	public function test_tribe_end_of_day( $input, $expected ) {
		$this->assertEquals( $expected, tribe_end_of_day( $input ) );
	}
}