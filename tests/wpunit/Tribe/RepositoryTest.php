<?php

namespace Tribe;

require_once codecept_data_dir( 'classes/Repository/Book_Repository.php' );

use Tribe\Common\Tests\Repository\Book_Repository as Repository;

class RepositoryTest extends \Codeception\TestCase\WPTestCase {
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
		$this->assertInstanceOf( \Tribe__Repository__Read_Interface::class, $repository->where_args( [ 'title' => 'foo' ] ) );
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
		// Create 3 books.
		$book_1_id = static::factory()->post->create( [
			'post_type'  => 'book',
			'post_title' => 'Alice in Wonderland',
			'meta_input' => [
				'sourced_genre' => 'fiction'
			]
		] );
		$book_2_id = static::factory()->post->create( [
			'post_type'  => 'book',
			'post_title' => 'The Hobbit',
			'meta_input' => [
				'sourced_genre' => 'fantasy'
			]
		] );
		$book_3_id = static::factory()->post->create( [
			'post_type'  => 'book',
			'post_title' => 'The Lord of the Rings',
			'meta_input' => [
				'sourced_genre' => 'fantasy'
			]
		] );

		$this->assertEquals(
			[ 'Alice in Wonderland', 'The Hobbit', 'The Lord of the Rings' ],
			$this->repository()->order_by( 'title', 'asc' )->map( fn( $book ) => $book->post_title )
		);

		$this->assertEquals(
			[ 'The Lord of the Rings', 'The Hobbit' ],
			$this->repository()
			     ->where( 'meta_equals', 'sourced_genre', 'fantasy' )
			     ->order_by( 'title', 'desc' )
			     ->map( fn( $book ) => $book->post_title )
		);

		$this->assertEquals(
			[ 'ID => ' . $book_3_id, 'ID => ' . $book_2_id ],
			$this->repository()
			     ->where( 'meta_equals', 'sourced_genre', 'fantasy' )
			     ->fields( 'ids' )
			     ->order_by( 'title', 'desc' )
			     ->map( fn( $id ) => 'ID => ' . $id )
		);
	}
}