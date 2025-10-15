<?php

namespace TEC\Common\Notifications;

use Codeception\TestCase\WPTestCase;
use Tribe\Tests\Traits\With_Uopz;
use Tribe\Tests\Traits\WP_Send_Json_Mocks;
use TEC\Common\Notifications\Readable_Trait;
use TEC\Common\Admin\Conditional_Content\Traits\Is_Dismissible;

/**
 * Class Notifications_Test
 *
 * @since 6.4.0
 *
 * @package TEC\Common\Notifications
 */
class Notifications_Test extends WPTestCase {
	use With_Uopz;
	use WP_Send_JSON_Mocks;
	use Readable_Trait;
	use Is_Dismissible;

	protected string $optin_key = 'ian-notifications-opt-in';

	protected string $main_nonce = 'common_ian_nonce';

	protected string $nonce_prefix = 'ian_nonce_';

	protected string $slug;

	protected array $actions = [
		'optin'    => 'wp_ajax_ian_optin',
		'dismiss'  => 'wp_ajax_ian_dismiss',
		'read'     => 'wp_ajax_ian_read',
		'read_all' => 'wp_ajax_ian_read_all',
		'get_feed' => 'wp_ajax_ian_get_feed',
	];

	/**
	 * @before
	 */
	public function init_notifications() {
		tribe_remove_option( $this->optin_key );
		tribe_update_option( $this->optin_key, false );
	}

	/**
	 * @after
	 */
	public function deinit_notifications() {
		tribe_remove_option( $this->optin_key );
	}

	/**
	 * Get the slug.
	 *
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * Calculate a future version based on the current TEC version.
	 *
	 * @return string
	 */
	public function calculate_test_future_version() {
		$version                      = \Tribe__Events__Main::VERSION;
		$parts                        = explode( '.', $version );
		$parts[ count( $parts ) - 1 ] = $parts[ count( $parts ) - 1 ] + 1;

		return implode( '.', $parts );
	}

	/**
	 * Calculate a past version based on the current TEC version.
	 *
	 * @return string
	 */
	public function calculate_test_past_version() {
		$version = \Tribe__Events__Main::VERSION;
		$parts   = explode( '.', $version );

		// Prevent negative patch versions!
		if ( $parts[ count( $parts ) - 1 ] === '0' ) {
			$parts[ count( $parts ) - 2 ] = $parts[ count( $parts ) - 2 ] - 1;
			$parts[ count( $parts ) - 1 ] = '9';
		} else {
			$parts[ count( $parts ) - 1 ] = $parts[ count( $parts ) - 1 ] - 1;
		}

		return implode( '.', $parts );
	}

	private function get_mocked_feed() {
		return [
			[
				'id'          => '101',
				'type'        => 'update',
				'slug'        => 'tec-update-664',
				'title'       => 'The Events Calendar 6.6.4 Update',
				'html'        => '<p>The latest update of The Events Calendar adds exiting new features!</p>',
				'actions'     => [
					[
						'text'   => 'See Details',
						'url'    => 'https://evnt.is/1ai-',
						'target' => '_blank',
					],
					[
						'text'   => 'Update Now',
						'url'    => '/wp-admin/update-core.php',
						'target' => '_self',
					],
				],
				'dismissible' => true,
				'conditions'  => [ 'wp_version>=5.8', 'php_version>=7.0' ],
			],
			[
				'id'          => '102',
				'type'        => 'notice',
				'slug'        => 'event-tickets-upsell',
				'title'       => 'Sell Tickets & Collect RSVPs with Event Tickets',
				'html'        => '<p>Sell tickets, collect RSVPs and manage attendees for free.</p>',
				'actions'     => [
					[
						'text'   => 'Learn More',
						'url'    => 'https://evnt.is/1aj1',
						'target' => '_blank',
					],
				],
				'dismissible' => true,
				'conditions'  => [ 'plugin_version:the-events-calendar@>=' . $this->calculate_test_past_version() ],
			],
			[
				'id'          => '103',
				'type'        => 'warning',
				'slug'        => 'fbar-upgrade-556',
				'title'       => 'Filter Bar 5.5.6 Security Update',
				'html'        => '<p>Get the latest version of Filter Bar for important security updates.</p>',
				'actions'     => [
					[
						'text'   => 'Update',
						'url'    => '/wp-admin/plugins.php?plugin_status=upgrade',
						'target' => '_self',
					],
				],
				'dismissible' => false,
				'conditions'  => [],
			],
		];
	}

	/**
	 * Setup AJAX Test.
	 */
	private function ajax_setup( ?int $user_id = null ) {
		if ( null === $user_id ) {
			$user_id = self::factory()->user->create( [ 'role' => 'administrator' ] );
			wp_set_current_user( $user_id );
		}

		$this->set_fn_return( 'wp_create_nonce', $this->main_nonce );
		$this->set_fn_return( 'check_ajax_referer', true );
		$this->set_fn_return( 'wp_doing_ajax', true );
		$this->set_fn_return( 'wp_verify_nonce', true );
	}

	/**
	 * @test
	 */
	public function it_should_return_true_for_opt_in() {
		tribe_update_option( $this->optin_key, true );
		$optin = Conditionals::get_opt_in();
		$this->assertTrue( $optin, 'Opt-in check should be true' );
	}

	/**
	 * @test
	 */
	public function it_should_return_false_for_opt_out() {
		tribe_update_option( $this->optin_key, false );
		$optin = Conditionals::get_opt_in();
		$this->assertFalse( $optin, 'Opt-in check should be false' );
	}

	/**
	 * @test
	 */
	public function it_should_match_php_version() {
		$version = '>=7';
		$matches = Conditionals::check_php_version( $version );
		$this->assertTrue( $matches, 'PHP requirement should be met' );
	}

	/**
	 * @test
	 */
	public function it_should_match_wp_version() {
		$version = '>=5';
		$matches = Conditionals::check_wp_version( $version );
		$this->assertTrue( $matches, 'WP requirement should be met' );
	}

	/**
	 * @test
	 */
	public function it_should_match_plugin_version() {
		$tec_version = \Tribe__Events__Main::VERSION;
		$plugins     = [ 'the-events-calendar@>=' . $tec_version ];
		$matches     = Conditionals::check_plugin_version( $plugins );
		$this->assertTrue( $matches, 'Plugin requirement should be met' );
	}

	/* Below here we mock installed plugins to test scenarios without having to install a bunch of plugins.*/

	/**
	 * Get an array of mocked "installed" plugins. To test some potential gotchas.
	 * Overrides get_plugins() with the mocked plugins list.
	 * Returns plugins in the same format as get_plugins() - keyed by folder/file.php.
	 *
	 * @return array The mocked installed plugins.
	 */
	private function mock_installed_plugins() {
		$mocked_plugins = [
			// Real plugin - should match by filename.
			'the-events-calendar/the-events-calendar.php'                             => [ 'Version' => \Tribe__Events__Main::VERSION ],
			// Fake test plugin to ensure we can test multiple plugins.
			'events-pro/events-pro.php'                                               => [ 'Version' => '6.5.0' ],
			// Fake plugins with "the-events-calendar" in the folder name, but different file names.
			// These should NOT match when checking for "the-events-calendar".
			'events-widgets-for-elementor-and-the-events-calendar/events-widgets.php' => [ 'Version' => '10.10.10' ],
			'the-events-calendar-tickets-plus/calendar-tickets-plus.php'              => [ 'Version' => '1.0.0' ],
			// Edge case: folder contains "the-events-calendar" but the filename also has a different pattern.
			'the-events-calendar-addon/addon-plugin.php'                              => [ 'Version' => '2.5.0' ],
		];

		$this->set_fn_return(
			'get_plugins',
			function () use ( $mocked_plugins ) {
				return $mocked_plugins;
			},
			true
		);

		return $mocked_plugins;
	}

	/**
	 * Get merged array of real installed plugins and mocked plugins.
	 * Overrides get_plugins() with the merged plugins list.
	 * This allows testing with both actual plugins and test fixtures.
	 *
	 * @return array The merged installed plugins.
	 */
	private function get_merged_plugins() {
		$real_plugins   = get_plugins();
		$mocked_plugins = $this->mock_installed_plugins();

		// Merge, with mocked plugins overriding real ones if there's a key conflict.
		$merged_plugins = array_merge( $real_plugins, $mocked_plugins );

		$this->set_fn_return(
			'get_plugins',
			function () use ( $merged_plugins ) {
				return $merged_plugins;
			},
			true
		);

		return $merged_plugins;
	}

	/**
	 * Mock is_plugin_active for the given plugins.
	 *
	 * @param array|bool $plugins The plugins to mock.
	 *                                If boolean false, false for all plugins.
	 *                                If boolean true, true for all plugins.
	 *
	 */
	private function mock_is_plugin_active( $plugins ) {
		$this->set_fn_return(
			'is_plugin_active',
			function ( $plugin ) use ( $plugins ) {
				if ( is_bool( $plugins ) ) {
					return $plugins;
				}

				return in_array( $plugin, $plugins, true );
			},
			true
		);
	}

	/**
	 * Test that plugins are matched by their filename (e.g., "the-events-calendar.php")
	 * and not by folder names containing similar text.
	 * This ensures that "the-events-calendar-addon/addon-plugin.php" doesn't
	 * incorrectly match when checking for "the-events-calendar".
	 *
	 * @test
	 */
	public function it_should_match_plugin_by_filename_not_folder_name() {
		// Mock get_plugins to return our test data.
		$this->mock_installed_plugins();

		$tec_version = \Tribe__Events__Main::VERSION;
		$plugins     = [ 'the-events-calendar@>=' . $tec_version ];
		$matches     = Conditionals::check_plugin_version( $plugins );

		$this->assertTrue( $matches, 'Should match the-events-calendar.php file, not folder names containing "the-events-calendar"' );
	}

	/**
	 * @test
	 */
	public function it_should_not_match_plugin_with_similar_folder_name() {
		// Mock get_plugins to return our test data.
		$this->mock_installed_plugins();

		// Try to match a plugin that has "the-events-calendar" in the folder name but different file.
		$plugins = [ 'events-widgets@>=1.0.0' ];
		$matches = Conditionals::check_plugin_version( $plugins );

		$this->assertFalse( $matches, 'Should not match events-widgets.php even though folder contains "the-events-calendar"' );
	}

	/**
	 * @test
	 */
	public function it_should_fail_when_plugin_not_active() {
		// Mock get_plugins to return our test data.
		$this->mock_installed_plugins();

		// Mock is_plugin_active to return false for all plugins.
		$this->mock_is_plugin_active( false );

		$tec_version = \Tribe__Events__Main::VERSION;
		$plugins     = [ 'the-events-calendar@>=' . $tec_version ];
		$matches     = Conditionals::check_plugin_version( $plugins );

		$this->assertFalse( $matches, 'Should fail when plugin is installed but not active' );
	}

	/**
	 * @test
	 */
	public function it_should_use_merged_plugins_for_comprehensive_testing() {
		// This test uses both real and mocked plugins.
		$this->get_merged_plugins();

		// Test with real plugin version.
		$tec_version = \Tribe__Events__Main::VERSION;
		$plugins     = [ 'the-events-calendar@>=' . $tec_version ];
		$matches     = Conditionals::check_plugin_version( $plugins );

		$this->assertTrue( $matches, 'Should work with merged real + mocked plugins' );
	}

	/**
	 * @test
	 */
	public function it_should_correctly_identify_multiple_plugins_with_similar_names() {
		// Mock get_plugins to return our test data.
		$this->mock_installed_plugins();

		// Mock is_plugin_active to return true for TEC and ECP only.
		$this->mock_is_plugin_active(
			[
				'the-events-calendar/the-events-calendar.php',
				'events-pro/events-pro.php',
			]
		);

		$tec_version = \Tribe__Events__Main::VERSION;

		// Test multiple plugins at once.
		$plugins = [
			'the-events-calendar@>=' . $tec_version,
			'events-pro@>=6.0.0',
		];
		$matches = Conditionals::check_plugin_version( $plugins );

		$this->assertTrue( $matches, 'Should correctly match multiple plugins by their filenames' );
	}

	/**
	 * @test
	 */
	public function it_should_handle_edge_case_plugins_with_partial_name_matches() {
		// Mock get_plugins to return our test data.
		$this->mock_installed_plugins();

		// Mock is_plugin_active for all our mocked plugins.
		$this->mock_is_plugin_active(
			[
				'the-events-calendar/the-events-calendar.php',
				'the-events-calendar-addon/addon-plugin.php',
				'the-events-calendar-tickets-plus/calendar-tickets-plus.php',
			]
		);

		// Check for the actual TEC plugin - should match.
		$plugins = [ 'the-events-calendar@>=' . \Tribe__Events__Main::VERSION ];
		$matches = Conditionals::check_plugin_version( $plugins );
		$this->assertTrue( $matches, 'Should find the-events-calendar.php' );

		// Check for the addon plugin with different filename - should not match TEC.
		$plugins = [ 'addon-plugin@>=2.0.0' ];
		$matches = Conditionals::check_plugin_version( $plugins );
		$this->assertTrue( $matches, 'Should find addon-plugin.php by its filename' );

		// Check for calendar-tickets-plus - should not match as "the-events-calendar".
		$plugins = [ 'calendar-tickets-plus@>=1.0.0' ];
		$matches = Conditionals::check_plugin_version( $plugins );
		$this->assertTrue( $matches, 'Should find calendar-tickets-plus.php by its filename' );
	}

	/**
	 * Test that checking for a plugin by folder name does not work.
	 * Plugin checks must use the filename, not the folder name.
	 *
	 * @test
	 */
	public function it_should_not_match_by_folder_name_when_filename_differs() {
		// Mock get_plugins to return our test data.
		$this->mock_installed_plugins();

		// Mock is_plugin_active for the addon plugin.
		$this->mock_is_plugin_active(
			[
				'the-events-calendar-addon/addon-plugin.php',
			]
		);

		// Try checking for "the-events-calendar-addon" - folder exists but file is "addon-plugin.php".
		$plugins = [ 'the-events-calendar-addon@>=1.0.0' ];
		$matches = Conditionals::check_plugin_version( $plugins );
		$this->assertFalse( $matches, 'Should not match by folder name alone; the-events-calendar-addon.php does not exist' );

		// Now check using the actual filename.
		$plugins = [ 'addon-plugin@>=2.0.0' ];
		$matches = Conditionals::check_plugin_version( $plugins );
		$this->assertTrue( $matches, 'Should match using the actual filename addon-plugin.php' );
	}

	/**
	 * Test version comparison operators work correctly with mocked plugins.
	 *
	 * @test
	 */
	public function it_should_handle_version_comparison_operators_with_mocked_plugins() {
		// Mock get_plugins to return our test data.
		$this->mock_installed_plugins();

		// Mock is_plugin_active for all plugins.
		$this->mock_is_plugin_active( true );

		// Test >= operator with high version (events-widgets is 10.10.10).
		$plugins = [ 'events-widgets@>=10.0.0' ];
		$matches = Conditionals::check_plugin_version( $plugins );
		$this->assertTrue( $matches, 'Should match events-widgets >= 10.0.0' );

		// Test <= operator with events-pro (version is 6.5.0).
		$plugins = [ 'events-pro@<=7.0.0' ];
		$matches = Conditionals::check_plugin_version( $plugins );
		$this->assertTrue( $matches, 'Should match events-pro <= 7.0.0' );

		// Test > operator failure.
		$plugins = [ 'calendar-tickets-plus@>1.0.0' ];
		$matches = Conditionals::check_plugin_version( $plugins );
		$this->assertFalse( $matches, 'Should not match calendar-tickets-plus > 1.0.0 (installed is 1.0.0)' );

		// Test < operator success.
		$plugins = [ 'calendar-tickets-plus@<2.0.0' ];
		$matches = Conditionals::check_plugin_version( $plugins );
		$this->assertTrue( $matches, 'Should match calendar-tickets-plus < 2.0.0' );

		// Test = operator (exact match).
		$plugins = [ 'addon-plugin@=2.5.0' ];
		$matches = Conditionals::check_plugin_version( $plugins );
		$this->assertTrue( $matches, 'Should match addon-plugin = 2.5.0' );
	}

	/**
	 * @test
	 */
	public function it_should_return_the_full_feed() {
		$feed     = $this->get_mocked_feed();
		$filtered = Conditionals::filter_feed( $feed );
		$this->assertIsArray( $filtered, 'Filtered feed should be an array' );
		$this->assertCount( 3, $filtered, 'Filtered feed should have three items' );
	}

	/**
	 * @test
	 */
	public function it_should_hide_notification_for_unmet_php_version() {
		$feed = $this->get_mocked_feed();

		// Add a condition that will never be met.
		$feed[0]['conditions'] = [
			'php_version<=1.8',
		];

		$filtered = Conditionals::filter_feed( $feed );
		$this->assertCount( 2, $filtered, 'Filtered feed should have two items' );
	}

	/**
	 * @test
	 */
	public function it_should_hide_notification_for_unmet_wp_version() {
		$feed = $this->get_mocked_feed();

		// Add conditions that will never be met.
		$feed[0]['conditions'] = [
			'php_version<=1.8',
		];

		$feed[1]['conditions'] = [
			'wp_version<=4',
		];

		$filtered = Conditionals::filter_feed( $feed );
		$this->assertCount( 1, $filtered, 'Filtered feed should have one item' );
	}

	/**
	 * @test
	 */
	public function it_should_hide_notification_for_unmet_plugin_version() {
		$feed = $this->get_mocked_feed();

		// Add conditions that will never be met.
		$feed[0]['conditions'] = [
			'plugin_version:events-calendar-pro@<=1.0.0',
		];

		$feed[1]['conditions'] = [
			'plugin_version:woocommerce@<=2.0.0',
		];

		$filtered = Conditionals::filter_feed( $feed );
		$this->assertCount( 1, $filtered, 'Filtered feed should have one item' );
	}

	/**
	 * @test
	 */
	public function it_should_optin_with_ajax() {
		$this->ajax_setup();
		tribe_update_option( $this->optin_key, false );

		$optin = tribe_is_truthy( tribe_get_option( $this->optin_key ) );
		$this->assertFalse( $optin, 'User has not accepted notifications yet' );

		$wp_send_json_success = $this->mock_wp_send_json_success();

		do_action( $this->actions['optin'] );

		$this->assertTrue( $wp_send_json_success->was_called(), 'wp_send_json_success should be called' );
		$this->assertTrue( $wp_send_json_success->was_verified(), 'wp_send_json_success should be verified' );

		$response = $wp_send_json_success->get_calls()[0][0];
		$this->assertEquals( 'Notifications opt-in successful', $response, 'Response should be "Notifications opt-in successful"' );

		$status = $wp_send_json_success->get_calls()[0][1];
		$this->assertEquals( 200, $status, 'Status should be 200' );

		$optin = tribe_is_truthy( tribe_get_option( $this->optin_key ) );
		$this->assertTrue( $optin, 'User has accepted notifications' );

		$this->reset_wp_send_json_mocks();
	}

	/**
	 * @test
	 */
	public function it_should_get_cached_feed_via_ajax() {
		$this->ajax_setup();

		$_REQUEST['plugin'] = 'tec';

		$feed  = $this->get_mocked_feed();
		$cache = tribe_cache();
		$cache->set_transient( 'tec_ian_api_feed_tec', $feed, 15 * MINUTE_IN_SECONDS );

		$wp_send_json_success = $this->mock_wp_send_json_success();

		do_action( $this->actions['get_feed'] );

		$this->assertTrue( $wp_send_json_success->was_called(), 'wp_send_json_success should be called' );
		$this->assertTrue( $wp_send_json_success->was_verified(), 'wp_send_json_success should be verified' );

		$status = $wp_send_json_success->get_calls()[0][1];
		$this->assertEquals( 200, $status, 'Status should be 200' );

		$response = $wp_send_json_success->get_calls()[0][0];
		$this->assertCount( count( $feed ), $response, 'Response should be the feed' );

		$this->reset_wp_send_json_mocks();
	}

	/**
	 * @test
	 */
	public function it_should_dismiss_notification() {
		$this->ajax_setup();

		$user_dismissed = get_user_meta( get_current_user_id(), $this->meta_key );
		$this->assertEmpty( $user_dismissed, 'User should not have dismissed any notifications yet' );

		$feed = $this->get_mocked_feed();
		$slug = $feed[0]['slug'];
		$id   = $feed[0]['id'];

		$this->slug = $slug;
		$this->assertFalse( $this->has_user_dismissed(), 'Dismissible trait should show user has not dismissed this' );

		$this->set_fn_return( 'wp_create_nonce', $this->nonce_prefix . $id );

		$_REQUEST['slug'] = $slug;
		$_REQUEST['id']   = $id;

		$wp_send_json_success = $this->mock_wp_send_json_success();

		do_action( $this->actions['dismiss'] );

		$this->assertTrue( $wp_send_json_success->was_called(), 'wp_send_json_success should be called' );
		$this->assertTrue( $wp_send_json_success->was_verified(), 'wp_send_json_success should be verified' );

		$response = $wp_send_json_success->get_calls()[0][0];
		$this->assertEquals( 'Notification dismissed', $response, 'Response should be "Notification dismissed"' );

		$status = $wp_send_json_success->get_calls()[0][1];
		$this->assertEquals( 200, $status, 'Status should be 200' );

		$user_dismissed = get_user_meta( get_current_user_id(), $this->meta_key );
		$this->assertContains( $slug, $user_dismissed, 'User meta should contain the notification slug as read' );

		$this->assertTrue( $this->has_user_dismissed(), 'Dismissible trait should show user has read the notification' );

		$this->reset_wp_send_json_mocks();
	}

	/**
	 * @test
	 */
	public function it_should_mark_notification_as_read() {
		$this->ajax_setup();

		$user_has_read = get_user_meta( get_current_user_id(), $this->read_meta_key );
		$this->assertEmpty( $user_has_read, 'User should not have read any notifications yet' );

		$feed = $this->get_mocked_feed();
		$slug = $feed[0]['slug'];
		$id   = $feed[0]['id'];

		$this->slug = $slug;
		$this->assertFalse( $this->has_user_read(), 'Readable trait should show user has not read the notification yet' );

		$this->set_fn_return( 'wp_create_nonce', $this->nonce_prefix . $id );

		$_REQUEST['slug'] = $slug;
		$_REQUEST['id']   = $id;

		$wp_send_json_success = $this->mock_wp_send_json_success();

		do_action( $this->actions['read'] );

		$this->assertTrue( $wp_send_json_success->was_called(), 'wp_send_json_success should be called' );
		$this->assertTrue( $wp_send_json_success->was_verified(), 'wp_send_json_success should be verified' );

		$response = $wp_send_json_success->get_calls()[0][0];
		$this->assertEquals( 'Notification marked as read', $response, 'Response should be "Notification marked as read"' );

		$status = $wp_send_json_success->get_calls()[0][1];
		$this->assertEquals( 200, $status, 'Status should be 200' );

		$user_has_read = get_user_meta( get_current_user_id(), $this->read_meta_key );
		$this->assertContains( $slug, $user_has_read, 'User meta should contain the notification slug as read' );

		$this->assertTrue( $this->has_user_read(), 'Readable trait should show user has read the notification' );

		$this->reset_wp_send_json_mocks();
	}

	/**
	 * @test
	 */
	public function it_should_mark_all_notifications_as_read() {
		$this->ajax_setup();

		$user_has_read = get_user_meta( get_current_user_id(), $this->read_meta_key );
		$this->assertEmpty( $user_has_read, 'User should not have read any notifications yet' );

		$feed = $this->get_mocked_feed();

		$slugs = array_map(
			function ( $item ) {
				return $item['slug'];
			},
			$feed
		);

		$_REQUEST['unread'] = wp_json_encode( $slugs );

		$wp_send_json_success = $this->mock_wp_send_json_success();

		do_action( $this->actions['read_all'] );

		$this->assertTrue( $wp_send_json_success->was_called(), 'wp_send_json_success should be called' );
		$this->assertTrue( $wp_send_json_success->was_verified(), 'wp_send_json_success should be verified' );

		$response = $wp_send_json_success->get_calls()[0][0];
		$this->assertEquals( 'All notifications marked as read', $response, 'Response should be "All notifications marked as read"' );

		$status = $wp_send_json_success->get_calls()[0][1];
		$this->assertEquals( 200, $status, 'Status should be 200' );

		$user_has_read = get_user_meta( get_current_user_id(), $this->read_meta_key );
		$this->assertCount( count( $feed ), $user_has_read, 'User meta should contain all notification slugs as read' );

		$this->reset_wp_send_json_mocks();
	}
}
