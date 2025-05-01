<?php

namespace Tribe\Repository;

require_once __DIR__ . '/ReadTestBase.php';

class ReadRelationshipsTest extends ReadTestBase {

	public $books;

	public $reviews;

	public function setUp(): void {
		parent::setUp();

		$this->books = $this->factory()->post->create_many( 5, [ 'post_type' => 'book' ] );
		$this->reviews = $this->factory()->post->create_many( 5, [ 'post_type' => 'review' ] );

		// Creates 2 reviews for book[4]
		update_post_meta( $this->reviews[0], 'book_id', $this->books[4] );
		update_post_meta( $this->reviews[1], 'book_id', $this->books[4] );
		// Creates 1 reviews for book[3]
		update_post_meta( $this->reviews[2], 'book_id', $this->books[3] );
		// Creates 1 reviews for book[2]
		update_post_meta( $this->reviews[3], 'book_id', $this->books[2] );
		// Create 1 review for a non-existing book
		update_post_meta( $this->reviews[4], 'book_id', rand( 1000, PHP_INT_MAX ) );
		// books 0 and 1 do not have reviews.
	}

	/**
	 * It should allow filtering posts that are not related via post meta values
	 *
	 * @test
	 */
	public function should_allow_querying_by_not_related_to_meta() {
		$repository = $this->repository();
		$not_related_ids = $repository
			->by_args( [ 'post_type' => 'book' ] )
			->by_not_related_to( [ 'book_id' ] )
			->get_ids();

		$this->assertEquals( 2, count( $not_related_ids ) );
		$this->assertEqualSets( [ $this->books[0], $this->books[1] ], $not_related_ids );
	}
}
