<?php

namespace Tribe;

require_once codecept_data_dir( 'classes/WP_Screen.php' );

use Tribe\Common\Tests\WP_Screen as WP_Screen;
use Tribe__Context as Context;
use Tribe__Admin__Helpers as Admin_Helpers;

class AdminHelpersTest extends \Codeception\TestCase\WPTestCase {
	function setUp(): void {
		parent::setUp();

		global $pagenow;
		$pagenow = null;
		unset( $_GET['post_type'] );

	}

	/**
	 * @test
	 * it should be instantiatable
	 * @since 4.9.6
	 */
	public function it_should_be_instantiatable() {
		$helpers = $this->make_instance();

		$this->assertInstanceOf( Admin_helpers::class, $helpers );
	}

	/**
	 * Return instance
	 *
	 * @return Admin_Helpers
	 * @since 4.9.6
	 */
	private function make_instance() {
		return new Admin_Helpers();
	}

	/**
	 * Check results for `is_post_type_screen` on the new event page
	 *
	 * @since 4.9.6
	 * @test
	 */
	public function is_post_type_tribe_events() {
		$helpers = $this->make_instance();
		add_filter( 'tribe_admin_is_wp_screen', '__return_true' );

		$this->go_to( "/wp-admin/post-new.php?post_type=tribe_events" );

		global $pagenow;
		$pagenow           = 'post-new.php';
		$_GET['post_type'] = 'tribe_events';

		global $current_screen;
		$current_screen = new WP_Screen( [
						'in_admin'  => true,
						'id'        => 'tribe_events',
						'post_type' => 'tribe_events'
					]
				);

		$this->assertTrue( $helpers->is_post_type_screen() );
	}

	/**
	 * Check results for `is_post_type_screen` on the new venue page
	 *
	 * @since 4.9.6
	 * @test
	 */
	public function is_post_type_tribe_venue() {
		$helpers = $this->make_instance();
		add_filter( 'tribe_admin_is_wp_screen', '__return_true' );

		$this->go_to( "/wp-admin/post-new.php?post_type=tribe_venue" );

		global $pagenow;
		$pagenow           = 'post-new.php';
		$_GET['post_type'] = 'tribe_venue';

		global $current_screen;
		$current_screen = new WP_Screen( [
						'in_admin'  => true,
						'id'        => 'tribe_venue',
						'post_type' => 'tribe_venue'
					]
				);

		$this->assertTrue( $helpers->is_post_type_screen() );
	}

	/**
	 * Check results for `is_post_type_screen` on the new organizers page
	 *
	 * @since 4.9.6
	 * @test
	 */
	public function is_post_type_tribe_organizer() {
		$helpers = $this->make_instance();
		add_filter( 'tribe_admin_is_wp_screen', '__return_true' );

		$this->go_to( "/wp-admin/post-new.php?post_type=tribe_organizer" );

		global $pagenow;
		$pagenow           = 'post-new.php';
		$_GET['post_type'] = 'tribe_organizer';

		global $current_screen;
		$current_screen = new WP_Screen( [
						'in_admin'  => true,
						'id'        => 'tribe_organizer',
						'post_type' => 'tribe_organizer'
					]
				);

		$this->assertTrue( $helpers->is_post_type_screen() );
	}

	/**
	 * Check results for `is_screen` on the WP dashboard
	 *
	 * @since 4.9.6
	 * @test
	 */
	public function is_screen_dashboard_should_return_false() {
		$helpers = $this->make_instance();
		add_filter( 'tribe_admin_is_wp_screen', '__return_true' );

		global $current_screen;
		$current_screen = new WP_Screen( [
						'in_admin'  => true,
						'id'        => 'dashboard',
						'base'      => 'dashboard',
						'post_type' => ''
					]
				 );

		$this->assertFalse( $helpers->is_screen() );
	}

	/**
	 * Check results for `is_screen` on the settings page
	 *
	 * @since 4.9.6
	 * @test
	 */
	public function is_screen_tribe_settings_general() {
		$helpers = $this->make_instance();
		add_filter( 'tribe_admin_is_wp_screen', '__return_true' );

		$this->go_to( "/wp-admin/edit.php?post_type=tribe_events&page=tribe-common" );
		global $pagenow;
		$pagenow           = 'edit.php';
		$_GET['post_type'] = 'tribe_events';
		$_GET['page']      = 'tribe-common';

		global $current_screen;
		$current_screen = new WP_Screen( [
						'in_admin'  => true,
						'id'        => 'tribe_events_page_tribe-common',
						'base'      => 'tribe_events_page_tribe-common',
						'post_type' => 'tribe_events'
					]
				);

		$this->assertTrue( $helpers->is_screen() );

	}

	/**
	 * Check results for `is_screen` on the aggregator page
	 *
	 * @since 4.9.6
	 * @test
	 */
	public function is_screen_tribe_aggregator() {
		$helpers = $this->make_instance();
		add_filter( 'tribe_admin_is_wp_screen', '__return_true' );

		$this->go_to( "/wp-admin/edit.php?post_type=tribe_events&page=aggregator" );
		global $pagenow;
		$pagenow           = 'edit.php';
		$_GET['post_type'] = 'tribe_events';
		$_GET['page']      = 'aggregator';

		global $current_screen;
		$current_screen = new WP_Screen( [
						'in_admin'  => true,
						'id'        => 'tribe_events_page_aggregator',
						'base'      => 'tribe_events_page_aggregator',
						'post_type' => 'tribe_events'
					]
				);

		$this->assertTrue( $helpers->is_screen() );

	}

	/**
	 * Check results for `is_screen` on the help page
	 *
	 * @since 4.9.6
	 * @test
	 */
	public function is_screen_tribe_help() {
		$helpers = $this->make_instance();
		add_filter( 'tribe_admin_is_wp_screen', '__return_true' );

		$this->go_to( "/wp-admin/edit.php?post_type=tribe_events&page=tribe-help" );
		global $pagenow;
		$pagenow           = 'edit.php';
		$_GET['post_type'] = 'tribe_events';
		$_GET['page']      = 'tribe-help';

		global $current_screen;
		$current_screen = new WP_Screen( [
						'in_admin'  => true,
						'id'        => 'tribe_events_page_tribe-help',
						'base'      => 'tribe_events_page_tribe-help',
						'post_type' => 'tribe_events'
					]
				);

		$this->assertTrue( $helpers->is_screen() );

	}

	/**
	 * Check results for `is_screen` on the app shop page
	 *
	 * @since 4.9.6
	 * @test
	 */
	public function is_screen_tribe_app_shop() {
		$helpers = $this->make_instance();
		add_filter( 'tribe_admin_is_wp_screen', '__return_true' );

		$this->go_to( "/wp-admin/edit.php?post_type=tribe_events&page=tribe-app-shop" );
		global $pagenow;
		$pagenow           = 'edit.php';
		$_GET['post_type'] = 'tribe_events';
		$_GET['page']      = 'tribe-app-shop';

		global $current_screen;
		$current_screen = new WP_Screen( [
						'in_admin'  => true,
						'id'        => 'tribe_events_page_tribe-app-shop',
						'base'      => 'tribe_events_page_tribe-app-shop',
						'post_type' => 'tribe_events'
					]
				);

		$this->assertTrue( $helpers->is_screen() );

	}

	/**
	 * Check results for `is_screen` on the tags page
	 *
	 * @since 4.9.6
	 * @test
	 */
	public function is_screen_tribe_events_post_tag() {
		$helpers = $this->make_instance();
		add_filter( 'tribe_admin_is_wp_screen', '__return_true' );

		$this->go_to( "wp-admin/edit-tags.php?taxonomy=post_tag&post_type=tribe_events" );
		global $pagenow;
		$pagenow           = 'edit-tags.php';
		$_GET['post_type'] = 'tribe_events';
		$_GET['taxonomy']  = 'post_tag';

		global $current_screen;
		$current_screen = new WP_Screen( [
						'in_admin'  => true,
						'post_type' => 'tribe_events',
						'taxonomy'  => 'post_tag',
						'id'        => 'edit-post_tag',
						'base'      => 'edit-tags'
					]
				);

		$this->assertTrue( $helpers->is_screen() );

	}

	/**
	 * Check results for `is_screen` on the categories page
	 *
	 * @since 4.9.6
	 * @test
	 */
	public function is_screen_tribe_events_cat() {
		$helpers = $this->make_instance();
		add_filter( 'tribe_admin_is_wp_screen', '__return_true' );

		$this->go_to( "wp-admin/edit-tags.php?taxonomy=tribe_events_cat&post_type=tribe_events" );
		global $pagenow;
		$pagenow           = 'edit-tags.php';
		$_GET['post_type'] = 'tribe_events';
		$_GET['taxonomy']  = 'tribe_events_cat';

		global $current_screen;
		$current_screen = new WP_Screen( [
							'in_admin'  => true,
							'post_type' => 'tribe_events',
							'taxonomy'  => 'tribe_events_cat',
							'id'        => 'edit-tribe_events_cat',
							'base'      => 'edit-tags'
						]
					);

		$this->assertTrue( $helpers->is_screen() );

	}
}