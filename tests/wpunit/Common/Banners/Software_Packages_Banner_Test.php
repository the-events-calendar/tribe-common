<?php

namespace TEC\Common\Banners;

use Codeception\TestCase\WPTestCase;
use DateTimeImmutable;
use DateTimeZone;
use TEC\Common\Nexcess\Banners\Registry;
use Tribe\Tests\Traits\With_Clock_Mock;
use Tribe__PUE__Checker as PUE_Checker;

class Software_Packages_Banner_Test extends WPTestCase {
	use With_Clock_Mock;

	public function setUp() {
		parent::setUp();

		unset( $GLOBALS['nx_banners'] );
		wp_set_current_user( static::factory()->user->create( [ 'role' => 'administrator' ] ) );
		set_current_screen( 'edit-tribe_events' );
		set_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY, [ 'plugins' => [ 'events-calendar-pro' => true ] ] );
	}

	public function tearDown() {
		unset( $GLOBALS['nx_banners'], $_GET['page'] );
		delete_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY );
		set_current_screen( 'front' );

		parent::tearDown();
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
	 * @covers \TEC\Common\Banners\Software_Packages_Banner::render_at_top
	 * @covers \TEC\Common\Banners\Software_Packages_Banner::render_in_sidebar
	 */
	public function test_it_shows_in_the_help_hub_sidebar_instead_of_at_the_top(): void {
		$this->freeze_clock_at( '2026-10-15 12:00:00' );
		$_GET['page'] = 'tec-tickets-help-hub';
		set_current_screen( 'tickets_page_tec-tickets-help-hub' );
		$banner = $this->banner();
		$banner->register();

		ob_start();
		$banner->render_at_top();
		$this->assertSame( '', (string) ob_get_clean() );

		ob_start();
		$banner->render_in_sidebar();
		$this->assertStringContainsString( 'notice nx-banner', (string) ob_get_clean() );
	}

	/**
	 * Freezes the clock at a New York time.
	 */
	private function freeze_clock_at( string $time ): void {
		$this->freeze_time( new DateTimeImmutable( $time, new DateTimeZone( 'America/New_York' ) ) );
	}

	private function banner(): Software_Packages_Banner {
		return new Software_Packages_Banner( new Audience( new Active_Plan() ), new Registry() );
	}

	/**
	 * Registers and renders the banner.
	 */
	private function render_banner(): string {
		$banner = $this->banner();
		$banner->register();

		ob_start();
		$banner->render_at_top();

		return (string) ob_get_clean();
	}
}
