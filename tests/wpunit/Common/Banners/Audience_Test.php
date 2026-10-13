<?php

namespace TEC\Common\Banners;

use Codeception\TestCase\WPTestCase;
use Tribe__PUE__Checker as PUE_Checker;
use WP_Screen;

class Audience_Test extends WPTestCase {

	public function setUp() {
		parent::setUp();

		wp_set_current_user( static::factory()->user->create( [ 'role' => 'administrator' ] ) );
		set_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY, [ 'plugins' => [ 'events-calendar-pro' => true ] ] );
	}

	public function tearDown() {
		delete_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY );
		unset( $_GET['page'] );
		set_current_screen( 'front' );

		parent::tearDown();
	}

	public function plugin_post_types(): array {
		return [
			'events'             => [ 'tribe_events' ],
			'venues'             => [ 'tribe_venue' ],
			'organizers'         => [ 'tribe_organizer' ],
			'series'             => [ 'tribe_event_series' ],
			'calendar embeds'    => [ 'tec_calendar_embed' ],
			'orders'             => [ 'tec_tc_order' ],
			'attendee fieldsets' => [ 'ticket-meta-fieldset' ],
		];
	}

	/**
	 * @covers \TEC\Common\Banners\Audience::should_display
	 */
	public function test_it_includes_administrators_on_plugin_pages(): void {
		set_current_screen( 'edit-tribe_events' );

		$this->assertTrue( $this->audience()->should_display() );
	}

	/**
	 * @dataProvider plugin_post_types
	 * @covers \TEC\Common\Banners\Audience::should_display
	 */
	public function test_it_includes_the_plugins_post_type_lists( string $post_type ): void {
		$this->set_post_type_screen( 'edit-' . $post_type, $post_type, 'edit' );

		$this->assertTrue( $this->audience()->should_display() );
	}

	/**
	 * @dataProvider plugin_post_types
	 * @covers \TEC\Common\Banners\Audience::should_display
	 */
	public function test_it_excludes_the_plugins_single_item_editors( string $post_type ): void {
		$this->set_post_type_screen( $post_type, $post_type, 'post' );

		$this->assertFalse( $this->audience()->should_display() );
	}

	/**
	 * @covers \TEC\Common\Banners\Audience::should_display
	 */
	public function test_it_excludes_creating_an_event(): void {
		$this->set_post_type_screen( 'tribe_events', 'tribe_events', 'post', 'add' );

		$this->assertFalse( $this->audience()->should_display() );
	}

	/**
	 * @covers \TEC\Common\Banners\Audience::should_display
	 */
	public function test_it_includes_the_tickets_setup_guide(): void {
		$_GET['page'] = 'tickets-setup';
		set_current_screen( 'tickets_page_tickets-setup' );

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
	public function test_it_includes_users_who_are_not_administrators(): void {
		set_current_screen( 'edit-tribe_events' );
		wp_set_current_user( static::factory()->user->create( [ 'role' => 'editor' ] ) );

		$this->assertTrue( $this->audience()->should_display() );
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

	/**
	 * Sets a post type's screen, whether or not a plugin registers that post type in the test site.
	 */
	private function set_post_type_screen( string $id, string $post_type, string $base, string $action = '' ): void {
		$screen            = WP_Screen::get( $id );
		$screen->post_type = $post_type;
		$screen->base      = $base;
		$screen->action    = $action;
		set_current_screen( $screen );
	}
}
