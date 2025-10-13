<?php
/**
 * Tests for Has_Datetime_Conditions trait.
 *
 * @since TBD
 *
 * @package TEC\Common\Tests\Admin\Conditional_Content\Traits;
 */

declare( strict_types=1 );

namespace TEC\Common\Tests\Admin\Conditional_Content\Traits;

use Codeception\TestCase\WPTestCase;
use TEC\Common\Admin\Conditional_Content\Traits\Has_Datetime_Conditions;
use Tribe\Utils\Date_I18n;
use Tribe__Date_Utils as Dates;

/**
 * Class Has_Datetime_Conditions_Test
 *
 * @since TBD
 *
 * @package TEC\Common\Tests\Admin\Conditional_Content\Traits;
 */
class Has_Datetime_Conditions_Test extends WPTestCase {

	/**
	 * Test implementation class.
	 */
	protected $test_class;

	/**
	 * Set up test environment.
	 *
	 * @before
	 */
	public function set_up(): void {
		// Create a test class that uses the trait.
		$this->test_class = new class() {
			use Has_Datetime_Conditions;

			public string $slug       = 'test-content';
			public string $start_date = 'January 1st';
			public string $end_date   = 'December 31st';
			public int $start_time    = 0;
			public int $end_time      = 0;

			public function hook(): void {
				// No-op for testing.
			}

			public function get_slug(): string {
				return $this->slug . '-' . date_i18n( 'Y' );
			}

			// Expose protected methods for testing.
			public function public_get_start_time(): ?Date_I18n {
				return $this->get_start_time();
			}

			public function public_get_end_time(): ?Date_I18n {
				return $this->get_end_time();
			}

			public function public_should_display(): bool {
				return $this->should_display();
			}
		};
	}

	/**
	 * Tear down test environment.
	 *
	 * @after
	 */
	public function tear_down(): void {
		remove_all_filters( 'tec_admin_conditional_content_test-content_start_date' );
		remove_all_filters( 'tec_admin_conditional_content_test-content_end_date' );
		remove_all_filters( 'tec_admin_conditional_content_test-content_should_display' );
	}

	/**
	 * Test that get_start_time returns a Date_I18n object.
	 *
	 * @test
	 */
	public function should_return_date_object_for_start_time() {
		$start_time = $this->test_class->public_get_start_time();

		$this->assertInstanceOf( Date_I18n::class, $start_time );
	}

	/**
	 * Test that get_end_time returns a Date_I18n object.
	 *
	 * @test
	 */
	public function should_return_date_object_for_end_time() {
		$end_time = $this->test_class->public_get_end_time();

		$this->assertInstanceOf( Date_I18n::class, $end_time );
	}

	/**
	 * Test that start_time defaults to midnight (0) when empty.
	 *
	 * @test
	 */
	public function should_default_start_time_to_midnight() {
		unset( $this->test_class->start_time );
		$start_time = $this->test_class->public_get_start_time();

		$this->assertEquals( 0, $start_time->format( 'G' ) );
	}

	/**
	 * Test that end_time defaults to midnight (0) when empty.
	 *
	 * @test
	 */
	public function should_default_end_time_to_midnight() {
		unset( $this->test_class->end_time );
		$end_time = $this->test_class->public_get_end_time();

		$this->assertEquals( 0, $end_time->format( 'G' ) );
	}

	/**
	 * Test that should_display returns true when within date range.
	 *
	 * @test
	 */
	public function should_display_when_within_date_range() {
		$now = Dates::build_date_object( 'now', 'UTC' );

		// Set dates to span current time.
		$this->test_class->start_date = $now->sub( new \DateInterval( 'P1D' ) )->format( 'Y-m-d' );
		$this->test_class->end_date   = $now->add( new \DateInterval( 'P2D' ) )->format( 'Y-m-d' );

		$this->assertTrue( $this->test_class->public_should_display() );
	}

	/**
	 * Test that should_display returns false when before start date.
	 *
	 * @test
	 */
	public function should_not_display_when_before_start_date() {
		$future = Dates::build_date_object( 'now', 'UTC' )->add( new \DateInterval( 'P10D' ) );

		$this->test_class->start_date = $future->format( 'Y-m-d' );
		$this->test_class->end_date   = $future->add( new \DateInterval( 'P5D' ) )->format( 'Y-m-d' );

		$this->assertFalse( $this->test_class->public_should_display() );
	}

	/**
	 * Test that should_display returns false when after end date.
	 *
	 * @test
	 */
	public function should_not_display_when_after_end_date() {
		$past = Dates::build_date_object( 'now', 'UTC' )->sub( new \DateInterval( 'P10D' ) );

		$this->test_class->start_date = $past->format( 'Y-m-d' );
		$this->test_class->end_date   = $past->add( new \DateInterval( 'P5D' ) )->format( 'Y-m-d' );

		$this->assertFalse( $this->test_class->public_should_display() );
	}

	/**
	 * Test that start date can be filtered.
	 *
	 * @test
	 */
	public function should_allow_filtering_start_date() {
		$year        = date_i18n( 'Y' );
		$custom_date = Dates::build_date_object( $year . '-06-15', 'UTC' );

		add_filter(
			'tec_admin_conditional_content_test-content_start_date',
			function () use ( $custom_date ) {
				return $custom_date;
			}
		);

		$start_time = $this->test_class->public_get_start_time();

		$this->assertEquals( $year . '-06-15', $start_time->format( 'Y-m-d' ) );
	}

	/**
	 * Test that end date can be filtered.
	 *
	 * @test
	 */
	public function should_allow_filtering_end_date() {
		$year        = date_i18n( 'Y' );
		$custom_date = Dates::build_date_object( $year . '-12-25', 'UTC' );

		add_filter(
			'tec_admin_conditional_content_test-content_end_date',
			function () use ( $custom_date ) {
				return $custom_date;
			}
		);

		$end_time = $this->test_class->public_get_end_time();

		$this->assertEquals( $year . '-12-25', $end_time->format( 'Y-m-d' ) );
	}

	/**
	 * Test that should_display can be filtered.
	 *
	 * @test
	 */
	public function should_allow_filtering_should_display() {
		// Set valid date range.
		$now = Dates::build_date_object( 'now', 'UTC' );

		$this->test_class->start_date = $now->sub( new \DateInterval( 'P1D' ) )->format( 'Y-m-d' );
		$this->test_class->end_date   = $now->add( new \DateInterval( 'P2D' ) )->format( 'Y-m-d' );

		// Filter to force false.
		add_filter( 'tec_admin_conditional_content_test-content_should_display', '__return_false' );

		$this->assertFalse( $this->test_class->public_should_display() );
	}

	/**
	 * Test that should_display returns false when start date filter returns null.
	 *
	 * @test
	 */
	public function should_not_display_when_start_date_filter_returns_null() {
		add_filter( 'tec_admin_conditional_content_test-content_start_date', '__return_null' );

		$this->assertFalse( $this->test_class->public_should_display() );
	}

	/**
	 * Test that should_display returns false when end date filter returns null.
	 *
	 * @test
	 */
	public function should_not_display_when_end_date_filter_returns_null() {
		add_filter( 'tec_admin_conditional_content_test-content_end_date', '__return_null' );

		$this->assertFalse( $this->test_class->public_should_display() );
	}

	/**
	 * Test that get_template returns a Template instance.
	 *
	 * @test
	 */
	public function should_return_template_instance() {
		$template = $this->test_class->get_template();

		$this->assertInstanceOf( \Tribe__Template::class, $template );
	}

	/**
	 * Test that get_template returns the same instance (singleton pattern).
	 *
	 * @test
	 */
	public function should_return_same_template_instance() {
		$template1 = $this->test_class->get_template();
		$template2 = $this->test_class->get_template();

		$this->assertSame( $template1, $template2 );
	}
}
