<?php

namespace Tribe\Utils;

use Tribe__Date_Utils as Dates;


class DateTest extends \Codeception\TestCase\WPTestCase {

	/**
	 */
	public function test_date_sort_with_array_of_strings() {
		$now          = '47448000';
		$start_date   = '47448001';
		$end_date     = '47448002';
		$sorted_array = [ $now, $start_date, $end_date ];
		$test_array   = [ $end_date, $now, $start_date ];

		$sorted = Dates::sort( $test_array );

		// Ensure we're *comparing* apples to apples here, instead of datetime objects to strings.
		$sorted = array_map(
			function( $date ) {
				return $date->format( 'U' );
			},
			$sorted
		);

		$this->assertEquals( $sorted_array, $sorted, 'Date strings should be sorted in the appropriate ascending order.' );
	}

	/**
	 */
	public function test_date_sort_with_array_of_ints() {
		$now          = 47448000;
		$start_date   = 47448001;
		$end_date     = 47448002;
		$sorted_array = [ $now, $start_date, $end_date ];
		$test_array   = [ $end_date, $now, $start_date ];

		$sorted = Dates::sort( $test_array );

		// Ensure we're *comparing* apples to apples here, instead of datetime objects to strings.
		$sorted = array_map(
			function( $date ) {
				return $date->format( 'U' );
			},
			$sorted
		);

		$this->assertEquals( $sorted_array, $sorted, 'Date integers should be sorted in the appropriate ascending order.' );
	}

	/**
	 */
	public function test_date_sort_with_array_of_mutable_objects() {
		$now          = Dates::mutable( '47448000' );
		$start_date   = Dates::mutable( '47448001' );
		$end_date     = Dates::mutable( '47448002' );
		$sorted_array = [ $now, $start_date, $end_date ];
		$test_array   = [ $end_date, $now, $start_date ];

		$sorted = Dates::sort( $test_array );

		// Both arrays are objects.

		$this->assertEquals( $sorted_array, $sorted, 'Date objects should be sorted in the appropriate ascending order.' );
	}

	/**
	 */
	public function test_date_sort_with_array_of_mixed_immutable_and_mutable_objects() {
		$now          = Dates::mutable( '47448000' );
		$start_date   = Dates::immutable( '47448001' );
		$end_date     = Dates::mutable( '47448002' );
		$sorted_array = [ $now, $start_date, $end_date ];
		$test_array   = [ $end_date, $now, $start_date ];

		$sorted = Dates::sort( $test_array );

		// Both arrays are objects.

		$this->assertEquals( $sorted_array, $sorted, 'Date objects should be sorted in the appropriate ascending order.' );
	}

	/**
	 */
	public function test_date_sort_with_array_of_immutable_objects() {
		$now          = Dates::immutable( '47448000' );
		$start_date   = Dates::immutable( '47448001' );
		$end_date     = Dates::immutable( '47448002' );
		$sorted_array = [ $now, $start_date, $end_date ];
		$test_array   = [ $end_date, $now, $start_date ];

		$sorted = Dates::sort( $test_array );

		// Both arrays are objects.

		$this->assertEquals( $sorted_array, $sorted, 'Date objects should be sorted in the appropriate ascending order.' );
	}

	/**
	 */
	public function test_date_sort_with_mixed_array() {
		$now          = '47448000';
		$start_date   = 47448001;
		$end_date     = Dates::build_date_object( '47448002' );
		$sorted_array = [ '47448000', '47448001', '47448002' ];
		$test_array   = [ $end_date, $now, $start_date ];

		$sorted = Dates::sort( $test_array );

		// Ensure we're *comparing* apples to apples here, instead of datetime objects to strings.
		$sorted = array_map(
			function( $date ) {
				return $date->format( 'U' );
			},
			$sorted
		);

		$this->assertEquals( $sorted_array, $sorted, 'Date integers should be sorted in the appropriate ascending order.' );
	}

	/**
	 */
	public function test_date_sort_desc() {
		$now        = '47448000';
		$start_date = '47448001';
		$end_date   = '47448002';
		// In descending order!
		$sorted_array = [ $end_date, $start_date, $now ];
		$test_array   = [ $end_date, $now, $start_date ];

		$sorted = Dates::sort( $test_array, 'DESC' );

		// Ensure we're *comparing* apples to apples here, instead of datetime objects to strings.
		$sorted = array_map(
			function( $date ) {
				return $date->format( 'U' );
			},
			$sorted
		);

		$this->assertEquals( $sorted_array, $sorted, 'Dates should be sorted in the appropriate descending order.' );
	}

	/**
	 */
	public function test_two_identical_timestamps() {
		$now        = '47448000';
		$start_date = '47448000';
		$end_date   = '47448000';

		$this->assertFalse( Dates::is_now( $start_date, $end_date, $now ), 'If the start and end date are identical, the function should fail (return false).' );
	}

	/**
	 */
	public function test_span_starts_after_now() {
		$now        = '47448000';
		$start_date = '47448001';
		$end_date   = '47448002';

		$this->assertFalse( Dates::is_now( $start_date, $end_date, $now ), 'If the start is in the future, the function should fail (return false).' );
	}

	/**
	 */
	public function test_span_starts_after_now_out_of_order() {
		$now        = '47448000';
		$start_date = '47448002';
		$end_date   = '47448001';

		$this->assertFalse( Dates::is_now( $start_date, $end_date, $now ), 'If the start is in the future, the function should fail (return false).' );
	}

	/**
	 */
	public function test_span_ends_before_now() {
		$now        = '47448000';
		$start_date = '47447888';
		$end_date   = '47447999';

		$this->assertFalse( Dates::is_now( $start_date, $end_date, $now ), 'If the end is in the past, the function should fail (return false).' );
	}

	/**
	 */
	public function test_span_ends_before_now_out_of_order() {
		$now        = '47448000';
		$start_date = '47447999';
		$end_date   = '47447888';

		$this->assertFalse( Dates::is_now( $start_date, $end_date, $now ), 'If the end is in the past, the function should fail (return false).' );
	}

	/**
	 */
	public function test_span_ends_now() {
		$now        = '47448000';
		$start_date = '47447888';
		$end_date   = '47448000';

		$this->assertFalse( Dates::is_now( $start_date, $end_date, $now ), 'If the end is now, the function should fail (return false).' );
	}

	/**
	 */
	public function test_span_ends_now_out_of_order() {
		$now        = '47448000';
		$start_date = '47448000';
		$end_date   = '47447888';

		$this->assertFalse( Dates::is_now( $start_date, $end_date, $now ), 'If the end is now, the function should fail (return false).' );
	}

	/**
	 */
	public function test_span_starts_now() {
		$now        = '47448000';
		$start_date = '47448000';
		$end_date   = '47448001';

		$this->assertTrue( Dates::is_now( $start_date, $end_date, $now ), 'If the start is now, the function should pass (return true).' );
	}

	/**
	 */
	public function test_span_starts_now_out_of_order() {
		$now        = '47448000';
		$start_date = '47448001';
		$end_date   = '47448000';

		$this->assertTrue( Dates::is_now( $start_date, $end_date, $now ), 'If the start is now, the function should pass (return true).' );
	}

	/**
	 */
	public function test_span_started_in_past() {
		$now        = '47448000';
		$start_date = '47447999';
		$end_date   = '47448001';

		$this->assertTrue( Dates::is_now( $start_date, $end_date, $now ), 'If the started in the past (and is not ended), the function should pass (return true).' );
	}

	/**
	 */
	public function test_span_started_in_past_out_of_order() {
		$now        = '47448000';
		$start_date = '47448001';
		$end_date   = '47447999';

		$this->assertTrue( Dates::is_now( $start_date, $end_date, $now, 'If the started in the past (and is not ended), the function should pass (return true).' ) );
	}
}
