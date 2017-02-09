<?php

namespace Tribe\Collisions;

use Tribe__Collisions__Average_Proximity_Start_Detector as Detector;

class Average_Proximity_Start_DetectorTest extends \Codeception\TestCase\WPTestCase {
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

	public function array_comparisons() {
		return [
			[ [], [ [ 7, 8 ] ] ],
			[ [ [ 1, 2 ], [ 3, 4 ] ], [ [ 7, 8 ] ] ],
			[ [ [ 1, 2 ], [ 3, 4 ], [ 6, 7 ], [ 7, 8 ] ], [ [ 7, 8 ] ] ],
		];
	}

	public function to_diff() {
		return [
			[ [], [], [] ],
			[ [ [ 1, 2 ] ], [], [ [ 1, 2 ] ] ],
			[ [ [ 1, 2 ] ], [ [ 3400, 5000 ] ], [] ],
			[ [ [ 1, 2 ], [ 3, 4 ] ], [ [ 3400, 5000 ] ], [ [ 1, 2 ] ] ],
			[ [ [ 1, 2 ], [ 3, 4 ] ], [ [ - 2, 0 ] ], [ [ 3, 4 ] ] ],
			[ [ [ 1, 2 ], [ 3, 4 ], [ 5, 6 ] ], [ [ 0, 1 ], [ 7, 9 ] ], [ [ 3, 4 ], [ 5, 6 ] ] ],
			[ [ [ 1, 2 ], [ 3, 4 ], [ 5, 6 ] ], [ [ 0, 1 ], [ 6, 9 ] ], [ [ 3, 4 ] ] ],
			[ [ [ 1, 2 ], [ 3, 4 ], [ 5, 6 ] ], [ [ 0, 1 ], [ 6, 9 ], [ 4, 3 ] ], [] ],
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
			[ [ [ 10, 20 ], [ 30, 40 ], [ 100, 110 ] ], [ [ 50, 60 ] ], [ [ 10, 20 ] ], [ [ 30, 40 ], [ 100, 110 ] ] ],
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

	public function three_array_intersections() {
		// $a, $b, $c, $expected_survivors, $expected_matching
		return [
			[ [ [ 1, 2 ] ], [ [ 4, 5 ] ], [ [ 5, 6 ] ], [ [ 1, 2 ] ], [ [ 4, 5 ] ] ],
			[ [ [ 1, 2 ], [ 3, 4 ] ], [ [ 4, 5 ] ], [ [ 3, 4 ] ], [ [ 3, 4 ] ], [ [ 3, 4 ] ] ],
			[ [ [ 1, 2 ], [ 3, 4 ], [ 3, 6 ] ], [ [ 4, 5 ] ], [ [ 3, 4 ] ], [ [ 3, 4 ] ], [ [ 3, 4 ] ] ],
		];
	}

	/**
	 * @test
	 * it should handle multiple array intersection
	 * @dataProvider three_array_intersections
	 */
	public function it_should_handle_multiple_array_intersection( $a, $b, $c, $expected_survivors, $expected_matching ) {
		$detector = new Detector();
		$intersected = $detector->report_intersect( $a, $b, $c );

		$this->assertCount( 2, $intersected );
		$surviving = reset( $intersected );
		$matching = end( $intersected );
		$this->assertEquals( $expected_survivors, $surviving );
		$this->assertEquals( $expected_matching, $matching );
	}

	/**
	 * @test
	 * it should handle multiple array touch
	 * @dataProvider three_array_intersections
	 */
	public function it_should_handle_multiple_array_touch( $a, $b, $c, $expected_survivors, $expected_matching ) {
		$detector = new Detector();
		$touched = $detector->report_touch( $a, $b, $c );

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