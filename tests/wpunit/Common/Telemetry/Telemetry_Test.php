<?php
/**
 * Tests for the Telemetry suite handling functionality.
 *
 * @since TBD
 *
 * @package TEC\Common\Telemetry
 */

namespace TEC\Common\Telemetry;

use TEC\Common\StellarWP\Telemetry\Config;
use TEC\Common\StellarWP\Telemetry\Opt_In\Status;

/**
 * Class Telemetry_Test
 *
 * Tests the native suite handling functionality of the Telemetry implementation.
 *
 * @since TBD
 *
 * @package TEC\Common\Telemetry
 */
class Telemetry_Test extends \Codeception\TestCase\WPTestCase {

	/**
	 * Clean up after each test.
	 *
	 * @since TBD
	 */
	public function tear_down() {
		parent::tear_down();
		delete_option( Status::OPTION_NAME );
		delete_option( 'tribe_events_calendar_options' );
	}

	/**
	 * @test
	 *
	 * It should register plugins via tec_telemetry_slugs filter.
	 *
	 * @since TBD
	 */
	public function it_should_register_plugins_via_filter() {
		// Add test plugins via filter.
		add_filter( 'tec_telemetry_slugs', function ( $slugs ) {
			$slugs['test-plugin-1'] = 'test-plugin-1/test-plugin-1.php';
			$slugs['test-plugin-2'] = 'test-plugin-2/test-plugin-2.php';
			return $slugs;
		} );

		// Get the registered slugs.
		$slugs = Telemetry::get_tec_telemetry_slugs();

		$this->assertArrayHasKey( 'test-plugin-1', $slugs );
		$this->assertArrayHasKey( 'test-plugin-2', $slugs );
		$this->assertEquals( 'test-plugin-1/test-plugin-1.php', $slugs['test-plugin-1'] );
		$this->assertEquals( 'test-plugin-2/test-plugin-2.php', $slugs['test-plugin-2'] );
	}

	/**
	 * @test
	 *
	 * It should add new plugin with current suite opt-in status.
	 *
	 * @since TBD
	 */
	public function it_should_add_new_plugin_with_current_suite_status() {
		// Boot telemetry.
		$telemetry = tribe( Telemetry::class );
		$telemetry->boot();

		// Register first plugin and opt in.
		add_filter( 'tec_telemetry_slugs', function ( $slugs ) {
			$slugs['test-plugin-1'] = 'test-plugin-1/test-plugin-1.php';
			return $slugs;
		} );

		$_GET['page'] = 'plugins.php';
		global $pagenow;
		$pagenow = 'plugins.php';

		$telemetry->register_tec_telemetry_plugins( true );

		// Verify first plugin is opted in.
		$status = Config::get_container()->get( Status::class );
		$this->assertTrue( $status->plugin_exists( 'test-plugin-1' ) );
		$this->assertTrue( $status->is_active() );

		// Now add a second plugin.
		add_filter( 'tec_telemetry_slugs', function ( $slugs ) {
			$slugs['test-plugin-2'] = 'test-plugin-2/test-plugin-2.php';
			return $slugs;
		} );

		// Clear cache to force re-registration.
		tribe( 'cache' )['tec_telemetry_slugs'] = null;

		// Register again (simulates new plugin activation).
		$telemetry->register_tec_telemetry_plugins();

		// Verify second plugin inherited the opt-in status.
		$this->assertTrue( $status->plugin_exists( 'test-plugin-2' ) );
		$this->assertTrue( $status->is_active() );

		unset( $_GET['page'] );
	}

	/**
	 * @test
	 *
	 * It should sync all plugins when opt-in status changes.
	 *
	 * @since TBD
	 */
	public function it_should_sync_all_plugins_when_status_changes() {
		// Boot telemetry.
		$telemetry = tribe( Telemetry::class );
		$telemetry->boot();

		// Register multiple plugins.
		add_filter( 'tec_telemetry_slugs', function ( $slugs ) {
			$slugs['test-plugin-1'] = 'test-plugin-1/test-plugin-1.php';
			$slugs['test-plugin-2'] = 'test-plugin-2/test-plugin-2.php';
			$slugs['test-plugin-3'] = 'test-plugin-3/test-plugin-3.php';
			return $slugs;
		} );

		$_GET['page'] = 'plugins.php';
		global $pagenow;
		$pagenow = 'plugins.php';

		// Register and opt in.
		$telemetry->register_tec_telemetry_plugins( true );

		$status = Config::get_container()->get( Status::class );

		// All should be opted in.
		$this->assertTrue( $status->is_active() );

		// Now opt out.
		$telemetry->register_tec_telemetry_plugins( false );

		// All should be opted out.
		$this->assertFalse( $status->is_active() );

		unset( $_GET['page'] );
	}

	/**
	 * @test
	 *
	 * It should use shared option by default.
	 *
	 * @since TBD
	 */
	public function it_should_use_shared_option_by_default() {
		$provider = tribe( Provider::class );

		$option_name = $provider->filter_telemetry_option_name( 'stellarwp_telemetry' );

		// Default should use the shared option (not isolated).
		$this->assertEquals( 'stellarwp_telemetry', $option_name );
	}

	/**
	 * @test
	 *
	 * It should allow isolation via filter.
	 *
	 * @since TBD
	 */
	public function it_should_allow_isolation_via_filter() {
		$provider = tribe( Provider::class );

		// Enable isolation.
		add_filter( 'tec_telemetry_isolate_option', '__return_true' );

		$option_name = $provider->filter_telemetry_option_name( 'stellarwp_telemetry' );

		// Should use isolated option.
		$this->assertEquals( 'tec_stellarwp_telemetry', $option_name );

		remove_filter( 'tec_telemetry_isolate_option', '__return_true' );
	}

	/**
	 * @test
	 *
	 * It should disable isolation when filter returns false.
	 *
	 * @since TBD
	 */
	public function it_should_disable_isolation_when_filter_returns_false() {
		$provider = tribe( Provider::class );

		// Explicitly disable isolation.
		add_filter( 'tec_telemetry_isolate_option', '__return_false' );

		$option_name = $provider->filter_telemetry_option_name( 'stellarwp_telemetry' );

		// Should use shared option.
		$this->assertEquals( 'stellarwp_telemetry', $option_name );

		remove_filter( 'tec_telemetry_isolate_option', '__return_false' );
	}

	/**
	 * @test
	 *
	 * It should not register plugins during AJAX requests.
	 *
	 * @since TBD
	 */
	public function it_should_not_register_during_ajax() {
		define( 'DOING_AJAX', true );

		$telemetry = tribe( Telemetry::class );
		$telemetry->boot();

		add_filter( 'tec_telemetry_slugs', function ( $slugs ) {
			$slugs['test-plugin-1'] = 'test-plugin-1/test-plugin-1.php';
			return $slugs;
		} );

		// Try to register (should bail early).
		$telemetry->register_tec_telemetry_plugins();

		$status = Config::get_container()->get( Status::class );

		// Should not have registered.
		$this->assertFalse( $status->plugin_exists( 'test-plugin-1' ) );
	}

	/**
	 * @test
	 *
	 * It should not register plugins during autosave.
	 *
	 * @since TBD
	 */
	public function it_should_not_register_during_autosave() {
		define( 'DOING_AUTOSAVE', true );

		$telemetry = tribe( Telemetry::class );
		$telemetry->boot();

		add_filter( 'tec_telemetry_slugs', function ( $slugs ) {
			$slugs['test-plugin-1'] = 'test-plugin-1/test-plugin-1.php';
			return $slugs;
		} );

		// Try to register (should bail early).
		$telemetry->register_tec_telemetry_plugins();

		$status = Config::get_container()->get( Status::class );

		// Should not have registered.
		$this->assertFalse( $status->plugin_exists( 'test-plugin-1' ) );
	}

	/**
	 * @test
	 *
	 * It should get status object correctly.
	 *
	 * @since TBD
	 */
	public function it_should_get_status_object() {
		$telemetry = tribe( Telemetry::class );
		$telemetry->boot();

		$status = Telemetry::get_status_object();

		$this->assertInstanceOf( Status::class, $status );
	}

	/**
	 * @test
	 *
	 * Deprecated calculate_optin_status should still work.
	 *
	 * @since TBD
	 */
	public function deprecated_calculate_optin_status_should_still_work() {
		$telemetry = tribe( Telemetry::class );
		$telemetry->boot();

		// Should return boolean based on Status::is_active().
		$result = $telemetry->calculate_optin_status();
		$this->assertIsBool( $result );

		// Explicit value should be returned as-is.
		$this->assertTrue( $telemetry->calculate_optin_status( true ) );
		$this->assertFalse( $telemetry->calculate_optin_status( false ) );
	}
}
