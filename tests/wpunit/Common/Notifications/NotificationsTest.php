<?php

namespace TEC\Common\Notifications;

use Tribe\Tests\Traits\With_Uopz;

/**
 * Class NotificationsTest
 *
 * @since 6.4.0
 *
 * @package TEC\Common\Notifications
 */
class NotificationsTest extends \Codeception\TestCase\WPTestCase {
	use With_Uopz;

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
		$feed = [
			[
				'id'          => '101',
				'type'        => 'update',
				'slug'        => 'tec-update-664',
				'title'       => 'The Events Calendar 6.6.4 Update',
				'html'        => '<p>The latest update of The Events Calendar adds an option to allow for duplicate Venue creation, updates custom table query logic to avoid DB error, and logic that displays the “REST API blocked” banner to prevent false positives.</p>',
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
						'link'   => 'https://evnt.is/1aj1',
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
						'link'   => '/wp-admin/plugins.php?plugin_status=upgrade',
						'target' => '_self',
					],
				],
				'dismissible' => false,
				'conditions'  => [],
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
		$feed = $this->get_mocked_feed();
		$filtered_feed = Conditionals::filter_feed( $feed );
		$this->assertIsArray( $filtered_feed, 'Filtered feed should be an array' );
		$this->assertCount( 3, $filtered_feed, 'Filtered feed should have three items' );
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

		$filtered_feed = Conditionals::filter_feed( $feed );
		$this->assertCount( 2, $filtered_feed, 'Filtered feed should have two items' );
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

		$filtered_feed = Conditionals::filter_feed( $feed );
		$this->assertCount( 1, $filtered_feed, 'Filtered feed should have one item' );
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

		$filtered_feed = Conditionals::filter_feed( $feed );
		$this->assertCount( 1, $filtered_feed, 'Filtered feed should have one item' );
	}
}
