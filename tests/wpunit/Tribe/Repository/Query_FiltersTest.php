<?php

namespace Tribe\Repository;

use Tribe__Repository__Query_Filters as Query_Filters;

class Query_FiltersTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * @return Query_Filters
	 */
	private function make_instance() {
		return new Query_Filters();
	}

	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->make_instance();

		$this->assertInstanceOf( Query_Filters::class, $sut );
	}

	/**
	 * It should allow adding filters in a group
	 *
	 * @test
	 */
	public function should_allow_adding_filters_with_an_id() {
		$filters = new Query_Filters();
		$filters->orderby( 'post_date', 'test' );
		$filters->fields( 'wp_posts.post_title as alternate_title', 'test' );
		$filters->join( 'left join wp_postmeta pm on pm.post_id = wp_posts.ID', 'test' );
		$filters->where( "pm.meta_key = 'baz'", 'test' );

		$id_filters = $filters->get_filters_by_id( 'test' );
		$this->assertEquals(
			[
				'fields'  => 'wp_posts.post_title as alternate_title',
				'join'    => 'left join wp_postmeta pm on pm.post_id = wp_posts.ID',
				'where'   => "(pm.meta_key = 'baz')",
				'orderby' => [ [ 'post_date', 'DESC' ] ],
			],
			$id_filters
		);
	}

	/**
	 * It should allow removing filters by id
	 *
	 * @test
	 */
	public function should_allow_removing_filters_by_id() {
		$filters = new Query_Filters();
		$filters->orderby( 'post_date', 'test' );
		$filters->fields( 'wp_posts.post_title as alternate_title', 'test' );
		$filters->join( 'left join wp_postmeta pm on pm.post_id = wp_posts.ID', 'test' );
		$filters->where( "pm.meta_key = 'baz'", 'test' );
		$filters->orderby( 'post_date', 'woot' );
		$filters->fields( 'wp_posts.post_title as alternate_title', 'woot' );
		$filters->join( 'left join wp_postmeta pm on pm.post_id = wp_posts.ID', 'woot' );
		$filters->where( "pm.meta_key = 'baz'", 'woot' );

		$filters->remove_filters_by_id( 'test' );

		$id_filters = $filters->get_filters_by_id( 'test' );
		$this->assertEquals( [], $id_filters );
		$id_filters = $filters->get_filters_by_id( 'woot' );
		$this->assertEquals(
			[
				'fields'  => 'wp_posts.post_title as alternate_title',
				'join'    => 'left join wp_postmeta pm on pm.post_id = wp_posts.ID',
				'where'   => "(pm.meta_key = 'baz')",
				'orderby' => [ [ 'post_date', 'DESC' ] ],
			],
			$id_filters
		);
	}

	public function orderby_input_set() {
		yield 'single string' => [
			'event_date',
			'event_date DESC, wp_posts.ID ASC',
		];

		yield 'array of strings' => [
			[ 'event_date', 'event_venue' ],
			'event_date DESC, event_venue DESC, wp_posts.ID ASC',
		];

		yield 'map of fields and orders' => [
			[ 'event_date' => 'ASC', 'event_venue' => 'DESC' ],
			'event_date ASC, event_venue DESC, wp_posts.ID ASC',
		];

		yield 'id map of fields and orders' => [
			[ 'event_date' => 'ASC', ],
			'event_date ASC, wp_posts.ID ASC',
			'order_by_date'
		];

		yield 'id map of fields and orders w/ 2 entries' => [
			[ 'event_date' => 'ASC', 'event_venue' => 'DESC' ],
			'event_date ASC, event_venue DESC, wp_posts.ID ASC',
			'order_by_multi'
		];

		yield 'id map of fields and orders w/ 2 entries, appending' => [
			[ 'event_date' => 'ASC', 'event_venue' => 'DESC' ],
			'wp_posts.ID ASC, event_date ASC, event_venue DESC',
			'order_by_multi',
			false,
			true
		];
	}

	/**
	 * It should correctly handle orderby in diff formats
	 *
	 * @test
	 * @dataProvider orderby_input_set
	 */
	public function should_correctly_handle_orderby_in_diff_formats(
		$orderby_input,
		$expected,
		$id = null,
		$override = false,
		$after = false
	) {
		$orderby_sql = 'wp_posts.ID ASC';
		$query       = new \WP_Query();

		$filters = new Query_Filters();
		$filters->set_query( $query );
		$filters->orderby( $orderby_input, $id, false, $after );
		$filtered = $filters->filter_posts_orderby( $orderby_sql, $query );

		$this->assertEquals( $expected, $filtered );
	}
}
