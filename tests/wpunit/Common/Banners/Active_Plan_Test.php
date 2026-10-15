<?php

namespace TEC\Common\Banners;

use Codeception\TestCase\WPTestCase;
use Tribe\Tests\Traits\With_Harbor_State;
use Tribe__PUE__Checker as PUE_Checker;

class Active_Plan_Test extends WPTestCase {
	use With_Harbor_State;

	public function setUp() {
		parent::setUp();

		delete_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY );
	}

	public function tearDown() {
		delete_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY );

		parent::tearDown();
	}

	/**
	 * @covers \TEC\Common\Banners\Active_Plan::exists
	 */
	public function test_it_exists_with_only_a_legacy_license(): void {
		$this->given_a_legacy_key_status( true );

		$this->assertTrue( ( new Active_Plan() )->exists() );
	}

	/**
	 * @covers \TEC\Common\Banners\Active_Plan::exists
	 */
	public function test_it_exists_with_only_a_consolidated_license(): void {
		$this->seed_unified_license_key();
		$this->seed_harbor_catalog_for_tec( [] );

		$this->assertTrue( ( new Active_Plan() )->exists() );
	}

	/**
	 * @covers \TEC\Common\Banners\Active_Plan::exists
	 */
	public function test_it_does_not_exist_without_a_license(): void {
		$this->assertFalse( ( new Active_Plan() )->exists() );
	}

	/**
	 * @covers \TEC\Common\Banners\Active_Plan::exists
	 */
	public function test_it_does_not_exist_when_the_legacy_license_is_invalid(): void {
		$this->given_a_legacy_key_status( false );

		$this->assertFalse( ( new Active_Plan() )->exists() );
	}

	/**
	 * The consolidated license API is absent on sandbox scraping and plugin activation requests.
	 *
	 * @covers \TEC\Common\Banners\Active_Plan::exists
	 */
	public function test_it_does_not_exist_when_the_harbor_function_is_not_loaded(): void {
		$this->assertSame( 'false', $this->exists_without_harbor_function( false ) );
	}

	/**
	 * @covers \TEC\Common\Banners\Active_Plan::exists
	 */
	public function test_it_exists_with_a_legacy_license_when_the_harbor_function_is_not_loaded(): void {
		$this->assertSame( 'true', $this->exists_without_harbor_function( [ 'plugins' => [ 'events-calendar-pro' => true ] ] ) );
	}

	/**
	 * Runs Active_Plan in a bare PHP process, since a loaded function cannot be undefined in this one.
	 *
	 * @param array<string, array<string, bool>>|false $legacy The stubbed legacy license transient value.
	 *
	 * @return string The process output: `true`, `false`, or the fatal error message.
	 */
	private function exists_without_harbor_function( $legacy ): string {
		// phpcs:disable WordPress.PHP.DevelopmentFunctions.error_log_var_export,WordPress.PHP.DiscouragedPHPFunctions.system_calls_shell_exec
		$script = sprintf(
			'class Tribe__PUE__Checker { const IS_ANY_LICENSE_VALID_TRANSIENT_KEY = "key"; }
			function get_transient() { return %s; }
			require %s;
			echo ( new TEC\\Common\\Banners\\Active_Plan() )->exists() ? "true" : "false";',
			var_export( $legacy, true ),
			var_export( dirname( __DIR__, 4 ) . '/src/Common/Banners/Active_Plan.php', true )
		);

		$output = shell_exec( escapeshellarg( PHP_BINARY ) . ' -d display_errors=1 -r ' . escapeshellarg( $script ) . ' 2>&1' );
		// phpcs:enable

		return trim( (string) $output );
	}

	/**
	 * Stores a legacy PUE key status.
	 */
	private function given_a_legacy_key_status( bool $valid ): void {
		set_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY, [ 'plugins' => [ 'events-calendar-pro' => $valid ] ] );
	}
}
