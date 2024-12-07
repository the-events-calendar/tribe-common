<?php

namespace TEC\Common\Notifications;

use Codeception\TestCase\WPTestCase;
use Tribe\Tests\Traits\With_Uopz;
use Tribe\Tests\Traits\WP_Send_Json_Mocks;
use TEC\Common\Notifications\Readable_Trait;
use TEC\Common\Admin\Conditional_Content\Dismissible_Trait;

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
	use Dismissible_Trait;

	protected $ian_optin_key = 'ian-notifications-opt-in';

	/**
	 * @before
	 */
	public function init_notifications() {
		tribe_update_option( $this->ian_optin_key, true );
	}

	/**
	 * @after
	 */
	public function deinit_notifications() {
		delete_option( $this->ian_optin_key );
	}

	private function get_mocked_feed() {
		return [
			[
				'id'          => '101',
				'type'        => 'update',
				'slug'        => 'tec-update-664',
				'title'       => 'The Events Calendar 6.6.4 Update',
				'html'        => '<p>The latest update of The Events Calendar adds an option to allow for duplicate Venue creation, updates custom table query logic to avoid DB error, and logic that displays the “REST API blocked” banner to prevent false positives.</p>',
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
				'conditions'  => [ 'plugin_version:the-events-calendar@>=5.0.0' ],
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
	private function ajax_setup( int $user_id = null ) {
		if ( null === $user_id ) {
			$user_id = self::factory()->user->create( [ 'role' => 'administrator' ] );
			wp_set_current_user( $user_id );
		}

		$this->set_fn_return( 'wp_create_nonce', 'common_ian_nonce' );
		$this->set_fn_return( 'check_ajax_referer', true );
		$this->set_fn_return( 'wp_doing_ajax', true );
		$this->set_fn_return( 'wp_verify_nonce', true );
	}

	/**
	 * @test
	 */
	public function it_should_return_true_for_opt_in() {
		$optin = Conditionals::get_opt_in();
		$this->assertTrue( $optin, 'Opt-in check should be true' );
	}

	/**
	 * @test
	 */
	public function it_should_return_false_for_opt_out() {
		tribe_update_option( $this->ian_optin_key, false );
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
		$plugins = [ 'the-events-calendar@>=6.0.0' ];
		$matches = Conditionals::check_plugin_version( $plugins );
		$this->assertTrue( $matches, 'Plugin requirement should be met' );
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
	public function it_should_dismiss_notification() {
		$this->ajax_setup();

		$user_dismissed = get_user_meta( get_current_user_id(), $this->meta_key );
		$this->assertEmpty( $user_dismissed, 'User should not have dismissed any notifications yet' );

		$feed = $this->get_mocked_feed();
		$slug = $feed[0]['slug'];
		$id   = $feed[0]['id'];

		$this->slug = $slug;
		$this->assertFalse( $this->has_user_dismissed(), 'Dismissible trait should show user has not dismissed this' );

		$this->set_fn_return( 'wp_create_nonce', 'ian_nonce_' . $id );

		$_REQUEST['slug'] = $slug;
		$_REQUEST['id']   = $id;

		$wp_send_json_success = $this->mock_wp_send_json_success();

		do_action( 'wp_ajax_ian_dismiss' );

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

		$this->set_fn_return( 'wp_create_nonce', 'ian_nonce_' . $id );

		$_REQUEST['slug'] = $slug;
		$_REQUEST['id']   = $id;

		$wp_send_json_success = $this->mock_wp_send_json_success();

		do_action( 'wp_ajax_ian_read' );

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

		do_action( 'wp_ajax_ian_read_all' );

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

	/**
	 * @test
	 */
	public function it_should_get_cached_feed_via_ajax() {
		$this->ajax_setup();

		$feed  = $this->get_mocked_feed();
		$cache = tribe_cache();
		$cache->set_transient( 'tec_ian_api_feed', $feed, 15 * MINUTE_IN_SECONDS );

		$wp_send_json_success = $this->mock_wp_send_json_success();

		do_action( 'wp_ajax_ian_get_feed' );

		$this->assertTrue( $wp_send_json_success->was_called(), 'wp_send_json_success should be called' );
		$this->assertTrue( $wp_send_json_success->was_verified(), 'wp_send_json_success should be verified' );

		$status = $wp_send_json_success->get_calls()[0][1];
		$this->assertEquals( 200, $status, 'Status should be 200' );

		$response = $wp_send_json_success->get_calls()[0][0];
		$this->assertCount( count( $feed ), $response, 'Response should be the feed' );

		$this->reset_wp_send_json_mocks();
	}
}
