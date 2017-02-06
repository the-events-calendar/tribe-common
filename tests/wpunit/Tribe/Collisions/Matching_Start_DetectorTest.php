<?php

namespace Tribe\Collisions;

use Tribe__Collisions__Matching_Start_Detector as Detector;

class Matching_Start_DetectorTest extends \Codeception\TestCase\WPTestCase {

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
			[ [ [ 1, 2 ], [ 1, 4 ], [ 2, 3 ], [ 4, 5 ] ], [ [ 1, 2 ] ], [ [ 2, 3 ], [ 4, 5 ] ] ],
			[ [ [ 1, 2 ], [ 2, 3 ], [ 4, 6 ], [ 4, 5 ] ], [ [ 1, 2 ], [ 4, 5 ] ], [ [ 2, 3 ] ] ],
			[ [ [ 1, 2 ], [ 2, 3 ], [ 4, 5 ], [ 4, 6 ] ], [ [ 1, 2 ], [ 4, 5 ], [ 4, 6 ] ], [ [ 2, 3 ] ] ],
			[ [ [ 1, 2 ], [ 3, 4 ], [ 5, 6 ], [ 5, 7 ] ], [ [ 1, 2 ] ], [ [ 3, 4 ], [ 5, 6 ], [ 5, 7 ] ] ],
			[ [ [ - 1, 0 ], [ - 2, 4 ], [ 3, 6 ], [ - 1, 1 ], [ - 1, 0 ] ], [ [ - 1, 0 ] ], [ [ - 2, 4 ], [ 3, 6 ] ] ],
			[
				[ [ - 1, 0 ], [ - 2, 4 ], [ 3, 6 ], [ - 1, 1 ], [ - 1, 0 ] ],
				[ [ 3, 6 ] ],
				[ [ - 2, 4 ], [ - 1, 0 ], [ - 1, 1 ] ]
			],
			// points
			[ [ [ 1, 1 ], [ 1, 4 ], [ 2, 2 ], [ 4, 4 ] ], [ [ 1, 1 ] ], [ [ 2, 2 ], [ 4, 4 ] ] ],
			[ [ [ 1, 1 ], [ 1, 4 ], [ 2, 2 ], [ 4, 4 ] ], [ [ 1, 1 ], [ 4, 4 ] ], [ [ 2, 2 ] ] ],
			[ [ [ 1, 1 ], [ 1, 9 ], [ 2, 2 ], [ 4, 4 ] ], [ [ 1, 1 ], [ 4, 4 ], [ 5, 5 ] ], [ [ 2, 2 ] ] ],
			[ [ [ 1, 1 ], [ 1, 9 ], [ 1, - 4 ], [ 3, 3 ], [ 5, 5 ] ], [ [ 1, 1 ] ], [ [ 3, 3 ], [ 5, 5 ] ] ],
			[
				[ [ - 1, - 1 ], [ - 2, - 2 ], [ 3, 3 ], [ - 1, 1 ], [ - 1, 0 ] ],
				[ [ - 1, - 1 ] ],
				[ [ - 2, - 2 ], [ 3, 3 ] ]
			],
			[
				[ [ - 1, - 1 ], [ - 2, - 2 ], [ 3, 3 ], [ - 1, 1 ], [ - 1, 0 ] ],
				[ [ 3, 3 ] ],
				[ [ - 2, - 2 ], [ - 1, - 1 ], [ - 1, 1 ], [ - 1, 0 ] ]
			],
		];
	}

	/**
	 * @test
	 * it should detect collisions in different segments
	 * @dataProvider different_segments
	 */
	public function it_should_detect_collisions_in_different_segments( $a, $b, $expected ) {
		$sut = $this->make_instance();

		$diffed = $sut->diff( $a, $b );

		$this->assertEquals( $expected, $diffed );
	}

	/**
	 * @test
	 * it should prune duplicates segments from result
	 */
	public function it_should_prune_duplicates_segments_from_result() {
		$sut = $this->make_instance();

		$a        = [ [ 1, 2 ], [ 1, 2 ], [ 1, 2 ], [ 3, 4 ], [ 4, 5 ] ];
		$b        = [ [ 4, 5 ] ];
		$expected = [ [ 1, 2 ], [ 3, 4 ] ];

		$diffed = $sut->diff( $a, $b );

		$this->assertEquals( $expected, $diffed );
	}

	/**
	 * @test
	 * it should handle points collision
	 */
	public function it_should_handle_points_collision() {
		$sut = $this->make_instance();

		$a        = [ [ 1, 1 ], [ 1, 4 ], [ 2, 2 ], [ 3, 3 ] ];
		$b        = [ [ 1, 1 ], [ 4, 5 ] ];
		$expected = [ [ 2, 2 ], [ 3, 3 ] ];

		$diffed = $sut->diff( $a, $b );

		$this->assertEquals( $expected, $diffed );
	}

	/**
	 * @test
	 * it should handle float values
	 */
	public function it_should_handle_float_values() {
		$sut = $this->make_instance();

		$a        = [ [ .1, .5 ], [ 1, 3.5 ], [ .5, 10 ], [ 2, 4 ], [ 3, 5 ] ];
		$b        = [ [ .1, .5 ], [ 2, 4 ], [ .5, 1 ] ];
		$expected = [ [ 1, 3.5 ], [ 3, 5 ] ];

		$diffed = $sut->diff( $a, $b );

		$this->assertEquals( $expected, $diffed );
	}

	/**
	 * @test
	 * it should allow diffing with multiple bs
	 */
	public function it_should_allow_diffing_with_multiple_bs() {
		$sut = $this->make_instance();

		$a = [ [ 1, 2 ], [ 4, 5 ], [ 7, 8 ], [ 9, 10 ], [ 11, 12 ] ];
		$b = [ [ 1, 3 ], [ 3, 5 ] ];
		$c = [ [ 4, 6 ], [ 6, 7 ] ];
		$d = [ [ 7, 8 ], [ 8, 9 ] ];

		$expected = [ [ 9, 10 ], [ 11, 12 ] ];

		$diffed = $sut->diff( $a, $b, $c, $d );

		$this->assertEquals( $expected, $diffed );
	}

	public function intersect_segments() {
		return [
			[ [], [], [] ],
			[ [ [ 1, 2 ] ], [], [] ],
			[ [ [ 1, 2 ] ], [ [ 1, 2, ] ], [ [ 1, 2 ] ] ],
			[ [ [ 1, 2 ] ], [ [ 1, 4, ] ], [ [ 1, 2 ] ] ],
			[ [ [ 1, 2 ], [ 3, 4 ] ], [ [ 1, 4, ] ], [ [ 1, 2 ] ] ],
			[ [ [ 1, 2 ], [ 3, 4 ] ], [ [ 1, 4, ], [ 3, 3 ] ], [ [ 1, 2 ], [ 3, 4 ] ] ],
		];
	}

	/**
	 * @test
	 * it should allow intersecting segments
	 * @dataProvider intersect_segments
	 */
	public function it_should_allow_intersecting_segments( array $a, array $b, array $expected ) {
		$sut = $this->make_instance();

		$intersected = $sut->intersect( $a, $b );

		$this->assertEquals( $expected, $intersected );
	}

	/**
	 * @return Detector
	 */
	private function make_instance() {
		return new Detector();
	}

}