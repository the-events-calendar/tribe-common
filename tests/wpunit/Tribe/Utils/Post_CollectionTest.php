<?php

namespace Tribe\Utils;

use Tribe__Utils__Post_Collection as Collection;

class Post_CollectionTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * It should return plucked post fields when requested
	 *
	 * @test
	 */
	public function should_return_plucked_post_fields_when_requested() {
		$posts   = [];
		$posts[] = static::factory()->post->create( [ 'post_title' => 'Post 1' ] );
		$posts[] = static::factory()->post->create( [ 'post_title' => 'Post 2' ] );
		$posts[] = static::factory()->post->create( [ 'post_title' => 'Post 3' ] );

		$collection = new Collection( $posts );

		$titles = [ 'Post 1', 'Post 2', 'Post 3' ];
		$this->assertEquals( $titles, $collection->pluck( 'post_title' ) );
		$this->assertEquals( $titles, $collection->pluck_field( 'post_title' ) );
	}

	/**
	 * It should return plucked custom fields when requested
	 *
	 * @test
	 */
	public function should_return_plucked_custom_fields_when_requested() {
		$posts   = [];
		$posts[] = static::factory()->post->create( [ 'meta_input' => [ '_test' => 'Post 1' ] ] );
		$posts[] = static::factory()->post->create( [ 'meta_input' => [ '_test' => 'Post 2' ] ] );
		$posts[] = static::factory()->post->create( [ 'meta_input' => [ '_test' => 'Post 3' ] ] );

		$collection = new Collection( $posts );

		$titles = [ 'Post 1', 'Post 2', 'Post 3' ];
		$this->assertEquals( $titles, $collection->pluck( '_test' ) );
		$this->assertEquals( $titles, $collection->pluck_meta( '_test' ) );
	}

	/**
	 * It should return list of plucked custom fields when requested
	 *
	 * @test
	 */
	public function should_return_list_of_plucked_custom_fields_when_requested() {
		$posts   = [];
		$posts[] = static::factory()->post->create();
		$posts[] = static::factory()->post->create();
		$posts[] = static::factory()->post->create();
		// The factory would add `meta_input` arrays as serialized arrays, use this method to add them as multiple rows.
		foreach ( $posts as $id ) {
			add_post_meta( $id, '_multi_test', 'one' );
			add_post_meta( $id, '_multi_test', 'two' );
			add_post_meta( $id, '_multi_test', 'three' );
		}

		$collection = new Collection( $posts );

		$expected_single = ( [ 'one', 'one', 'one' ] );
		$expected_multi  = ( array_fill( 0, 3, [ 'one', 'two', 'three' ] ) );
		$this->assertEquals( $expected_single, $collection->pluck( '_multi_test', true ) );
		$this->assertEquals( $expected_single, $collection->pluck_meta( '_multi_test', true ) );
		$this->assertEquals( $expected_multi, $collection->pluck( '_multi_test', false ) );
		$this->assertEquals( $expected_multi, $collection->pluck_meta( '_multi_test', false ) );
	}

	/**
	 * It should return plucked taxonomy terms
	 *
	 * @test
	 */
	public function should_return_plucked_taxonomy_terms() {
		// Become an administrator to add taxonomy terms during post insertion.
		wp_set_current_user( static::factory()->user->create( [ 'role' => 'administrator' ] ) );
		$posts   = [];
		$posts[] = static::factory()->post->create();
		$posts[] = static::factory()->post->create();
		$posts[] = static::factory()->post->create();
		// The `tax_input` creation argument works in tricky ways, avoid it completely and use explict assignment.
		foreach ( $posts as $id ) {
			foreach (
				[
					'post_tag' => [ 'tag1', 'tag2', 'tag3' ],
					'category' => [ 'cat1', 'cat2', 'meow' ]
				] as $tax => $terms
			) {
				wp_set_object_terms( $id, $terms, $tax );
			}
		}

		$collection = new Collection( $posts );

		$args                = [ 'fields' => 'names' ];
		$expected_single_tag = array_fill( 0, 3, 'tag1' );
		$expected_multi_tag  = array_fill( 0, 3, [ 'tag1', 'tag2', 'tag3' ] );
		$expected_single_cat = array_fill( 0, 3, 'cat1' );
		$expected_multi_cat  = array_fill( 0, 3, [ 'cat1', 'cat2', 'meow' ] );
		$this->assertEquals( $expected_single_tag, $collection->pluck( 'post_tag', true ) );
		$this->assertEquals( $expected_single_tag, $collection->pluck_taxonomy( 'post_tag', true ) );
		$this->assertEquals( $expected_multi_tag, $collection->pluck( 'post_tag', false, $args ) );
		$this->assertEquals( $expected_multi_tag, $collection->pluck_taxonomy( 'post_tag', false, $args ) );
		$this->assertEquals( $expected_single_cat, $collection->pluck( 'category', true ) );
		$this->assertEquals( $expected_single_cat, $collection->pluck_taxonomy( 'category', true ) );
		$this->assertEquals( $expected_multi_cat, $collection->pluck( 'category', false, $args ) );
		$this->assertEquals( $expected_multi_cat, $collection->pluck_taxonomy( 'category', false, $args ) );
	}

	/**
	 * It should allow to pluck combine
	 *
	 * @test
	 */
	public function should_allow_to_pluck_combine() {
		// Become an administrator to add taxonomy terms during post insertion.
		wp_set_current_user( static::factory()->user->create( [ 'role' => 'administrator' ] ) );
		$posts   = [];
		$posts[] = static::factory()->post->create( [ 'post_title' => 'Post 1' ] );
		$posts[] = static::factory()->post->create( [ 'post_title' => 'Post 2' ] );
		$posts[] = static::factory()->post->create( [ 'post_title' => 'Post 3' ] );
		// The `tax_input` creation argument works in tricky ways, avoid it completely and use explict assignment.
		foreach ( $posts as $id ) {
			foreach (
				[
					'post_tag' => [ 'tag1', 'tag2', 'tag3' ],
					'category' => [ 'cat1', 'cat2', 'meow' ]
				] as $tax => $terms
			) {
				wp_set_object_terms( $id, $terms, $tax );
			}
		}

		$collection = new Collection( $posts );

		$this->assertEquals(
			array_combine( $posts, array_fill( 0, 3, 'tag1' ) ),
			$collection->pluck_combine( 'ID', 'post_tag' )
		);

		$this->assertEquals(
			[
				$posts[0] => [ 'post_title' => 'Post 1', 'post_tag' => 'tag1' ],
				$posts[1] => [ 'post_title' => 'Post 2', 'post_tag' => 'tag1' ],
				$posts[2] => [ 'post_title' => 'Post 3', 'post_tag' => 'tag1' ],
			],
			$collection->pluck_combine( 'ID', [ 'post_title', 'post_tag' ] )
		);

		$this->assertEquals(
			[
				$posts[0] => [
					'post_title' => 'Post 1',
					'post_tag'   => 'tag1',
					'category'   => [ 'cat1', 'cat2', 'meow' ]
				],
				$posts[1] => [
					'post_title' => 'Post 2',
					'post_tag'   => 'tag1',
					'category'   => [ 'cat1', 'cat2', 'meow' ]
				],
				$posts[2] => [
					'post_title' => 'Post 3',
					'post_tag'   => 'tag1',
					'category'   => [ 'cat1', 'cat2', 'meow' ]
				],
			],
			$collection->pluck_combine(
				'ID',
				[ 'post_title', 'post_tag', 'category' => [ 'single' => false, 'args' => [ 'fields' => 'names' ] ] ]
			)
		);

		$this->assertEquals(
			[
				$posts[0] => [
					'title' => 'Post 1',
					'tag'   => 'tag1',
					'category'   => [ 'cat1', 'cat2', 'meow' ]
				],
				$posts[1] => [
					'title' => 'Post 2',
					'tag'   => 'tag1',
					'category'   => [ 'cat1', 'cat2', 'meow' ]
				],
				$posts[2] => [
					'title' => 'Post 3',
					'tag'   => 'tag1',
					'category'   => [ 'cat1', 'cat2', 'meow' ]
				],
			],
			$collection->pluck_combine(
				'ID',
				[
					'post_title' => [ 'as' => 'title' ],
					'post_tag'   => [ 'as' => 'tag' ],
					'category'   => [ 'single' => false, 'args' => [ 'fields' => 'names' ] ]
				]
			)
		);
	}

	/**
	 * It should allow to pluck_combine one field w/ args
	 *
	 * @test
	 */
	public function should_allow_to_pluck_combine_one_field_w_args() {
		$posts   = [];
		$posts[] = static::factory()->post->create( [ 'post_title' => 'Post 1' ] );
		$posts[] = static::factory()->post->create( [ 'post_title' => 'Post 2' ] );
		$posts[] = static::factory()->post->create( [ 'post_title' => 'Post 3' ] );

		$collection = new Collection( $posts );
		$result     = $collection->pluck_combine( 'ID', [ 'post_title' => [ 'as' => 'title' ] ] );

		$expected =
			[
				$posts[0] => [ 'title' => 'Post 1' ],
				$posts[1] => [ 'title' => 'Post 2' ],
				$posts[2] => [ 'title' => 'Post 3' ],
			];
		$this->assertEquals( $expected, $result );
	}

	/**
	 * It should allow to alias results with flat map
	 *
	 * @test
	 */
	public function should_allow_to_alias_results_with_flat_map() {
		$posts   = [];
		$posts[] = static::factory()->post->create( [ 'post_title' => 'Post 1' ] );
		$posts[] = static::factory()->post->create( [ 'post_title' => 'Post 2' ] );
		$posts[] = static::factory()->post->create( [ 'post_title' => 'Post 3' ] );

		$collection = new Collection( $posts );
		$result     = $collection->pluck_combine( 'ID', [ 'post_title' => 'title' ] );

		$expected =
			[
				$posts[0] => [ 'title' => 'Post 1' ],
				$posts[1] => [ 'title' => 'Post 2' ],
				$posts[2] => [ 'title' => 'Post 3' ],
			];
		$this->assertEquals( $expected, $result );
	}

	/**
	 * It should allow to alias multi with a flat map
	 *
	 * @test
	 */
	public function should_allow_to_alias_multi_with_a_flat_map() {
		$posts   = [];
		$posts[] = static::factory()->post->create( [ 'post_title' => 'Post 1', 'post_status' => 'private' ] );
		$posts[] = static::factory()->post->create( [ 'post_title' => 'Post 2', 'post_status' => 'draft' ] );
		$posts[] = static::factory()->post->create( [ 'post_title' => 'Post 3', 'post_status' => 'publish' ] );

		$collection = new Collection( $posts );
		$result     = $collection->pluck_combine( 'ID', [
			'post_title'  => 'title',
			'post_status' => 'status',
		] );

		$expected =
			[
				$posts[0] => [ 'title' => 'Post 1', 'status' => 'private' ],
				$posts[1] => [ 'title' => 'Post 2', 'status' => 'draft' ],
				$posts[2] => [ 'title' => 'Post 3', 'status' => 'publish' ],
			];
		$this->assertEquals( $expected, $result );
	}
}
