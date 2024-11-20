<?php

namespace TEC\Common\Notifications;

use Tribe\Tests\Traits\With_Uopz;

/**
 * Class NotificationsTest
 *
 * @since TBD
 *
 * @package TEC\Common\Notifications
 */
class NotificationsTest extends \Codeception\TestCase\WPTestCase {
	use With_Uopz;

	protected $ian_optin_key = 'ian-notifications-opt-in';

	protected $ian_api_url = 'https://ian.stellarwp.com/feed/stellar/tec/plugins.json';

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

	private function get_known_feed() {
		$feed = [
			[
				'id'          => '101',
				'type'        => 'update',
				'slug'        => 'tec-update-664',
				'title'       => 'The Events Calendar 6.6.4 Update',
				'content'     => '<p>The latest update of The Events Calendar adds an option to allow for duplicate Venue creation, updates custom table query logic to avoid DB error, and logic that displays the “REST API blocked” banner to prevent false positives.</p>',
				'actions'     => [
					[
						'text'   => 'See Details',
						'link'   => 'https://evnt.is/1ai-',
						'target' => '_blank',
					],
					[
						'text'   => 'Update Now',
						'link'   => '/wp-admin/update-core.php',
						'target' => '_self',
					],
				],
				'dismissible' => true,
			],
			[
				'id'          => '102',
				'type'        => 'notice',
				'slug'        => 'event-tickets-upsell',
				'title'       => 'Sell Tickets & Collect RSVPs with Event Tickets',
				'content'     => '<p>Sell tickets, collect RSVPs and manage attendees for free.</p>',
				'actions'     => [
					[
						'text'   => 'Learn More',
						'link'   => 'https://evnt.is/1aj1',
						'target' => '_blank',
					],
				],
				'dismissible' => true,
			],
			[
				'id'          => '103',
				'type'        => 'warning',
				'slug'        => 'fbar-upgrade-556',
				'title'       => 'Filter Bar 5.5.6 Security Update',
				'content'     => '<p>Get the latest version of Filter Bar for important security updates.</p>',
				'actions'     => [
					[
						'text'   => 'Update',
						'link'   => '/wp-admin/plugins.php?plugin_status=upgrade',
						'target' => '_self',
					],
				],
				'dismissible' => false,
			],
		];

		return $feed;
	}

	/**
	 * Setup AJAX Test.
	 */
	private function ajax_setup() {
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
	public function it_should_get_a_response_from_the_api() {
		$response = wp_remote_get( $this->ian_api_url );
		$this->assertIsArray( $response, 'API should return a response' );
		$this->assertEquals( 200, wp_remote_retrieve_response_code( $response ), 'API should return a 200 response' );
	}

	/**
	 * @test
	 */
	public function it_should_get_a_feed_from_the_api() {
		$feed = wp_remote_retrieve_body( wp_remote_get( $this->ian_api_url ) );
		$this->assertIsString( $feed, 'API should return a feed' );

		$feed = json_decode( $feed );
		$this->assertIsObject( $feed, 'Feed should be a JSON object' );
	}

	/**
	 * @test
	 */
	public function it_should_get_a_feed_with_notifications() {
		$body = wp_remote_retrieve_body( wp_remote_get( $this->ian_api_url ) );
		$feed = json_decode( $body, true );
		$this->assertIsArray( $feed, 'Feed should be an array' );

		$notifications = $feed['notifications_by_area']['general-tec'];
		$this->assertIsArray( $notifications, 'Feed should have notifications' );

		$this->assertArrayHasKey( 'id', $notifications[0], 'Notification should have an ID' );
		$this->assertArrayHasKey( 'type', $notifications[0], 'Notification should have a type' );
		$this->assertArrayHasKey( 'slug', $notifications[0], 'Notification should have a slug' );
		$this->assertArrayHasKey( 'title', $notifications[0], 'Notification should have a title' );
		$this->assertArrayHasKey( 'html', $notifications[0], 'Notification should have content' );
	}
}
