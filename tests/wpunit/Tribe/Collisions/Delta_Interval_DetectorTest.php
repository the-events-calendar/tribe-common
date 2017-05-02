<?php

namespace Tribe\Collisions;

use Tribe__Collisions__Delta_Interval_Detector as Detector;

class Delta_Interval_DetectorTest extends \Codeception\TestCase\WPTestCase {

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

		$a = [ [ 1, 2 ] ];
		$diffed = $sut->diff( $a, [] );

		$this->assertEquals( $a, $diffed );
	}

	/**
	 * @test
	 * it should return empty array when diffing array with itself
	 */
	public function it_should_return_empty_array_when_diffing_array_with_itself() {
		$sut = $this->make_instance();

		$a = [ [ 1, 2 ], [ 3, 4 ] ];
		$diffed = $sut->diff( $a, $a );

		$this->assertEquals( [], $diffed );
	}

	/**
	 * @test
	 * it should detect collisions no matter the order of the segments
	 */
	public function it_should_detect_collisions_no_matter_the_order_of_the_segments() {
		$sut = $this->make_instance();

		$a = [ [ 1, 2 ], [ 3, 4 ], [ 5, 6 ] ];
		$b = [ [ 5, 6 ], [ 1, 2 ], [ 3, 4 ] ];
		$diffed = $sut->diff( $a, $b );

		$this->assertEquals( [], $diffed );
	}

	public function different_segments() {
		return [
			[ [ [ 1, 2 ], [ 3, 4 ], [ 5, 6 ], [ 6, 7 ] ], [ [ 3, 4 ] ], 2, [ [ 6, 7 ] ] ],
			[ [ [ 1, 2 ], [ 3, 4 ], [ 5, 6 ], [ 6, 7 ] ], [ [ 3, 4 ] ], 1, [ [ 1, 2 ], [ 5, 6 ], [ 6, 7 ] ] ],
			// points
			[ [ [ 1, 1 ], [ 3, 3 ], [ 5, 5 ], [ 6, 6 ], [ 7, 7 ] ], [ [ 3, 4 ] ], 2, [ [ 7, 7 ] ] ],
			[ [ [ 1, 1 ], [ 3, 3 ], [ 5, 5 ], [ 6, 6 ], [ 7, 7 ] ], [ [ 3, 4 ] ], 1, [ [ 1, 1 ], [ 6, 6 ], [ 7, 7 ] ] ],
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
}