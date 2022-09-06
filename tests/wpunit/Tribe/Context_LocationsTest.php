<?php

namespace Tribe;

/**
 * Class Context_LocationsTest
 *
 * @since   TBD
 *
 * @package Tribe
 */
class Context_LocationsTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * @test
	 */
	public function should_not_fail_on_wp_query_null() {
		global $wp_query;

		$wp_query = null;

		$this->assertFalse( tribe_context()->is( 'is_main_query' ), 'When WP_Query is `null` it should be false.' );
	}

	/**
	 * @test
	 */
	public function should_not_fail_on_wp_query_is_array() {
		global $wp_query;

		$wp_query = [ 'test' ];

		$this->assertFalse( tribe_context()->is( 'is_main_query' ), 'When WP_Query is `array` it should be false.' );
	}

	/**
	 * @test
	 */
	public function should_not_fail_on_wp_query_not_WP_Query_instance() {
		global $wp_query;

		$wp_query = (object) [];

		$this->assertFalse( tribe_context()->is( 'is_main_query' ), 'When WP_Query is `stdObj` it should be false.' );
	}
}