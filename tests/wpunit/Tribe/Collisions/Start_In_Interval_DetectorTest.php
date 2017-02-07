<?php

namespace Tribe\Collisions;

use Tribe__Collisions__Start_In_Interval_Detector as Detector;

class Start_In_Interval_DetectorTest extends \Codeception\TestCase\WPTestCase {

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
			[ [ [ 1, 2 ], [ 2, 3 ], [ 3, 4 ], [ 4, 5 ] ], [ [ 0, 2 ], [ 4, 6 ] ], [ [ 3, 4 ] ] ],
			// points
			[ [ [ 1, 1 ], [ 2, 2 ], [ 3, 3 ], [ 4, 4 ] ], [ [ 0, 2 ], [ 4, 6 ] ], [ [ 3, 3 ] ] ],
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
		$b        = [ [ 3, 6 ] ];
		$expected = [ [ 1, 2 ] ];

		$diffed = $sut->diff( $a, $b );

		$this->assertEquals( $expected, $diffed );
	}

	/**
	 * @test
	 * it should handle points collision
	 */
	public function it_should_handle_points_collision() {
		$sut = $this->make_instance();

		$a        = [ [ 1, 1 ], [ 2, 2 ], [ 3, 3 ] ];
		$b        = [ [ 1, 2 ], [ 4, 5 ] ];
		$expected = [ [ 3, 3 ] ];

		$diffed = $sut->diff( $a, $b );

		$this->assertEquals( $expected, $diffed );
	}

	/**
	 * @test
	 * it should handle float values
	 */
	public function it_should_handle_float_values() {
		$sut = $this->make_instance();

		$a        = [ [ .1, .5 ], [ 1, 3.5 ], [ 2, 4 ], [ 3, 5 ] ];
		$b        = [ [ - 1, 2 ], [ 5, 7 ] ];
		$expected = [ [ 3, 5 ] ];

		$diffed = $sut->diff( $a, $b );

		$this->assertEquals( $expected, $diffed );
	}

	/**
	 * @return Detector
	 */
	private function make_instance() {
		return new Detector();
	}

	/**
	 * @test
	 * it should allow generating segments from points
	 */
	public function it_should_allow_generating_segments_from_points() {
		$sut = $this->make_instance();

		$points    = [ 1, 4, - 3, 0, - 23, 23 ];
		$length    = 10;
		$generated = $sut->points_to_segments( $points, $length );

		$this->assertCount( count( $points ), $generated );
		foreach ( $generated as $segment ) {
			$this->assertEquals( $length, $segment[1] - $segment[0] );
		}
	}

	/**
	 * @test
	 * it should allow generating 0 length segments from points
	 */
	public function it_should_allow_generating_0_length_segments_from_points() {
		$sut = $this->make_instance();

		$points    = [ 1, 4, - 3, 0, - 23, 23 ];
		$generated = $sut->points_to_segments( $points, 0 );

		$this->assertCount( count( $points ), $generated );
		foreach ( $generated as $segment ) {
			$this->assertEquals( $segment[1], $segment[0] );
		}
	}
}