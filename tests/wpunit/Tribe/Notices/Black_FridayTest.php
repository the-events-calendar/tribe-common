<?php

use Tribe__Date_Utils as Dates;

class Black_FridayTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * Test ! should_display() when constant is set.
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
			"tribe_black-friday_notice_start_date",
			function( $date ) {
				// Set the start date to the past.
				return Dates::build_date_object( '-7 days', 'UTC' );
			}
		);

		add_filter(
			"tribe_black-friday_notice_end_date",
			function( $date ) {
				// Set the end date to the future.
				return Dates::build_date_object( '+7 days', 'UTC' );
			}
		);

		// Ensure we're on a good screen.
		set_current_screen( 'tribe_events_page_tribe-common' );

		$notice = tribe( Tribe\Admin\Notice\Marketing\Black_Friday::class );

		$this->assertFalse( $notice->should_display() );

		// So we don't muck up later tests.
		remove_all_filters( "tribe_black-friday_notice_start_date" );
		uopz_undefine( 'TRIBE_HIDE_UPSELL' );
	}

	/**
	 * Test ! should_display() when on wrong screen
	 *
	 * @test
	 */
	public function should_not_display_when_wrong_screen() {
		// Ensure we're on a good date.
		add_filter(
			"tribe_black-friday_notice_start_date",
			function( $date ) {
				// Set the start date to the past.
				return Dates::build_date_object( '-7 days', 'UTC' );
			}
		);

		add_filter(
			"tribe_black-friday_notice_end_date",
			function( $date ) {
				// Set the end date to the future.
				return Dates::build_date_object( '+7 days', 'UTC' );
			}
		);

		// Ensure we're on the wrong screen.
		set_current_screen( 'edit-post' );

		$notice = tribe( Tribe\Admin\Notice\Marketing\Black_Friday::class );

		$this->assertFalse( $notice->should_display() );

		// So we don't muck up later tests.
		remove_all_filters( "tribe_black-friday_notice_start_date" );
	}

	/**
	 * Test ! should_display() when date passed
	 *
	 * @test
	 */
	public function should_not_display_when_past() {
		add_filter(
			"tribe_black-friday_notice_start_date",
			function( $date ) {
				// Set the start date to the past.
				return Dates::build_date_object( '-7 days', 'UTC' );
			}
		);

		add_filter(
			"tribe_black-friday_notice_end_date",
			function( $date ) {
				// Set the end date to the past.
				return Dates::build_date_object( '-5 days', 'UTC' );
			}
		);

		// Ensure we're on a good screen.
		set_current_screen( 'tribe_events_page_tribe-common' );

		$notice = tribe( Tribe\Admin\Notice\Marketing\Black_Friday::class );

		$this->assertFalse( $notice->should_display() );

		// So we don't muck up later tests.
		remove_all_filters( "tribe_black-friday_notice_start_date" );
		remove_all_filters( "tribe_black-friday_notice_end_date" );
	}

	/**
	 * Test ! should_display() when date in future
	 *
	 * @test
	 */
	public function should_not_display_when_in_future() {
		add_filter(
			"tribe_black-friday_notice_start_date",
			function( $date ) {
				// Set the start date to the future.
				return Dates::build_date_object( '+5 days', 'UTC' );
			}
		);

		add_filter(
			"tribe_black-friday_notice_end_date",
			function( $date ) {
				// Set the end date to the future.
				return Dates::build_date_object( '+7 days', 'UTC' );
			}
		);

		// Ensure we're on a good screen.
		set_current_screen( 'tribe_events_page_tribe-common' );

		$notice = tribe( Tribe\Admin\Notice\Marketing\Black_Friday::class );

		$this->assertFalse( $notice->should_display() );

		// So we don't muck up later tests.
		remove_all_filters( "tribe_black-friday_notice_start_date" );
		remove_all_filters( "tribe_black-friday_notice_end_date" );
	}

	/**
	 * Test should_display() when the stars align (all conditions true).
	 *
	 * @test
	 */
	public function should_display_when_stars_align() {
		add_filter(
			"tribe_black-friday_notice_start_date",
			function( $date ) {
				// Set the start date to the past.
				return Dates::build_date_object( '-7 days', 'UTC' );
			}
		);

		add_filter(
			"tribe_black-friday_notice_end_date",
			function( $date ) {
				// Set the end date to the future.
				return Dates::build_date_object( '+7 days', 'UTC' );
			}
		);

		// Ensure we're on a good screen.
		set_current_screen( 'tribe_events_page_tribe-common' );

		$notice = tribe( Tribe\Admin\Notice\Marketing\Black_Friday::class );

		$this->assertTrue( $notice->should_display() );

		// So we don't muck up later tests.
		remove_all_filters( "tribe_black-friday_notice_start_date" );
		remove_all_filters( "tribe_black-friday_notice_end_date" );
	}
}
