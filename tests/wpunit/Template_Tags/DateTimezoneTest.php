<?php

namespace Template_Tags;

use Tribe__Date_Utils;

/**
 * Regression tests ensuring `tribe_format_date()` honors the wall-clock
 * timestamp contract regardless of the PHP default timezone in effect.
 */

// phpcs:disable WordPress.DateTime.RestrictedFunctions.timezone_change_date_default_timezone_set -- Simulating third-party code changing PHP's default timezone is the behavior under test.
class DateTimezoneTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @var string The PHP default timezone before the test ran.
	 */
	protected $php_timezone_backup;

	protected function setUp() {
		parent::setUp();

		$this->php_timezone_backup = date_default_timezone_get();

		update_option( 'timezone_string', 'America/Chicago' );
		tribe( 'cache' )->reset();
	}

	protected function tearDown() {
		date_default_timezone_set( $this->php_timezone_backup );

		update_option( 'timezone_string', '' );
		update_option( 'gmt_offset', 0 );

		parent::tearDown();
	}

	/**
	 * PHP default timezones covering all failure-relevant characteristics: zero
	 * offset (control), negative offsets with northern DST, large positive
	 * without DST, half-hour and 45-minute fractional offsets, southern DST
	 * with fractional offset, and the extreme +14 offset past the date line.
	 *
	 * @return array<string,array<string>>
	 */
	public static function php_timezones(): array {
		return [
			'php=UTC'                 => [ 'UTC' ],
			'php=America/Chicago'     => [ 'America/Chicago' ],
			'php=America/Los_Angeles' => [ 'America/Los_Angeles' ],
			'php=Asia/Tokyo'          => [ 'Asia/Tokyo' ],
			'php=Asia/Kolkata'        => [ 'Asia/Kolkata' ],
			'php=Asia/Kathmandu'      => [ 'Asia/Kathmandu' ],
			'php=Pacific/Chatham'     => [ 'Pacific/Chatham' ],
			'php=Pacific/Kiritimati'  => [ 'Pacific/Kiritimati' ],
		];
	}

	/**
	 * A wall-clock datetime string must render as the same wall-clock time under
	 * any PHP default timezone: a non-UTC default timezone used to shift the
	 * output by its UTC offset.
	 *
	 * @dataProvider php_timezones
	 */
	public function test_string_input_renders_wall_clock_time_under_any_php_timezone( string $php_tz ): void {
		date_default_timezone_set( $php_tz );

		$this->assertEquals(
			'2050-07-22 11:30 am',
			tribe_format_date( '2050-07-22 11:30:00', true, 'Y-m-d g:i a' )
		);
	}

	/**
	 * A wall-clock datetime string near the day boundary must not cross into the
	 * adjacent day when rendered under a PHP default timezone with a large offset.
	 *
	 * @dataProvider php_timezones
	 */
	public function test_string_input_near_day_boundary_keeps_its_date( string $php_tz ): void {
		date_default_timezone_set( $php_tz );

		$this->assertEquals(
			'2050-12-31 11:30 pm',
			tribe_format_date( '2050-12-31 23:30:00', true, 'Y-m-d g:i a' )
		);
	}

	/**
	 * The current-year check deciding between the yearless and with-year formats
	 * must compare wall-clock years: under a large positive PHP default timezone
	 * offset, a current-year date near the year boundary used to be treated as
	 * next year's and rendered with a redundant year.
	 *
	 * @dataProvider php_timezones
	 */
	public function test_year_check_compares_wall_clock_years( string $php_tz ): void {
		// A current-year date whose wall-clock timestamp crosses into the next
		// year when shifted by a positive UTC offset.
		$current_year = ( new \DateTimeImmutable( 'now', wp_timezone() ) )->format( 'Y' );
		$date         = "{$current_year}-12-31 23:30:00";

		date_default_timezone_set( 'UTC' );
		$baseline = tribe_format_date( $date, false );

		if ( false !== strpos( $baseline, $current_year ) ) {
			$this->fail( "Baseline render of a current-year date should use the yearless format: {$baseline}" );
		}

		date_default_timezone_set( $php_tz );

		$this->assertEquals( $baseline, tribe_format_date( $date, false ) );
	}

	/**
	 * Unparsable input must keep falling back to the legacy `strtotime()`
	 * behavior: `date_i18n()` receives `false` and renders the current date in
	 * the site timezone.
	 */
	public function test_unparsable_input_preserves_legacy_fallback(): void {
		$today = ( new \DateTimeImmutable( 'now', new \DateTimeZone( 'America/Chicago' ) ) )->format( 'Y-m-d' );

		$this->assertEquals(
			$today,
			tribe_format_date( 'totally invalid input', true, 'Y-m-d' )
		);
	}

	/**
	 * The explicit-UTC parse guarding the fallback must return `false` on
	 * unparsable input instead of defaulting to `now`.
	 */
	public function test_build_date_object_returns_false_on_unparsable_input_without_fallback(): void {
		$this->assertFalse(
			Tribe__Date_Utils::build_date_object( 'totally invalid input', 'UTC', false )
		);
	}
}
