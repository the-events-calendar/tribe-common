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
				'orderby' => 'post_date',
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
				'orderby' => 'post_date',
			],
			$id_filters
		);
	}
}
