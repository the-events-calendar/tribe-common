<?php

namespace TEC\Common\Banners;

use Codeception\TestCase\WPTestCase;
use DateTimeImmutable;
use DateTimeZone;
use Tribe\Tests\Traits\With_Clock_Mock;
use Tribe__PUE__Checker as PUE_Checker;

class Software_Packages_Banner_Test extends WPTestCase {
	use With_Clock_Mock;

	/**
	 * @before
	 */
	public function set_up_a_site_that_should_see_the_banner(): void {
		// Every copy of stellarwp/banners shares its registry through this global, so start each test without the banner.
		unset( $GLOBALS['nx_banners'] );
		wp_set_current_user( static::factory()->user->create( [ 'role' => 'administrator' ] ) );
		set_current_screen( 'edit-tribe_events' );
		// No expiration, so the frozen clock cannot expire it.
		set_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY, [ 'plugins' => [ 'events-calendar-pro' => true ] ] );
	}

	/**
	 * @after
	 */
	public function tear_down_banner_state(): void {
		unset( $GLOBALS['nx_banners'] );
		delete_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY );
		set_current_screen( 'front' );
	}

	/**
	 * @covers \TEC\Common\Banners\Software_Packages_Banner::register
	 */
	public function test_it_does_not_show_before_its_start(): void {
		$this->freeze_clock_at( '2026-10-14 11:59:59' );

		$this->assertSame( '', $this->render_banner() );
	}

	/**
	 * @covers \TEC\Common\Banners\Software_Packages_Banner::register
	 */
	public function test_it_shows_from_its_start(): void {
		$this->freeze_clock_at( '2026-10-14 12:00:00' );

		$this->assertStringContainsString( 'notice nx-banner', $this->render_banner() );
	}

	/**
	 * @covers \TEC\Common\Banners\Software_Packages_Banner::register
	 */
	public function test_it_keeps_showing_years_later_because_it_has_no_end(): void {
		$this->freeze_clock_at( '2036-10-14 12:00:00' );

		$this->assertStringContainsString( 'notice nx-banner', $this->render_banner() );
	}

	/**
	 * Freezes the clock at a New York time.
	 */
	private function freeze_clock_at( string $time ): void {
		$this->freeze_time( new DateTimeImmutable( $time, new DateTimeZone( 'America/New_York' ) ) );
	}

	/**
	 * Registers the banner and renders it at the top of the current screen.
	 */
	private function render_banner(): string {
		$banner = new Software_Packages_Banner( new Audience( new Active_Plan() ) );
		$banner->register();

		ob_start();
		$banner->render_at_top();

		return (string) ob_get_clean();
	}
}
