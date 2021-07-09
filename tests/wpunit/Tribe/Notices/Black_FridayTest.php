<?php

use Tribe__Date_Utils as Dates;

class Black_FridayTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * Test ! should_display() when constant is set.
	 *
	 * @test
	 */
	public function should_not_display_when_upsells_hidden() {
		// Set the constant.
		uopz_redefine( 'TRIBE_HIDE_UPSELL', true );
		// Ensure we're on a good date.
		add_filter(
			"tribe_black-friday_notice__start_date",
			function( $date ) {
				// Set the start date to today to be sure it's the constant that's stopping us.
				return Dates::build_date_object( 'today', 'UTC' );
		} );

		// Ensure we're on a good screen.
		set_current_screen( 'tribe_events_page_tribe-common' );

		$notice = tribe( Tribe\Admin\Notice\Marketing\Black_Friday::class );

		$this->assertFalse( $notice->should_display() );

		// So we don't muck up later tests.
		remove_all_filters( "tribe_black-friday_notice__start_date" );
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
			"tribe_black-friday_notice__start_date",
			function( $date ) {
				// Set the start date to today to be sure it's the constant that's stopping us.
				return Dates::build_date_object( 'today', 'UTC' );
		} );

		// Ensure we're on the wrong screen.
		set_current_screen( 'edit-post' );

		$notice = tribe( Tribe\Admin\Notice\Marketing\Black_Friday::class );

		$this->assertFalse( $notice->should_display() );

		// So we don't muck up later tests.
		remove_all_filters( "tribe_black-friday_notice__start_date" );
	}

	/**
	 * Test ! should_display() when on wrong date
	 *
	 * @test
	 */
	public function should_not_display_when_wrong_date() {
		// Set start and end dates in the past.
		add_filter(
			"tribe_black-friday_notice__start_date",
			function( $date ) {
				// Set the start date to today to be sure it's the constant that's stopping us.
				return Dates::build_date_object( '-7 days', 'UTC' );
		} );
		add_filter(
			"tribe_black-friday_notice__end_date",
			function( $date ) {
				// Set the start date to today to be sure it's the constant that's stopping us.
				return Dates::build_date_object( '-5 days', 'UTC' );
		} );

		// Ensure we're on a good screen.
		set_current_screen( 'tribe_events_page_tribe-common' );

		$notice = tribe( Tribe\Admin\Notice\Marketing\Black_Friday::class );

		$this->assertFalse( $notice->should_display() );

		// So we don't muck up later tests.
		remove_all_filters( "tribe_black-friday_notice__start_date" );
		remove_all_filters( "tribe_black-friday_notice__end_date" );
	}

	/**
	 * Test should_display() when the stars align (all conditions true).
	 *
	 * @test
	 */
	public function should_display_when_stars_align() {
		// Set start and end dates to bracket now.
		add_filter(
			"tribe_black-friday_notice__start_date",
			function( $date ) {
				// Set the start date to today to be sure it's the constant that's stopping us.
				return Dates::build_date_object( '-7 days', 'UTC' );
		} );
		add_filter(
			"tribe_black-friday_notice__end_date",
			function( $date ) {
				// Set the start date to today to be sure it's the constant that's stopping us.
				return Dates::build_date_object( '+7 days', 'UTC' );
		} );

		// Ensure we're on a good screen.
		set_current_screen( 'tribe_events_page_tribe-common' );

		$notice = tribe( Tribe\Admin\Notice\Marketing\Black_Friday::class );

		$this->assertTrue( $notice->should_display() );

		// So we don't muck up later tests.
		remove_all_filters( "tribe_black-friday_notice__start_date" );
		remove_all_filters( "tribe_black-friday_notice__end_date" );
	}
}
