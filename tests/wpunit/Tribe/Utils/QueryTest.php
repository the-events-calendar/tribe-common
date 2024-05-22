<?php

namespace Tribe\Utils;

class QueryTest extends \Codeception\TestCase\WPTestCase {

	public function test_for_posts_w_empty_posts() {
		$query = Query::for_posts( [] );

		$this->assertEquals( [], $query->posts );
		$this->assertFalse( $query->post );
		$this->assertEquals( 0, $query->found_posts );
		$this->assertEquals( [], $query->get_posts() );
	}

	public function test_for_posts_with_posts() {
		$posts = array_map( static function ()
		{
			return static::factory()->post->create_and_get();
		},
			range( 1, 3 ) );


		$query = Query::for_posts( $posts );

		$this->assertEquals( $posts, $query->posts );
		$this->assertEquals( reset( $posts ), $query->post );
		$this->assertEquals( 3, $query->found_posts );
		$this->assertEquals( $posts, $query->get_posts() );

		$query->set( 'fields', 'all' );
		$this->assertEquals( $posts, $query->get_posts() );

		$query->set( 'fields', 'ids' );
		$this->assertEquals( wp_list_pluck( $posts, 'ID' ), $query->get_posts() );

		$query->set( 'fields', 'id=>parent' );
		$expected = array_combine(
			wp_list_pluck( $posts, 'ID' ),
			wp_list_pluck( $posts, 'post_parent' )
		);
		$this->assertEquals( $expected, $query->get_posts() );
	}

	public function test_for_posts_w_ids() {
		$posts = array_map( static function ()
		{
			return static::factory()->post->create();
		},
			range( 1, 3 ) );


		$query = Query::for_posts( $posts );

		$this->assertEquals( $posts, $query->posts );
		$this->assertEquals( reset( $posts ), $query->post );
		$this->assertEquals( 3, $query->found_posts );
		$this->assertEquals( array_map( 'get_post', $posts ), $query->get_posts() );

		$query->set( 'fields', 'all' );
		$this->assertEquals( array_map( 'get_post', $posts ), $query->get_posts() );

		$query->set( 'fields', 'ids' );
		$this->assertEquals( $posts, $query->get_posts() );

		$query->set( 'fields', 'id=>parent' );
		$expected = array_combine(
			$posts,
			array_fill( 0, 3, 0 )
		);
		$this->assertEquals( $expected, $query->get_posts() );
	}
}
