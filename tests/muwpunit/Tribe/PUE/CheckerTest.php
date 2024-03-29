<?php
namespace Tribe\PUE;

require_once codecept_data_dir( 'classes/WP_Screen.php' );

use Tribe\Common\Tests\WP_Screen;
use Tribe__PUE__Checker as Checker;

class CheckerTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @var int
	 */
	protected $superadmin_user;

	/**
	 * @var int
	 */
	protected $main_site;

	/**
	 * @var string
	 */
	protected $pue_update_url = 'pue_update_url';

	/**
	 * @var string
	 */
	protected $slug = 'event-aggregator';

	/**
	 * @var string
	 */
	protected $plugin_file = 'event-aggregator/event-aggregator.php';

	/**
	 * @var string
	 */
	protected $network_plugin_file = 'the-events-calendar/the-events-calendar.php';

	public function setUp(): void {
		// before
		parent::setUp();

		// your set up methods here
		$GLOBALS['current_screen'] = new WP_Screen( [ 'in_admin' => 'not-network' ] );
		$this->superadmin_user     = $this->factory()->user->create( [ 'role' => 'administrator' ] );
		grant_super_admin( $this->superadmin_user );
		global $current_site;
		$this->main_site = $current_site->blog_id;
	}

	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->make_instance();

		$this->assertInstanceOf( Checker::class, $sut );
	}

	/**
	 * @test
	 * it should not mark plugin as network activated if network key and local key are not set
	 */
	public function it_should_not_mark_plugin_as_network_activated_if_network_key_and_local_key_are_not_set() {
		$user = $this->factory()->user->create();
		$blog = $this->factory()->blog->create( [ 'domain' => 'sub1', 'path' => '/', 'user' => $user ] );

		$sut = $this->make_instance();

		// no network option
		delete_network_option( null, $sut->get_license_option_key() );
		// no local option on main site
		delete_option( $sut->get_license_option_key() );

		switch_to_blog( $blog );
		// no local option on subsite
		delete_option( $sut->get_license_option_key() );

		$is_network_licensed_on_subsite = $sut->is_network_licensed();

		$this->assertFalse( $is_network_licensed_on_subsite );
	}

	/**
	 * @test
	 * it should mark a plugin as network activated if network key is set and local key is not set
	 */
	public function it_should_mark_a_plugin_as_network_activated_if_network_key_is_set_and_local_key_is_not_set() {
		$user = $this->factory()->user->create();
		$blog = $this->factory()->blog->create( [ 'domain' => 'sub1', 'path' => '/', 'user' => $user ] );

		$sut = $this->make_instance();

		// no network option
		update_network_option( null, $sut->get_license_option_key(), 'network-key' );
		// no local option on main site
		delete_option( $sut->get_license_option_key() );

		switch_to_blog( $blog );
		// no local option on subsite
		delete_option( $sut->get_license_option_key() );

		// spoof the site-wide active plugins option
		add_filter( 'pre_site_option_active_sitewide_plugins', function () {
			return [ 'the-events-calendar/the-events-calendar.php' => true ];
		} );

		$is_network_licensed_on_subsite = $sut->is_network_licensed();

		$this->assertTrue( $is_network_licensed_on_subsite );
	}

	/**
	 * @test
	 * it should mark a plugin as network activated if network and local key are set to same value
	 */
	public function it_should_mark_a_plugin_as_network_activated_if_network_and_local_key_are_set_to_same_value() {
		$user = $this->factory()->user->create();
		$blog = $this->factory()->blog->create( [ 'domain' => 'sub1', 'path' => '/', 'user' => $user ] );

		$sut = $this->make_instance();

		// no network option
		update_network_option( null, $sut->get_license_option_key(), 'same-key' );
		// no local option on main site
		delete_option( $sut->get_license_option_key() );

		switch_to_blog( $blog );
		// no local option on subsite
		update_option( $sut->get_license_option_key(), 'same-key' );

		// spoof the site-wide active plugins option
		add_filter( 'pre_site_option_active_sitewide_plugins', function () {
			return [ 'the-events-calendar/the-events-calendar.php' => true ];
		} );

		$is_network_licensed_on_subsite = $sut->is_network_licensed();

		$this->assertTrue( $is_network_licensed_on_subsite );
	}

	/**
	 * @test
	 * it should mark plugin as locally activated if network key is not set and local key is set
	 */
	public function it_should_mark_plugin_as_locally_activated_if_network_key_is_not_set_and_local_key_is_set() {
		$user = $this->factory()->user->create();
		$blog = $this->factory()->blog->create( [ 'domain' => 'sub1', 'path' => '/', 'user' => $user ] );

		$sut = $this->make_instance();

		// no network option
		delete_network_option( null, $sut->get_license_option_key() );
		// no local option on main site
		delete_option( $sut->get_license_option_key() );

		switch_to_blog( $blog );
		// no local option on subsite
		update_option( $sut->get_license_option_key(), 'local-key' );

		$is_network_licensed_on_subsite = $sut->is_network_licensed();

		$this->assertFalse( $is_network_licensed_on_subsite );
	}

	/**
	 * @test
	 * it should mark plugin as locally activated if network and local key are set and different
	 */
	public function it_should_mark_plugin_as_locally_activated_if_network_and_local_key_are_set_and_different() {
		$user = $this->factory()->user->create();
		$blog = $this->factory()->blog->create( [ 'domain' => 'sub1', 'path' => '/', 'user' => $user ] );

		$sut = $this->make_instance();

		// no network option
		update_network_option( null, $sut->get_license_option_key(), 'network-key' );
		// no local option on main site
		delete_option( $sut->get_license_option_key() );

		switch_to_blog( $blog );
		// no local option on subsite
		update_option( $sut->get_license_option_key(), 'local-key' );

		$is_network_licensed_on_subsite = $sut->is_network_licensed();

		$this->assertFalse( $is_network_licensed_on_subsite );
	}

	/**
	 * @test
	 * it should show network editable license to network admin in network license settings
	 */
	public function it_should_show_network_editable_license_to_network_admin_in_network_license_settings() {
		$GLOBALS['current_screen'] = new WP_Screen( [ 'in_admin' => 'network' ] );
		update_network_option( null, 'active_sitewide_plugins', [ $this->network_plugin_file => true ] );
		wp_set_current_user( $this->superadmin_user );

		$sut = $this->make_instance();

		$this->assertFalse( $sut->should_show_subsite_editable_license() );
		$this->assertFalse( $sut->should_show_overrideable_license() );
		$this->assertTrue( $sut->should_show_network_editable_license() );
	}

	/**
	 * @test
	 * it should show locally editable license to network admin in subsite license settings
	 */
	public function it_should_show_locally_editable_license_to_network_admin_in_subsite_license_settings() {
		$GLOBALS['current_screen'] = new WP_Screen( [ 'in_admin' => 'not-network' ] );
		update_network_option( null, 'active_sitewide_plugins', [ $this->network_plugin_file => true ] );
		$subsite_admin = $this->factory()->user->create();
		$blog          = $this->factory()->blog->create( [ 'domain' => 'sub1', 'path' => '/', 'user' => $subsite_admin ] );
		switch_to_blog( $blog );
		wp_set_current_user( $this->superadmin_user );

		$sut = $this->make_instance();

		$this->assertTrue( $sut->should_show_subsite_editable_license() );
		$this->assertFalse( $sut->should_show_overrideable_license() );
		$this->assertFalse( $sut->should_show_network_editable_license() );
	}

	/**
	 * @test
	 * it should show non editable network license to subsite amin in subsite license settings
	 */
	public function it_should_show_non_editable_network_license_to_subsite_amin_in_subsite_license_settings() {
		$GLOBALS['current_screen'] = new WP_Screen( [ 'in_admin' => 'not-network' ] );
		update_network_option( null, 'active_sitewide_plugins', [ $this->network_plugin_file => true ] );
		$subsite_admin = $this->factory()->user->create();
		$blog          = $this->factory()->blog->create( [ 'domain' => 'sub1', 'path' => '/', 'user' => $subsite_admin ] );
		switch_to_blog( $blog );
		wp_set_current_user( $subsite_admin );

		$sut = $this->make_instance();

		$this->assertFalse( $sut->should_show_subsite_editable_license() );
		$this->assertTrue( $sut->should_show_overrideable_license() );
		$this->assertFalse( $sut->should_show_network_editable_license() );
	}

	/**
	 * @test
	 * it should show locally editable license to network admin in main site settings if plugin is not nw activated
	 */
	public function it_should_show_locally_editable_license_to_network_admin_in_main_site_settings_if_plugin_is_not_nw_activated(
	) {
		$GLOBALS['current_screen'] = new WP_Screen( [ 'in_admin' => 'not-network' ] );
		update_network_option( null, 'active_sitewide_plugins', [] );
		switch_to_blog( $this->main_site );
		wp_set_current_user( $this->superadmin_user );

		$sut = $this->make_instance();

		$this->assertTrue( $sut->should_show_subsite_editable_license() );
		$this->assertFalse( $sut->should_show_overrideable_license() );
		$this->assertFalse( $sut->should_show_network_editable_license() );
	}

	/**
	 * @test
	 * it should show locally editable license to network admin on subsite if plugin is not nw activated
	 */
	public function it_should_show_locally_editable_license_to_network_admin_on_subsite_if_plugin_is_not_nw_activated() {
		$GLOBALS['current_screen'] = new WP_Screen( [ 'in_admin' => 'not-network' ] );
		update_network_option( null, 'active_sitewide_plugins', [] );
		$subsite_admin = $this->factory()->user->create();
		$blog          = $this->factory()->blog->create( [ 'domain' => 'sub1', 'path' => '/', 'user' => $subsite_admin ] );
		switch_to_blog( $blog );
		wp_set_current_user( $this->superadmin_user );

		$sut = $this->make_instance();

		$this->assertTrue( $sut->should_show_subsite_editable_license() );
		$this->assertFalse( $sut->should_show_overrideable_license() );
		$this->assertFalse( $sut->should_show_network_editable_license() );
	}

	/**
	 * @test
	 * it should show locally editable license to subsite admin on subsite if plugin is not nw activated
	 */
	public function it_should_show_locally_editable_license_to_subsite_admin_on_subsite_if_plugin_is_not_nw_activated() {
		$GLOBALS['current_screen'] = new WP_Screen( [ 'in_admin' => 'not-network' ] );
		update_network_option( null, 'active_sitewide_plugins', [] );
		$subsite_admin = $this->factory()->user->create();
		$blog          = $this->factory()->blog->create( [ 'domain' => 'sub1', 'path' => '/', 'user' => $subsite_admin ] );
		switch_to_blog( $blog );
		wp_set_current_user( $subsite_admin );

		$sut = $this->make_instance();

		$this->assertTrue( $sut->should_show_subsite_editable_license() );
		$this->assertFalse( $sut->should_show_overrideable_license() );
		$this->assertFalse( $sut->should_show_network_editable_license() );
	}

	/**
	 * @test
	 * it should show locally editable license to main site admin on main site if plugin is not nw activated
	 */
	public function it_should_show_locally_editable_license_to_main_site_admin_on_main_site_if_plugin_is_not_nw_activated() {

		$GLOBALS['current_screen'] = new WP_Screen( [ 'in_admin' => 'not-network' ] );
		update_network_option( null, 'active_sitewide_plugins', [] );
		$main_site_admin = $this->factory()->user->create_and_get();
		switch_to_blog( $this->main_site );
		$main_site_admin->add_role( 'administrator' );
		wp_set_current_user( $main_site_admin->ID );

		$sut = $this->make_instance();

		$this->assertTrue( $sut->should_show_subsite_editable_license() );
		$this->assertFalse( $sut->should_show_overrideable_license() );
		$this->assertFalse( $sut->should_show_network_editable_license() );
	}

	/**
	 * @return Checker
	 */
	private function make_instance() {
		return new Checker( $this->pue_update_url, $this->slug, $this->plugin_file );
	}
}