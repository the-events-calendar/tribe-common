<?php

namespace Tribe\Collisions;

use Tribe__Collisions__Median_Proximity_Start_Detector as Detector;

class Median_Proximity_Start_DetectorTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->make_instance();

		$this->assertInstanceOf( Detector::class, $sut );
	}

	/**
	 * @test
	 * it should return the original array if diffing with empty array
	 */
	public function it_should_return_the_original_array_if_diffing_with_empty_array() {
		$a = [ [ 1, 2 ], [ 3, 4 ] ];
		$b = [];

		$detector = new Detector();
		$diffed = $detector->diff( $a, $b );

		$this->assertEquals( $a, $diffed );
	}

	/**
	 * @test
	 * it should return empty array when diffing array with itself
	 */
	public function it_should_return_empty_array_when_diffing_array_with_itself() {
		$a = [ [ 1, 2 ], [ 3, 4 ] ];

		$detector = new Detector();
		$diffed = $detector->diff( $a, $a );

		$this->assertEquals( [], $diffed );
	}

	/**
	 * @test
	 * it should return empty array when diffing empty array
	 */
	public function it_should_return_empty_array_when_diffing_empty_array() {
		$b = [ [ 1, 2 ], [ 3, 4 ] ];

		$detector = new Detector();
		$this->assertEmpty( $detector->diff( [], [] ) );
		$this->assertEmpty( $detector->diff( [], $b ) );
	}

	public function to_diff() {
		return [
//			[ [], [], [] ],
//			[ [ [ 1, 2 ] ], [], [ [ 1, 2 ] ] ],
//			[ [ [ 1, 2 ] ], [ [ 3400, 5000 ] ], [] ],
//			[ [ [ 1, 2 ], [ 3, 4 ] ], [ [ 3400, 5000 ] ], [ [ 1, 2 ] ] ],
//			[ [ [ 1, 2 ], [ 3, 4 ] ], [ [ - 2, 0 ] ], [ [ 3, 4 ] ] ],
//			[ [ [ 1, 2 ], [ 3, 4 ], [ 5, 6 ] ], [ [ 0, 1 ], [ 7, 9 ] ], [ [ 3, 4 ], [ 5, 6 ] ] ],
//			[ [ [ 1, 2 ], [ 3, 4 ], [ 5, 6 ] ], [ [ 0, 1 ], [ 6, 9 ] ], [ [ 3, 4 ] ] ],
//			[ [ [ 1, 2 ], [ 3, 4 ], [ 5, 6 ] ], [ [ 0, 1 ], [ 6, 9 ], [ 4, 3 ] ], [] ],
			[
				[ [ 1, 2 ], [ 1, 3 ], [ 1, 4 ], [ 1, 5 ], [ 3, 4 ], [ 5, 6 ] ],
				[ [ 0, 1 ], [ 6, 9 ], [ 4, 3 ] ],
				[ [ 1, 3 ], [ 1, 4 ], [ 1, 5 ] ]
			],
			[
				[ [ 1, 2 ], [ 2, 3 ], [ 3, 4 ], [ 4, 5 ], [ 5, 6 ], [ 7, 8 ] ],
				[ [ 0, 1 ], [ 3, 4 ], [ 4, 5 ], [ 5, 6 ] ], // median is 1 from (0,0,0,1) as 0s are excluded
				[ [ 2, 3 ], [ 7, 8 ] ]
			],
		];
	}

	/**
	 * @test
	 * it should handle diffing two arrays
	 * @dataProvider to_diff
	 */
	public function it_should_handle_diffing_two_arrays( $a, $b, $expected ) {
		$detector = $this->make_instance();

		$diffed = $detector->diff( $a, $b );

		$this->assertEquals( $expected, $diffed );
	}

	public function multiple_diffing_arrays() {
		return [
			// $a, $b, $c, $expected
			[ [], [], [], [] ],
			[ [ [ 1, 2 ] ], [], [ [ 3, 4 ] ], [] ],
			[ [ [ 1, 2 ], [ 3, 4 ] ], [ [ 3400, 5000 ] ], [ [ - 100, 200 ] ], [ [ 3, 4 ] ] ],
			[ [ [ 10, 20 ], [ 30, 40 ], [ 100, 110 ] ], [ [ 50, 60 ] ], [ [ 10, 20 ] ], [ [ 100, 110 ] ] ],
			[
				[ [ 10, 20 ], [ 30, 40 ], [ 100, 110 ] ],
				[ [ 20, 25 ] ],
				[ [ 40, 50 ], [ 75, 80 ] ],
				[ [ 100, 110 ] ] //median is 10 out of (10, 10, 25)
			],
		];
	}

	/**
	 * @test
	 * it should handle diffing more arrays
	 * @dataProvider multiple_diffing_arrays
	 */
	public function it_should_handle_diffing_more_arrays( $a, $b, $c, $expected ) {
		$detector = $this->make_instance();

		$diffed = $detector->diff( $a, $b, $c );

		$this->assertEquals( $expected, $diffed );
	}

	/**
	 * @test
	 * it should return empty array when intersecting with empty array
	 */
	public function it_should_return_empty_array_when_intersecting_with_empty_array() {
		$a = [ [ 1, 2 ], [ 3, 4 ] ];

		$detector = new Detector();
		$intersected = $detector->intersect( $a, [] );

		$this->assertEmpty( $intersected );
	}

	/**
	 * @test
	 * it should return empty array when intersecting empty array
	 */
	public function it_should_return_empty_array_when_intersecting_empty_array() {
		$b = [ [ 1, 2 ], [ 3, 4 ] ];

		$detector = new Detector();
		$this->assertEmpty( $detector->intersect( [], [] ) );
		$this->assertEmpty( $detector->intersect( [], $b ) );
		$this->assertEmpty( $detector->report_intersect( [], [] ) );
		$this->assertEmpty( $detector->report_intersect( [], $b ) );
	}

	/**
	 * @test
	 * it should return empty array when touching empty array
	 */
	public function it_should_return_empty_array_when_touching_empty_array() {
		$b = [ [ 1, 2 ], [ 3, 4 ] ];

		$detector = new Detector();
		$this->assertEmpty( $detector->touch( [], [] ) );
		$this->assertEmpty( $detector->touch( [], $b ) );
		$this->assertEmpty( $detector->report_touch( [], [] ) );
		$this->assertEmpty( $detector->report_touch( [], $b ) );
	}

	/**
	 * @test
	 * it should return original array when intersecting with itself
	 */
	public function it_should_return_original_array_when_intersecting_with_itself() {
		$a = [ [ 1, 2 ], [ 3, 4 ], [ 5, 6 ] ];

		$detector = new Detector();
		$intersected = $detector->intersect( $a, $a );

		$this->assertEquals( $a, $intersected );
	}

	/**
	 * @test
	 * it should report the original array when reporting intersect with itself
	 */
	public function it_should_report_the_original_array_when_reporting_intersect_with_itself() {
		$a = $expected_surviving = $expected_matching = [ [ 1, 2 ], [ 3, 4 ], [ 5, 6 ] ];

		$detector = new Detector();
		$intersected = $detector->report_intersect( $a, $a );

		$this->assertCount( 2, $intersected );
		$surviving = reset( $intersected );
		$matching = end( $intersected );
		$count = count( $a );
		$this->assertCount( $count, $surviving );
		$this->assertCount( $count, $matching );
		$this->assertEquals( $surviving, $expected_surviving );
		$this->assertEquals( $matching, $expected_matching );
	}

	public function a_and_b_report_intersect() {
		// $a, $b, $expected_survivors, $expected_matching
		return [
			[ [ [ 1, 2 ] ], [ [ 4, 5 ] ], [ [ 1, 2 ] ], [ [ 4, 5 ] ] ],
			[ [ [ 1, 2 ], [ 3, 4 ] ], [ [ 4, 5 ] ], [ [ 3, 4 ] ], [ [ 4, 5 ] ] ],
			[
				[ [ 1, 2 ], [ 3, 4 ], [ 4, 5 ] ],
				[ [ 4, 5 ] ],
				[ [ 4, 5 ] ],
				[ [ 4, 5 ] ],
			],
			[
				[ [ 1, 2 ], [ 3, 4 ], [ 4, 5 ] ],
				[ [ 4, 5 ], [ 3, 4 ] ],
				[ [ 3, 4 ], [ 4, 5 ] ],
				[ [ 3, 4 ], [ 4, 5 ] ],
			],
			[
				[ [ 1, 2 ], [ 3, 4 ], [ 4, 5 ] ],
				[ [ 4, 5 ], [ 7, 8 ] ],
				[ [ 4, 5 ] ],
				[ [ 4, 5 ] ],
			],
			[
				[ [ 1, 2 ], [ 3, 4 ], [ 4, 5 ] ],
				[ [ - 2, 1 ], [ 7, 8 ] ],
				[ [ 1, 2 ], [ 4, 5 ] ],
				[ [ - 2, 1 ], [ 7, 8 ] ],
			],
			[
				[ [ 1, 2 ], [ 3, 4 ], [ 4, 5 ] ],
				[ [ - 3, 1 ], [ 7, 8 ] ],
				[ [ 4, 5 ] ],
				[ [ 7, 8 ] ],
			],
			[
				[ [ 1, 2 ], [ 3, 4 ], [ 4, 5 ], [ 5, 6 ] ],
				[ [ - 3, 1 ], [ 7, 8 ] ],
				[ [ 5, 6 ] ],
				[ [ 7, 8 ] ],
			],
			[
				[ [ 10, 15 ], [ 20, 25 ], [ 30, 35 ], [ 40, 45 ] ],
				[ [ 12, 14 ], [ 23, 25 ], [ 26, 28 ], [ 42, 44 ] ], // median is 2 out of (2,2,3,4)
				[ [ 10, 15 ], [ 40, 45 ] ],
				[ [ 12, 14 ], [ 42, 44 ] ],
			],
			[
				[ [ 10, 15 ], [ 20, 25 ], [ 30, 35 ], [ 40, 45 ] ],
				[ [ 12, 14 ], [ 23, 25 ], [ 26, 28 ], [ 43, 44 ], ], // median is 3 out of (2,3,3,4)
				[ [ 10, 15 ], [ 20, 25 ], [ 40, 45 ] ],
				[ [ 12, 14 ], [ 23, 25 ], [ 43, 44 ] ],
			],
		];
	}

	/**
	 * @test
	 * it should report intersections correctly
	 * @dataProvider a_and_b_report_intersect
	 */
	public function it_should_report_intersections_correctly( $a, $b, $expected_survivors, $expected_matching ) {
		$detector = new Detector();
		$intersected = $detector->report_intersect( $a, $b );

		$this->assertCount( 2, $intersected );
		$surviving = reset( $intersected );
		$matching = end( $intersected );
		$this->assertEquals( $expected_survivors, $surviving );
		$this->assertEquals( $expected_matching, $matching );
	}

	/**
	 * @test
	 * it should report touches correctly
	 * @dataProvider a_and_b_report_intersect
	 */
	public function it_should_report_touches_correctly( $a, $b, $expected_survivors, $expected_matching ) {
		$detector = new Detector();
		$touched = $detector->report_touch( $a, $b );

		$this->assertCount( 2, $touched );
		$surviving = reset( $touched );
		$matching = end( $touched );
		$this->assertEquals( $expected_survivors, $surviving );
		$this->assertEquals( $expected_matching, $matching );
	}

	/**
	 * @return Detector
	 */
	private function make_instance() {
		return new Detector();
	}
}