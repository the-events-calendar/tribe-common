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

}