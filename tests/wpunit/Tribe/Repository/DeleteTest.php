<?php

namespace Tribe\Repository;

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
}
