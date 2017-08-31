<?php

namespace Tribe\Collisions;

use Tribe__Collisions__Delta_Start_Detector as Detector;

class Delta_Start_DetectorTest extends \Codeception\TestCase\WPTestCase {

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
	 * it should return the original array if diff array is empty
	 */
	public function it_should_return_the_original_array_if_diff_array_is_empty() {
		$sut = $this->make_instance();

		$a      = [ [ 1, 2 ] ];
		$diffed = $sut->diff( $a, [] );

		$this->assertEquals( $a, $diffed );
	}

	/**
	 * @test
	 * it should return empty array when diffing array with itself
	 */
	public function it_should_return_empty_array_when_diffing_array_with_itself() {
		$sut = $this->make_instance();

		$a      = [ [ 1, 2 ], [ 3, 4 ] ];
		$diffed = $sut->diff( $a, $a );

		$this->assertEquals( [], $diffed );
	}

	/**
	 * @test
	 * it should detect collisions no matter the order of the segments
	 */
	public function it_should_detect_collisions_no_matter_the_order_of_the_segments() {
		$sut = $this->make_instance();

		$a      = [ [ 1, 2 ], [ 3, 4 ], [ 5, 6 ] ];
		$b      = [ [ 5, 6 ], [ 1, 2 ], [ 3, 4 ] ];
		$diffed = $sut->diff( $a, $b );

		$this->assertEquals( [], $diffed );
	}

	public function different_segments() {
		return [
			[ [ [ 1, 2 ] ], [ [ 0, 0 ] ], 2, [] ],
			[ [ [ 1, 2 ], [ 3, 4 ] ], [ [ 0, 0 ] ], 2, [ [ 3, 4 ] ] ],
			[ [ [ - 1, 0 ], [ 1, 2 ], [ 3, 4 ] ], [ [ 0, 0 ] ], 2, [ [ 3, 4 ] ] ],
			[ [ [ - 2, - 1 ], [ - 1, 0 ], [ 1, 2 ], [ 3, 4 ] ], [ [ 0, 0 ] ], 2, [ [ 3, 4 ] ] ],
			[
				[ [ - 3, - 2 ], [ - 2, - 1 ], [ - 1, 0 ], [ 1, 2 ], [ 3, 4 ] ],
				[ [ 0, 0 ] ],
				2,
				[ [ - 3, - 2 ], [ 3, 4 ] ]
			],
			[ [ [ 1, 2 ] ], [ [ 0, 0 ] ], 1, [] ],
			[ [ [ 1, 2 ], [ 3, 4 ] ], [ [ 0, 0 ] ], 1, [ [ 3, 4 ] ] ],
			[ [ [ - 1, 0 ], [ 1, 2 ], [ 3, 4 ] ], [ [ 0, 0 ] ], 1, [ [ 3, 4 ] ] ],
			[ [ [ - 2, - 1 ], [ - 1, 0 ], [ 1, 2 ], [ 3, 4 ] ], [ [ 0, 0 ] ], 1, [ [ - 2, - 1 ], [ 3, 4 ] ] ],
			[
				[ [ - 3, - 2 ], [ - 2, - 1 ], [ - 1, 0 ], [ 1, 2 ], [ 3, 4 ] ],
				[ [ 0, 0 ] ],
				1,
				[ [ - 3, - 2 ], [ - 2, - 1 ], [ 3, 4 ] ]
			],
			// points
			[ [ [ 1, 1 ] ], [ [ 2, 3 ] ], 2, [] ],
			[ [ [ 1, 1 ] ], [ [ 2, 3 ] ], 2, [] ],
			[ [ [ - 1, - 1 ], [ 1, 1 ] ], [ [ 2, 3 ] ], 2, [ [ - 1, - 1 ] ] ],
			[ [ [ - 1, - 1 ], [ 1, 1 ], [ 5, 5 ], [ 6, 6 ] ], [ [ 2, 3 ] ], 2, [ [ - 1, - 1 ], [ 6, 6 ] ] ],
			[ [ [ 1, 1 ] ], [ [ 2, 3 ] ], 1, [] ],
			[ [ [ 1, 1 ] ], [ [ 2, 3 ] ], 1, [] ],
			[ [ [ - 1, - 1 ], [ 1, 1 ] ], [ [ 2, 3 ] ], 1, [ [ - 1, - 1 ] ] ],
			[ [ [ - 1, - 1 ], [ 1, 1 ], [ 5, 5 ], [ 6, 6 ] ], [ [ 2, 3 ] ], 1, [ [ - 1, - 1 ], [ 5, 5 ], [ 6, 6 ] ] ],
		];
	}

	/**
	 * @test
	 * it should detect collisions in different segments
	 * @dataProvider different_segments
	 */
	public function it_should_detect_collisions_in_different_segments( $a, $b, $delta, $expected ) {
		$sut = $this->make_instance( $delta );

		$diffed = $sut->diff( $a, $b );

		$this->assertEquals( $expected, $diffed );
	}

	/**
	 * @return Detector
	 */
	private function make_instance( $delta = 1 ) {
		return new Detector( $delta );
	}

	public function intersect_segments() {
		return [
			[ [], [], 2, [] ],
			[ [ [ 1, 2 ] ], [], 2, [] ],
			[ [ [ 1, 2 ] ], [ [ 1, 2, ] ], 2, [ [ 1, 2 ] ] ],
			[ [ [ 1, 2 ] ], [ [ 1, 4, ] ], 2, [ [ 1, 2 ] ] ],
			[ [ [ 1, 2 ], [ 3, 4 ] ], [ [ 1, 4, ] ], 2, [ [ 1, 2 ], [ 3, 4 ] ] ],
			[ [ [ 1, 2 ], [ 3, 4 ], [ 6, 7 ] ], [ [ 1, 4, ] ], 2, [ [ 1, 2 ], [ 3, 4 ], [ 6, 7 ] ] ],
			[ [ [ 1, 2 ], [ 3, 4 ], [ 6, 7 ] ], [ [ 1, 4, ] ], 1, [ [ 1, 2 ], [ 3, 4 ] ] ],
		];
	}

	/**
	 * @test
	 * it should allow intersecting segments
	 * @dataProvider intersect_segments
	 */
	public function it_should_allow_intersecting_segments( array $a, array $b, $delta, array $expected ) {
		$sut = $this->make_instance( $delta );

		$intersected = $sut->intersect( $a, $b );
		$touched     = $sut->touch( $a, $b );

		$this->assertEquals( $expected, $intersected );
		$this->assertEquals( $expected, $touched );
	}

	/**
	 * @test
	 * it should allow intersecting with multiple segments
	 */
	public function it_should_allow_intersecting_with_multiple_segments() {
		$sut = $this->make_instance( 2 );

		$a = [ [ 1, 2 ], [ 3, 4 ], [ 5, 6 ] ];
		$b = [ [ 1, 2 ] ];
		$c = [ [ 0, 1 ] ];
		$d = [ [ 0, 0 ] ];

		$this->assertEquals( [ [ 1, 2 ], [ 3, 4 ] ], $sut->intersect( $a, $b ) );
		$this->assertEquals( [ [ 1, 2 ], [ 3, 4 ] ], $sut->intersect( $a, $b, $c ) );
		$this->assertEquals( [ [ 1, 2 ] ], $sut->intersect( $a, $b, $c, $d ) );
	}

	/**
	 * @test
	 * it should allow reporting when intersecting
	 */
	public function it_should_allow_reporting_when_intersecting() {
		$a                    = [ [ 1, 2 ], [ 3, 4 ], [ 5, 6 ] ];
		$b                    = [ [ 1, 2 ] ];
		$expected_intersected = [ [ 1, 2 ], [ 3, 4 ] ];
		$expected_matches     = [ [ 1, 2 ], [ 1, 2 ] ];

		$sut = $this->make_instance( 2 );


		$intersected = $sut->report_intersect( $a, $b );
		$this->assertEquals( [ $expected_intersected, $expected_matches ], $intersected );
	}

	/**
	 * @test
	 * it should allow reporting when touching
	 */
	public function it_should_allow_reporting_when_touching() {
		$a                = [ [ - 3, - 2 ], [ - 1, 0 ], [ 0, 1 ], [ 1, 2 ], [ 3, 4 ], [ 5, 6 ] ];
		$b                = [ [ 1, 2 ] ]; // -1 to 4
		$expected_touched = [ [ - 1, 0 ], [ 0, 1 ], [ 1, 2 ], [ 3, 4 ] ];
		$expected_matches = [ [ 1, 2 ], [ 1, 2 ], [ 1, 2 ], [ 1, 2 ] ];

		$sut = $this->make_instance( 2 );


		$intersected = $sut->report_touch( $a, $b );
		$this->assertEquals( [ $expected_touched, $expected_matches ], $intersected );
	}

	/**
	 * @test
	 * it should allow reporting when intersecting with multiple Bs
	 */
	public function it_should_allow_reporting_when_intersecting_with_multiple_bs() {
		$a                    = [ [ 1, 2 ], [ 3, 4 ], [ 5, 6 ] ];
		$b                    = [ [ 1, 2 ] ]; // -1 to 4
		$c                    = [ [ 2, 3 ] ]; // 0 to 5
		$d                    = [ [ - 1, 0 ] ]; // -3 to 2
		$expected_intersected = [ [ 1, 2 ] ];
		$expected_matches     = [ [ 1, 2 ] ];

		$sut = $this->make_instance( 2 );

		$intersected = $sut->report_intersect( $a, $b, $c, $d );
		$this->assertEquals( [ $expected_intersected, $expected_matches ], $intersected );
	}

	/**
	 * @test
	 * it should allow reporting when touching with multiple Bs
	 */
	public function it_should_allow_reporting_when_touching_with_multiple_bs() {
		$a                = [ [ - 3, - 2 ], [ - 1, 0 ], [ 0, 1 ], [ 1, 2 ], [ 3, 4 ], [ 5, 6 ] ];
		$b                = [ [ 1, 2 ] ]; // -1 to 4
		$c                = [ [ 2, 3 ] ];
		$expected_touched = [ [ - 1, 0 ], [ 0, 1 ], [ 1, 2 ], [ 3, 4 ], [ 5, 6 ] ];
		$expected_matches = [ [ 1, 2 ], [ 1, 2 ], [ 1, 2 ], [ 1, 2 ], [ 2, 3 ] ];

		$sut = $this->make_instance( 2 );

		$intersected = $sut->report_touch( $a, $b, $c );
		$this->assertEquals( [ $expected_touched, $expected_matches ], $intersected );
	}
}