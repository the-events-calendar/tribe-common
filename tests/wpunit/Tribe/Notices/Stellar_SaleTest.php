<?php

use Tribe__Date_Utils as Dates;
use Tribe\Tests\Traits\With_Uopz;

class Stellar_SaleTest extends \Codeception\TestCase\WPTestCase {
	use With_Uopz;

	/**
	 * Test ! should_display() when constant is set.
	 * Note this test assumes we have not set the constant in our testing environment.
	 *
	 * Need uopz to test this!
	 *
	 * @skip
	 */
	public function should_not_display_when_upsells_hidden() {
		// Set the constant.
		uopz_redefine( 'TRIBE_HIDE_UPSELL', true );
		// Ensure we're on a good date.
		add_filter(
			"tribe_stellar-sale_notice_start_date",
			function( $date ) {
				// Set the start date to the past.
				return Dates::build_date_object( '-7 days', 'UTC' );
			}
		);

		add_filter(
			"tribe_stellar-sale_notice_end_date",
			function( $date ) {
				// Set the end date to the future.
				return Dates::build_date_object( '+7 days', 'UTC' );
			}
		);

		// Ensure we're on a good screen.
		set_current_screen( 'tribe_events_page_tribe-common' );

		$notice = tribe( Tribe\Admin\Notice\Marketing\Stellar_Sale::class );

		$this->assertFalse( $notice->should_display() );

		// So we don't muck up later tests.
		remove_all_filters( "tribe_stellar-sale_notice_start_date" );
		uopz_undefine( 'TRIBE_HIDE_UPSELL' );
	}

	/**
	 * Test ! should_display() when on wrong screen.
	 * Note this test assumes we have not set the constant in our testing environment.
	 *
	 * @test
	 */
	public function should_not_display_when_wrong_screen() {
		// Ensure we're on a good date.
		add_filter(
			"tribe_stellar-sale_notice_start_date",
			function( $date ) {
				// Set the start date to the past.
				return Dates::build_date_object( '-7 days', 'UTC' );
			}
		);

		add_filter(
			"tribe_stellar-sale_notice_end_date",
			function( $date ) {
				// Set the end date to the future.
				return Dates::build_date_object( '+7 days', 'UTC' );
			}
		);

		// Ensure we're on the WRONG screen.
		set_current_screen( 'edit-post' );

		$notice = tribe( Tribe\Admin\Notice\Marketing\Stellar_Sale::class );

		$this->assertFalse( $notice->should_display() );

		// So we don't muck up later tests.
		remove_all_filters( "tribe_stellar-sale_notice_start_date" );
	}



	/**
	 * Test ! should_display() when date passed.
	 * Note this test assumes we have not set the constant in our testing environment.
	 *
	 * @test
	 */
	public function should_not_display_when_past() {
		// Ensure we're on a good screen.
		set_current_screen( 'tribe_events_page_tribe-common' );

		// Mock the `now` date to be this year, in the past of the notice display date.
		$year = date( 'Y' );
		$this->set_class_fn_return( Dates::class, 'build_date_object', static function ( $input ) use ( $year ) {
			return $input === 'now' ?
				new DateTime( "$year-02-23 09:23:23" )
				: new DateTime( $input );
		}, true );

		$notice = tribe( Tribe\Admin\Notice\Marketing\Black_Friday::class );

		$this->assertFalse( $notice->should_display() );
	}

	/**
	 * Test ! should_display() when date in future.
	 * Note this test assumes we have not set the constant in our testing environment.
	 *
	 * @test
	 */
	public function should_not_display_when_in_future() {
		// Ensure we're on a good screen.
		set_current_screen( 'tribe_events_page_tribe-common' );

		// Mock the `now` date to be this year, in the future of the notice display date.
		$year = date( 'Y' );
		$this->set_class_fn_return( Dates::class, 'build_date_object', static function ( $input ) use ( $year ) {
			return $input === 'now' ?
				new DateTime( "$year-12-10 09:23:23" )
				: new DateTime( $input );
		}, true );

		$notice = tribe( Tribe\Admin\Notice\Marketing\Black_Friday::class );

		$this->assertFalse( $notice->should_display() );
	}

	/**
	 * Test should_display() when the stars align (all conditions true).
	 * Note this test assumes we have not set the constant in our testing environment.
	 *
	 * @test
	 */
	public function should_display_when_stars_align() {
		// Ensure we're on a good screen.
		set_current_screen( 'tribe_events_page_tribe-common' );

		// Mock the `now` date to be this year on November 21st.
		$year = date( 'Y' );
		$this->set_class_fn_return( Dates::class, 'build_date_object', static function ( $input ) use ( $year ) {
			return $input === 'now' ?
				new DateTime( "2022-07-27 19:23:23" )
				: new DateTime( $input );
		}, true );

		$notice = tribe( Tribe\Admin\Notice\Marketing\Stellar_Sale::class );

		$this->assertTrue( $notice->should_display() );
	}
}
