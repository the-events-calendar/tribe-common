<?php

namespace Tribe\Repository;

use Tribe__Repository__Query_Filters as Query_Filters;
use Tribe__Repository__Read as Read_Repository;

class ReadTest extends \Codeception\TestCase\WPTestCase {
	protected $schema = [];
	protected $query_filters;
	protected $default_args = [ 'post_type' => 'book', 'orderby' => 'ID', 'order' => 'ASC' ];

	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->repository();

		$this->assertInstanceOf( Read_Repository::class, $sut );
	}

	/**
	 * @return Read_Repository
	 */
	private function repository() {
		if ( null === $this->query_filters ) {
			$this->query_filters = new Query_Filters();
		}

		return new Read_Repository( $this->schema, $this->query_filters, $this->default_args );
	}

	/**
	 * It should return all posts (non paginated) by default
	 *
	 * @test
	 */
	public function should_return_all_posts_by_default() {
		$ids = $this->factory()->post->create_many( 5, [ 'post_type' => 'book' ] );
		update_option( 'posts_per_page', 2 );

		$this->assertEquals( 5, $this->repository()->found() );
		$this->assertEquals( 5, $this->repository()->count() );
		$this->assertCount( 5, $this->repository()->all() );
		$this->assertEquals( reset( $ids ), $this->repository()->first()->ID );
		$this->assertEquals( end( $ids ), $this->repository()->last()->ID );
		$this->assertEquals( $ids[1], $this->repository()->nth( 2 )->ID );
		$this->assertEquals( $ids[2], $this->repository()->nth( 3 )->ID );
		$this->assertNull( $this->repository()->nth( 23 ) );
	}

	/**
	 * It should allow offsetting the results
	 *
	 * @test
	 */
	public function should_allow_offsetting_the_results() {
		$ids = $this->factory()->post->create_many( 5, [ 'post_type' => 'book' ] );
		update_option( 'posts_per_page', 2 );

		$this->assertEquals( 5, $this->repository()->offset( 2 )->found() );
		$this->assertEquals( 3, $this->repository()->offset( 2 )->count() );
		$this->assertCount( 3, $this->repository()->offset( 2 )->all() );
		$this->assertEquals( $ids[2], $this->repository()->offset( 2 )->first()->ID );
		$this->assertEquals( end( $ids ), $this->repository()->offset( 2 )->last()->ID );
		$this->assertEquals( $ids[3], $this->repository()->offset( 2 )->nth( 2 )->ID );
		$this->assertEquals( $ids[4], $this->repository()->offset( 2 )->nth( 3 )->ID );
		$this->assertNull( $this->repository()->nth( 23 ) );
	}

	/**
	 * It should allow paginating results
	 *
	 * @test
	 */
	public function should_allow_paginating_results() {
		$ids = $this->factory()->post->create_many( 5, [ 'post_type' => 'book' ] );
		update_option( 'posts_per_page', 2 );

		$page_1 = $this->repository()t 
		                   ->per_page(3)
		                   ->page(1);

		$this->assertEquals( 5, $page_1->found() );
		$this->assertEquals( 3, $page_1->count() );
		$this->assertCount( 3, $page_1->all() );
		$this->assertEquals( $ids[0], $page_1->first()->ID );
		$this->assertEquals( $ids[2], $page_1->last()->ID );
		$this->assertEquals( $ids[0], $page_1->nth( 1 )->ID );
		$this->assertEquals( $ids[1], $page_1->nth( 2 )->ID );
		$this->assertEquals( $ids[2], $page_1->nth( 3 )->ID );
		$this->assertNull( $page_1->nth( 4 ) );

		$page_2 = $this->repository()
		               ->per_page( 3 )
		               ->page( 2 );

		$this->assertEquals( 5, $page_2->found() );
		$this->assertEquals( 2, $page_2->count() );
		$this->assertCount( 2, $page_2->all() );
		$this->assertEquals( $ids[3], $page_2->first()->ID );
		$this->assertEquals( $ids[4], $page_2->last()->ID );
		$this->assertEquals( $ids[3], $page_2->nth( 1 )->ID );
		$this->assertEquals( $ids[4], $page_2->nth( 2 )->ID );
		$this->assertNull( $page_2->nth( 3 ) );
	}

	protected function _before() {
		parent::_before();
		register_post_type( 'book' );
	}
}