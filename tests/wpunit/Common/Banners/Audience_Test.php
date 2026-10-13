<?php

namespace TEC\Common\Banners;

use Codeception\TestCase\WPTestCase;
use Tribe__PUE__Checker as PUE_Checker;

class Audience_Test extends WPTestCase {

	/**
	 * @before
	 */
	public function set_up_an_administrator_with_an_active_plan(): void {
		wp_set_current_user( static::factory()->user->create( [ 'role' => 'administrator' ] ) );
		set_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY, [ 'plugins' => [ 'events-calendar-pro' => true ] ] );
	}

	/**
	 * @after
	 */
	public function tear_down_audience_state(): void {
		delete_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY );
		set_current_screen( 'front' );
	}

	/**
	 * @covers \TEC\Common\Banners\Audience::should_display
	 */
	public function test_it_includes_administrators_on_plugin_pages(): void {
		set_current_screen( 'edit-tribe_events' );

		$this->assertTrue( $this->audience()->should_display() );
	}

	/**
	 * @covers \TEC\Common\Banners\Audience::should_display
	 */
	public function test_it_includes_the_shared_licensing_page(): void {
		set_current_screen( 'settings_page_lw-software-manager' );

		$this->assertTrue( $this->audience()->should_display() );
	}

	/**
	 * @covers \TEC\Common\Banners\Audience::should_display
	 */
	public function test_it_excludes_other_admin_pages(): void {
		set_current_screen( 'dashboard' );

		$this->assertFalse( $this->audience()->should_display() );
	}

	/**
	 * @covers \TEC\Common\Banners\Audience::should_display
	 */
	public function test_it_excludes_post_types_that_are_not_the_plugins(): void {
		set_current_screen( 'edit-page' );

		$this->assertFalse( $this->audience()->should_display() );
	}

	/**
	 * @covers \TEC\Common\Banners\Audience::should_display
	 */
	public function test_it_excludes_users_who_cannot_manage_options(): void {
		set_current_screen( 'edit-tribe_events' );
		wp_set_current_user( static::factory()->user->create( [ 'role' => 'editor' ] ) );

		$this->assertFalse( $this->audience()->should_display() );
	}

	/**
	 * @covers \TEC\Common\Banners\Audience::should_display
	 */
	public function test_it_excludes_sites_without_an_active_plan(): void {
		set_current_screen( 'edit-tribe_events' );
		delete_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY );

		$this->assertFalse( $this->audience()->should_display() );
	}

	private function audience(): Audience {
		return new Audience( new Active_Plan() );
	}
}
