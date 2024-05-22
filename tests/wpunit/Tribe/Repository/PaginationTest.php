<?php

namespace Tribe\Repository;

require_once __DIR__ . '/ReadTestBase.php';

use Tribe__Repository__Interface as Repository;

class PaginationTest extends ReadTestBase {

	/**
	 * It should return the next page repository instance
	 *
	 * @test
	 */
	public function should_return_the_next_page_repository_instance() {
		$repository = $this->repository();
		$repository->per_page( 2 );
		$book_ids = static::factory()->post->create_many( 5, [ 'post_type' => 'book' ] );

		$page_2 = $repository->next();

		$this->assertInstanceOf( Repository::class, $page_2 );
		$this->assertEquals( array_slice( $book_ids, 0, 2 ), $repository->get_ids() );
		$this->assertEquals( array_slice( $book_ids, 2, 2 ), $page_2->get_ids() );

		$page_3 = $page_2->next();

		$this->assertInstanceOf( Repository::class, $page_3 );
		$this->assertEquals( array_slice( $book_ids, 0, 2 ), $repository->get_ids() );
		$this->assertEquals( array_slice( $book_ids, 2, 2 ), $page_2->get_ids() );
		$this->assertEquals( array_slice( $book_ids, 4, 1 ), $page_3->get_ids() );

		$page_4 = $page_3->next();

		$this->assertInstanceOf( Repository::class, $page_4 );
		$this->assertEquals( array_slice( $book_ids, 0, 2 ), $repository->get_ids() );
		$this->assertEquals( array_slice( $book_ids, 2, 2 ), $page_2->get_ids() );
		$this->assertEquals( array_slice( $book_ids, 4, 1 ), $page_3->get_ids() );
		$this->assertEquals( [], $page_4->get_ids() );
	}

	/**
	 * It should return the previous page repository instance
	 *
	 * @test
	 */
	public function should_return_the_previous_page_repository_instance() {
		$book_ids = static::factory()->post->create_many( 5, [ 'post_type' => 'book' ] );
		$page_3   = $this->repository()->per_page( 2 )->page( 3 );

		$this->assertInstanceOf( Repository::class, $page_3 );
		$this->assertEquals( array_slice( $book_ids, 4, 1 ), $page_3->get_ids() );

		$page_2 = $page_3->prev();

		$this->assertInstanceOf( Repository::class, $page_2 );
		$this->assertEquals( array_slice( $book_ids, 4, 1 ), $page_3->get_ids() );
		$this->assertEquals( array_slice( $book_ids, 2, 2 ), $page_2->get_ids() );

		$page_1 = $page_2->prev();

		$this->assertInstanceOf( Repository::class, $page_1 );
		$this->assertEquals( array_slice( $book_ids, 4, 1 ), $page_3->get_ids() );
		$this->assertEquals( array_slice( $book_ids, 2, 2 ), $page_2->get_ids() );
		$this->assertEquals( array_slice( $book_ids, 0, 2 ), $page_1->get_ids() );

		$page_0 = $page_1->prev();

		$this->assertInstanceOf( Repository::class, $page_0 );
		$this->assertEquals( array_slice( $book_ids, 4, 1 ), $page_3->get_ids() );
		$this->assertEquals( array_slice( $book_ids, 2, 2 ), $page_2->get_ids() );
		$this->assertEquals( array_slice( $book_ids, 0, 2 ), $page_1->get_ids() );
		$this->assertEquals( [], $page_0->get_ids() );
	}

}
