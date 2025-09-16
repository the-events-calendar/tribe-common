<?php

class NoticesTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * Take the Admin Notice instance and verify the notice registered is memoized in the Cache.
	 *
	 * @test
	 */
	public function should_memoize_notice() {
		// Verify the notice is not memoized in the Cache.
		$cache     = tribe( 'cache' );
		$cache_key = 'transient_admin_notices';
		$this->assertEmpty( $cache[ $cache_key ] );

		// Register a notice.
		$notice       = Tribe__Admin__Notices::instance();
		$message      = '<p>test</p>';
		$message_slug = 'test';
		$notice->register_transient( $message_slug, $message );

		// The memoized notice should be in the Cache.
		$this->assertEquals( $message, $cache[ $cache_key ][ $message_slug ][0] );
	}

	/**
	 * Take the Admin Notice instance and verify the notice cache busts.
	 *
	 * @test
	 */
	public function should_clear_memoize_notice() {
		// Verify the notice is not memoized in the Cache.
		$cache     = tribe( 'cache' );
		$cache_key = 'transient_admin_notices';
		$this->assertEmpty( $cache[ $cache_key ] );

		// Register a notice.
		$notice       = Tribe__Admin__Notices::instance();
		$message      = '<p>test</p>';
		$message_slug = 'test';
		$notice->register_transient( $message_slug, $message );
		$notice->remove_transient( $message_slug );

		// The memoized notice should not be in the Cache.
		$this->assertFalse( isset( $cache[ $cache_key ][ $message_slug ] ) );
	}

	/**
	 * Take the Admin Notice instance and verify the notice updates.
	 *
	 * @test
	 */
	public function should_update_memoize_notice() {
		// Verify the notice is not memoized in the Cache.
		$cache     = tribe( 'cache' );
		$cache_key = 'transient_admin_notices';
		$this->assertEmpty( $cache[ $cache_key ] );

		// Register a notice.
		$notice       = Tribe__Admin__Notices::instance();
		$message      = '<p>test</p>';
		$message_slug = 'test';
		$notice->register_transient( $message_slug, $message );

		// Register another notice.
		$message2      = '<p>test 2</p>';
		$message_slug2 = 'test 2';
		$notice->register_transient( $message_slug2, $message2 );

		// The Notice store should be updated with both.
		$this->assertTrue( $notice->showing_transient_notice( $message_slug ) );
		$this->assertTrue( $notice->showing_transient_notice( $message_slug2 ) );
		$this->assertFalse( $notice->showing_transient_notice( 'nonsense-faux-slug' ) );
	}
}
