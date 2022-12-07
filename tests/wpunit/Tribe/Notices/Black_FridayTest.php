<?php

use Tribe\Tests\Traits\With_Uopz;
use Tribe__Date_Utils as Dates;

class Black_FridayTest extends \Codeception\TestCase\WPTestCase {
	use With_Uopz;

	/**
	 * Test ! should_display() when constant is set.
	 *
	 * Need uopz to test this!
	 */
	public function should_not_display_when_upsells_hidden() {
		// Set the constant.
		$this->set_const_value( 'TRIBE_HIDE_UPSELL', true );
		// Ensure we're on a good date.
		add_filter(
			'tribe_black-friday_notice_start_date',
			function ( $date ) {
				// Set the start date to the past.
				return Dates::build_date_object( '-7 days', 'UTC' );
			}
		);

		add_filter(
			'tribe_black-friday_notice_end_date',
			function ( $date ) {
				// Set the end date to the future.
				return Dates::build_date_object( '+7 days', 'UTC' );
			}
		);

		// Ensure we're on a good screen.
		set_current_screen( 'tribe_events_page_tribe-common' );

		$notice = tribe( Tribe\Admin\Notice\Marketing\Black_Friday::class );

		$this->assertFalse( $notice->should_display() );
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
			'tribe_black-friday_notice_start_date',
			function ( $date ) {
				// Set the start date to the past.
				return Dates::build_date_object( '-7 days', 'UTC' );
			}
		);

		add_filter(
			'tribe_black-friday_notice_end_date',
			function ( $date ) {
				// Set the end date to the future.
				return Dates::build_date_object( '+7 days', 'UTC' );
			}
		);

		// Ensure we're on the WRONG screen.
		set_current_screen( 'edit-post' );

		$notice = tribe( Tribe\Admin\Notice\Marketing\Black_Friday::class );

		$this->assertFalse( $notice->should_display() );
	}

	/**
	 * Test ! should_display() when date passed.
	 * Note this test assumes we have not set the constant in our testing environment.
	 *
	 * @test
	 */
	public function should_not_display_when_past() {
		add_filter(
			'tribe_black-friday_notice_start_date',
			function ( $date ) {
				// Set the start date to the past.
				return Dates::build_date_object( '-7 days', 'UTC' );
			}, 200
		);

		add_filter(
			'tribe_black-friday_notice_end_date',
			function ( $date ) {
				// Set the end date to the past.
				return Dates::build_date_object( '-5 days', 'UTC' );
			}, 200
		);

		// Ensure we're on a good screen.
		set_current_screen( 'tribe_events_page_tribe-common' );

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
		add_filter(
			'tribe_black-friday_notice_start_date',
			function ( $date ) {
				// Set the start date to the future.
				return Dates::build_date_object( '+5 days', 'UTC' );
			}, 200
		);

		add_filter(
			'tribe_black-friday_notice_end_date',
			function ( $date ) {
				// Set the end date to the future.
				return Dates::build_date_object( '+7 days', 'UTC' );
			}, 200
		);

		// Ensure we're on a good screen.
		set_current_screen( 'tribe_events_page_tribe-common' );

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
		add_filter(
			'tribe_black-friday_notice_start_date',
			function ( $date ) {
				// Set the start date to the past.
				return Dates::build_date_object( '-7 days', 'UTC' );
			}, 200
		);

		add_filter(
			'tribe_black-friday_notice_end_date',
			function ( $date ) {
				// Set the end date to the future.
				return Dates::build_date_object( '+7 days', 'UTC' );
			}, 200
		);

		// Ensure we're on a good screen.
		set_current_screen( 'tribe_events_page_tribe-common' );

		$notice = tribe( Tribe\Admin\Notice\Marketing\Black_Friday::class );

		codecept_debug( $notice );

		$this->assertTrue( $notice->should_display() );
	}
}
