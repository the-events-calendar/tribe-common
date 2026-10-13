<?php

namespace TEC\Common\Banners;

use Codeception\TestCase\WPTestCase;
use Tribe\Tests\Traits\With_Harbor_State;
use Tribe__PUE__Checker as PUE_Checker;

class Active_Plan_Test extends WPTestCase {
	use With_Harbor_State;

	/**
	 * @before
	 * @after
	 */
	public function clear_legacy_key_statuses(): void {
		delete_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY );
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
	 * Records a legacy PUE key status for a paid plugin, as each checker does on admin_init.
	 */
	private function given_a_legacy_key_status( bool $valid ): void {
		set_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY, [ 'plugins' => [ 'events-calendar-pro' => $valid ] ] );
	}
}
