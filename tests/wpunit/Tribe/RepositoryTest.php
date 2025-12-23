<?php

namespace Tribe;

require_once codecept_data_dir( 'classes/Repository/Book_Repository.php' );

use Codeception\TestCase\WPTestCase;
use Tribe\Common\Tests\Repository\Book_Repository as Repository;

class RepositoryTest extends WPTestCase {
	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->repository();

		$this->assertInstanceOf( Repository::class, $sut );
	}

	/**
	 * @return Repository
	 */
	protected function repository() {
		return new Repository();
	}

	public function setUp(): void {
		parent::setUp();

		register_taxonomy(
			'audience',
			[ 'book' ],
			[
				'hierarchical' => false,
				'label'        => 'Audience',
			]
		);

		// Set the user to one that will be able to create taxonomy terms.
		wp_set_current_user( static::factory()->user->create( [ 'role' => 'administrator' ] ) );
	}

	protected function create_three_books(): array {
		$book_1_id = static::factory()->post->create( [
			'post_type'  => 'book',
			'post_title' => 'Alice in Wonderland',
			'meta_input' => [
				'sourced_genre' => 'fiction',
				'author_name'   => 'Lewis Carroll',
			],
			'tax_input'  => [
				'audience' => [ 'for kids', 'for young adults', 'for adults' ]
			],
		] );
		$book_2_id = static::factory()->post->create( [
			'post_type'  => 'book',
			'post_title' => 'The Hobbit',
			'meta_input' => [
				'sourced_genre' => 'fantasy',
				'author_name'   => 'J.R.R. Tolkien',
			],
			'tax_input'  => [
				'audience' => [ 'for kids', 'for young adults', 'for adults' ]
			],
		] );
		$book_3_id = static::factory()->post->create( [
			'post_type'  => 'book',
			'post_title' => 'The Lord of the Rings',
			'meta_input' => [
				'sourced_genre' => 'fantasy',
				'author_name'   => 'J.R.R. Tolkien',
			],
			'tax_input'  => [
				'audience' => [ 'for young adults', 'for adults' ]
			],
		] );

		return array( $book_1_id, $book_2_id, $book_3_id );
	}

	/**
	 * It should allow getting and setting default arguments
	 *
	 * @test
	 */
	public function should_allow_getting_and_setting_default_arguments() {
		$repository   = $this->repository();
		$current_args = $repository->get_default_args();

		$new_args = array_merge( $current_args, [ 'foo' => 'bar' ] );

		$repository->set_default_args( $new_args );

		$this->assertEquals( $new_args, $repository->get_default_args() );
	}

	/**
	 * It should return a Read repository when calling Read repo methods on it
	 *
	 * @test
	 */
	public function should_return_a_read_repository_when_calling_read_repo_methods_on_it() {
		$repository = $this->repository();

		$this->assertInstanceOf( \Tribe__Repository__Read_Interface::class, $repository->by( 'title', 'foo' ) );
		$this->assertInstanceOf( \Tribe__Repository__Read_Interface::class, $repository->where( 'title', 'foo' ) );
		$this->assertInstanceOf( \Tribe__Repository__Read_Interface::class,
			$repository->where_args( [ 'title' => 'foo' ] ) );
		$this->assertInstanceOf( \Tribe__Repository__Read_Interface::class, $repository->page( 2 ) );
		$this->assertInstanceOf( \Tribe__Repository__Read_Interface::class, $repository->per_page( 2 ) );
		$this->assertInstanceOf( \Tribe__Repository__Read_Interface::class, $repository->offset( 2 ) );
		$this->assertInstanceOf( \Tribe__Repository__Read_Interface::class, $repository->order( 'DESC' ) );
		$this->assertInstanceOf( \Tribe__Repository__Read_Interface::class, $repository->order_by( 'id' ) );
		$this->assertInstanceOf( \Tribe__Repository__Read_Interface::class, $repository->fields( 'ids' ) );
		$this->assertInstanceOf( \Tribe__Repository__Read_Interface::class, $repository->permission( 'editable' ) );
		$this->assertInstanceOf( \Tribe__Repository__Read_Interface::class, $repository->in( [ 1, 2 ] ) );
		$this->assertInstanceOf( \Tribe__Repository__Read_Interface::class, $repository->not_in( [ 1, 2 ] ) );
		$this->assertInstanceOf( \Tribe__Repository__Read_Interface::class, $repository->parent( 1 ) );
		$this->assertInstanceOf( \Tribe__Repository__Read_Interface::class, $repository->parent_in( [ 1, 2 ] ) );
		$this->assertInstanceOf( \Tribe__Repository__Read_Interface::class, $repository->parent_not_in( [ 1, 2 ] ) );
		$this->assertInstanceOf( \Tribe__Repository__Read_Interface::class, $repository->search( 'foo' ) );
	}

	/**
	 * It should allow mapping on the query results
	 *
	 * @test
	 */
	public function should_allow_mapping_on_the_query_results(): void {
		[ , $book_2_id, $book_3_id ] = $this->create_three_books();

		$this->assertEquals(
			[ 'Alice in Wonderland', 'The Hobbit', 'The Lord of the Rings' ],
			$this->repository()->order_by( 'title', 'asc' )->map( fn( $book ) => $book->post_title )
		);

		$this->assertEquals(
			[ 'The Lord of the Rings', 'The Hobbit' ],
			$this
				->repository()
				->where( 'meta_equals', 'sourced_genre', 'fantasy' )
				->order_by( 'title', 'desc' )
				->map( fn( $book ) => $book->post_title )
		);

		$this->assertEquals(
			[ 'ID => ' . $book_3_id, 'ID => ' . $book_2_id ],
			$this
				->repository()
				->where( 'meta_equals', 'sourced_genre', 'fantasy' )
				->fields( 'ids' )
				->order_by( 'title', 'desc' )
				->map( fn( $id ) => 'ID => ' . $id )
		);
	}

	/**
	 * @test
	 */
	public function should_build_meta_in_not_in_query_correctly(): void {
		[ $book_1_id, $book_2_id, $book_3_id ] = $this->create_three_books();

		$this->assertEquals(
			[ $book_1_id ],
			$this->repository()->where( 'meta_in', 'author_name', 'Lewis Carroll' )->get_ids(),
			"meta_in should support single values with space"
		);

		$this->assertEquals(
			[ $book_1_id ],
			$this->repository()->where( 'meta_in', 'author_name', [ 'Lewis Carroll' ] )->get_ids(),
			"meta_in should support single values with space"
		);
		$this->assertEquals(
			[ $book_2_id, $book_3_id ],
			$this->repository()->where( 'meta_not_in', 'author_name', 'Lewis Carroll' )->get_ids(),
			"meta_not_in should support single values with space"
		);

		$this->assertEquals(
			[ $book_2_id, $book_3_id ],
			$this->repository()->where( 'meta_not_in', 'author_name', [ 'Lewis Carroll' ] )->get_ids(),
			"meta_not_in should support single values with spaces in array"
		);
	}

	/**
	 * @test
	 */
	public function should_build_term_id_in_not_in_query_correctly(): void {
		[ $book_1_id, $book_2_id, $book_3_id ] = $this->create_three_books();

		$for_kids_term_id = get_term_by( 'name', 'for kids', 'audience' )->term_id;

		$this->assertEquals(
			[ $book_1_id, $book_2_id ],
			$this->repository()->where( 'term_id_in', 'audience', $for_kids_term_id )->get_ids(),
			"term_id_in should support single values"
		);
		$this->assertEquals(
			[ $book_3_id ],
			$this->repository()->where( 'term_id_not_in', 'audience', $for_kids_term_id )->get_ids(),
			"term_id_not_in should support single values"
		);
	}

	/**
	 * @test
	 */
	public function should_build_term_name_in_not_in_query_correctly(): void {
		[ $book_1_id, $book_2_id, $book_3_id ] = $this->create_three_books();

		$this->assertEquals(
			[ $book_1_id, $book_2_id ],
			$this->repository()->where( 'term_name_in', 'audience', 'for kids' )->get_ids(),
			"term_name_in should support single values with space"
		);
		$this->assertEquals(
			[ $book_1_id, $book_2_id ],
			$this->repository()->where( 'term_name_in', 'audience', [ 'for kids' ] )->get_ids(),
			"term_name_in should support single values with space in array"
		);
		$this->assertEquals(
			[ $book_3_id ],
			$this->repository()->where( 'term_name_not_in', 'audience', 'for kids' )->get_ids(),
			"term_name_not_in should support single values with space"
		);
		$this->assertEquals(
			[ $book_3_id ],
			$this->repository()->where( 'term_name_not_in', 'audience', [ 'for kids' ] )->get_ids(),
			"term_name_not_in should support single values with space in array"
		);
	}

	/**
	 * @test
	 */
	public function should_build_term_slug_in_not_in_query_correctly(): void {
		[ $book_1_id, $book_2_id, $book_3_id ] = $this->create_three_books();

		$this->assertEquals(
			[ $book_1_id, $book_2_id ],
			$this->repository()->where( 'term_slug_in', 'audience', 'for-kids' )->get_ids(),
			"term_slug_in should support single values with space"
		);
		$this->assertEquals(
			[ $book_1_id, $book_2_id ],
			$this->repository()->where( 'term_slug_in', 'audience', [ 'for-kids' ] )->get_ids(),
			"term_slug_in should support single values with space in array"
		);
		$this->assertEquals(
			[ $book_3_id ],
			$this->repository()->where( 'term_slug_not_in', 'audience', 'for-kids' )->get_ids(),
			"term_slug_not_in should support single values with space"
		);
		$this->assertEquals(
			[ $book_3_id ],
			$this->repository()->where( 'term_slug_not_in', 'audience', [ 'for-kids' ] )->get_ids(),
			"term_slug_not_in should support single values with space in array"
		);
	}
}
