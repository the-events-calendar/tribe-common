<?php

namespace Tribe\Repository;

use Tribe__Promise as Promise;

class DeleteTest extends \Codeception\TestCase\WPTestCase {
	protected $class;

	public function setUp() {
		parent::setUp();
		register_post_type( 'book' );
		register_taxonomy( 'genre', 'book' );
		$this->class = new class extends \Tribe__Repository {
			protected $filter_name = 'books';
			protected $default_args = [ 'post_type' => 'book', 'orderby' => 'ID', 'order' => 'ASC' ];
		};
	}

	/**
	 * Validate the pre check hook will bypass the normal delete() operations.
	 *
	 * @test
	 */
	public function should_run_pre_check_filter() {
		list( $john ) = $this->create_books_by_authors();

		// Add hooks to track pre check works.
		add_filter( "tribe_repository_books_before_delete", function () {
			return [];
		} );
		$delete_filter_ran = false;
		add_filter( "tribe_repository_books_delete", function () use ( &$delete_filter_ran ) {
			$delete_filter_ran = true;
		} );

		// Run delete and verify hooks run.
		$deleted = $this->repository()->where( 'author', $john )->delete();

		$this->assertFalse( $delete_filter_ran );
		$this->assertEquals( [], $deleted );
	}

	/**
	 * It should allow deleting a set of posts
	 *
	 * @test
	 */
	public function should_allow_deleting_a_set_of_posts() {
		list( $john, $from_john ) = $this->create_books_by_authors();

		$deleted = $this->repository()->where( 'author', $john )->delete();

		$this->assertEqualSets( $from_john, $deleted );
	}

	protected function create_books_by_authors(): array {
		$john      = $this->factory()->user->create( [ 'user_login' => 'john', 'role' => 'editor' ] );
		$jane      = $this->factory()->user->create( [ 'user_login' => 'jane', 'role' => 'editor' ] );
		$from_john = array_map( function () use ( $john ) {
			return $this->factory()->post->create( [ 'post_type' => 'book', 'post_author' => $john ] );
		}, range( 1, 3 ) );
		$from_jane = array_map( function () use ( $jane ) {
			return $this->factory()->post->create( [ 'post_type' => 'book', 'post_author' => $jane ] );
		}, range( 1, 3 ) );

		return array( $john, $from_john, $jane, $from_jane );
	}

	/**
	 * @return Read_Repository
	 */
	protected function repository() {
		return new $this->class();
	}

	/**
	 * It should not delete anything if there is nothing to delete
	 *
	 * @test
	 */
	public function should_not_delete_anything_if_there_is_nothing_to_delete() {
		list( $john, $from_john, $jane, $from_jane ) = $this->create_books_by_authors();

		$deleted = $this->repository()->where( 'author', 23423 )->delete();

		$this->assertCount( 0, $deleted );
		$this->assertEqualSets( array_merge( $from_john, $from_jane ), get_posts( [
			'fields'         => 'ids',
			'post_type'      => 'book',
			'posts_per_page' => - 1,
		] ) );
	}

	/**
	 * It should allow filtering the delete method completely
	 *
	 * @test
	 */
	public function should_allow_filtering_the_delete_method_completely() {
		list( $john, $from_john, $jane, $from_jane ) = $this->create_books_by_authors();
		add_filter( 'tribe_repository_books_delete', function () {
			return 'foo';
		} );

		$deleted = $this->repository()->where( 'author', $john )->delete();

		$this->assertEquals( 'foo', $deleted );
		$this->assertEqualSets( array_merge( $from_john, $from_jane ), get_posts( [
			'fields'         => 'ids',
			'post_type'      => 'book',
			'posts_per_page' => - 1,
		] ) );
	}

	/**
	 * It should allow deleting in async mode
	 *
	 * @test
	 */
	public function should_allow_deleting_in_async_mode() {
		 $this->markTestSkipped( 'Locally the test passes with flying colors, but on Travis it fails randomly. Nothing introduced was supposed to break it.' );

		add_filter( 'tribe_repository_delete_async_activated', '__return_true' );
		add_filter( 'tribe_repository_delete_background_threshold', function () {
			// Since we're deleting 3 posts let's make sure async mode is kicking in.
			return 2;
		} );
		list( $john, $from_john ) = $this->create_books_by_authors();

		$deleted = $this->repository()->where( 'author', $john )->delete();

		$this->assertEqualSets( $from_john, $deleted );
		foreach ( $from_john as $id ) {
			$this->assertInstanceOf( \WP_Post::class, get_post( $id ) );
		}
	}

	/**
	 * It should allow always getting back a Promise and have it invoked immediately in sync mode
	 *
	 * @test
	 */
	public function should_allow_always_getting_back_a_promise_and_have_it_invoked_immediately_in_sync_mode() {
		add_filter( 'tribe_repository_delete_async_activated', '__return_false' );
		list( $john, $from_john ) = $this->create_books_by_authors();

		$filtered = $this->repository()->where( 'author', $john );
		$this->assertCount( 3, $filtered->get_ids() );
		$promise = $filtered->delete( true );
		$this->assertInstanceOf( Promise::class, $promise );
		add_action( 'test_resolved', function ( $arg ) {
			$this->assertEquals( 'one', $arg );
		} );

		$promise->then( function () {
			do_action( 'test_resolved', 'one' );
		} )->dispatch();

		$this->assertTrue( (bool) did_action( 'test_resolved' ) );
		foreach ( $from_john as $id ) {
			$this->assertEmpty(  get_post( $id ) );
		}
	}

	/**
	 * It should allow filtering the delete callback
	 *
	 * @test
	 */
	public function should_allow_filtering_the_delete_callback() {
		register_post_type('deleted_book');
		add_filter( 'tribe_repository_delete_async_activated', '__return_false' );
		add_filter('tribe_repository_delete_callback', function () {
			return function ( $id ) {
				wp_update_post( [
					'ID'        => $id,
					'post_type' => 'deleted_book',
				] );
			};
		});
		list( $john, $from_john ) = $this->create_books_by_authors();

		$this->repository()->where( 'author', $john )->delete();

		foreach ( $from_john as $id ) {
			$this->assertEquals( 'deleted_book', get_post_type( $id ) );
		}
	}

	/**
	 * It should return a promise when requesting it on empty matches
	 *
	 * @test
	 */
	public function should_return_a_promise_when_requesting_it_on_empty_matches() {
		$promise = $this->repository()->where( 'author', 23 )->delete( true );
		$this->assertInstanceOf( \Tribe__Promise::class, $promise );
	}
}
