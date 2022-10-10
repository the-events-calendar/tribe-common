<?php

namespace Tribe\Repository;

require_once __DIR__ . '/ReadTestBase.php';

class ReadRelationshipsTest extends ReadTestBase {

	/**
	 * It should allow filtering posts by related post meta fields
	 *
	 * @test
	 */
	public function should_allow_querying_by_not_related_to_meta() {
		$book_ids = $this->factory()->post->create_many( 5, [ 'post_type' => 'book' ] );
		$review_ids = $this->factory()->post->create_many( 5, [ 'post_type' => 'review' ] );

		foreach( $review_ids as $i => $id ) {
			if ( $i === 0 ) {
				continue;
			}

			if ( $i % 2 === 0 ) {
				// Creates 2 reviews for the first book
				update_post_meta( $id, 'book_id', $book_ids[0] );
			}

			// Create one review for a non-existing book
			update_post_meta( $review_ids[0], 'book_id', rand( 1000, PHP_INT_MAX ) );
		}

		$repository = $this->repository();
		$not_related_ids = $repository->by_args( [ 'post_type' => 'book' ] )->by_not_related_to( [ 'book_id' ] )->get_ids();

		// Now we should have 2 reviews for book[0], one review for a non-existing book.
		// And 4 books without reviews.
		$this->assertEquals( 4, count( $not_related_ids ) );
		// And the books without reviews should be the ones we created, except for book[0]
		array_shift( $book_ids );
		$this->assertEqualSets( $book_ids, $not_related_ids );
	}
}