<?php

namespace Tribe\Repository;

require_once __DIR__ . '/ReadTestBase.php';

class AllQueryTest extends ReadTestBase {

	public function wrong_batch_size_provider(): array {
		return [
			'0'  => [ 0 ],
			'-1' => [ - 1 ],
		];
	}

	/**
	 * It should throw if batch size is not integer greater than 0
	 *
	 * @test
	 * @dataProvider wrong_batch_size_provider
	 */
	public function should_throw_if_batch_size_is_not_integer_greater_than_0( int $wrong_batch_size ): void {
		$this->expectException( \Tribe__Repository__Usage_Error::class );

		$this->repository()->get_ids( true, $wrong_batch_size )->next();
	}

	/**
	 * It should fetch all posts with one query when found less than batch size
	 *
	 * @test
	 */
	public function should_fetch_all_posts_with_one_query_when_found_less_than_batch_size(): void {
		$ids = static::factory()->post->create_many( 10, [ 'post_type' => 'book' ] );
		global $wpdb;
		$queries_before = $wpdb->num_queries;

		$repository = $this->repository();
		$generator  = $repository->get_ids( true, 20 );

		$this->assertEqualSets( $ids, iterator_to_array( $generator ) );
		// One query to fetch, one to get found rows.
		$this->assertEquals( $queries_before + 2, $wpdb->num_queries );
	}

	/**
	 * It should fatch all posts with multiple queries when found more than batch size
	 *
	 * @test
	 */
	public function should_fatch_all_posts_with_multiple_queries_when_found_more_than_batch_size(): void {
		$ids = static::factory()->post->create_many( 10, [ 'post_type' => 'book' ] );
		global $wpdb;
		$queries_before = $wpdb->num_queries;

		$repository = $this->repository();
		$generator  = $repository->get_ids( true, 3 );

		$actual = iterator_to_array( $generator );
		$this->assertEqualSets( $ids, $actual );
		// Four queries to fetch, one to get found rows.
		$this->assertEquals( $queries_before + 5, $wpdb->num_queries );
	}

	/**
	 * It should get all IDS correctly when found equals batch size
	 *
	 * @test
	 */
	public function should_get_all_ids_correctly_when_found_equals_batch_size(): void {
		$ids = static::factory()->post->create_many( 10, [ 'post_type' => 'book' ] );
		global $wpdb;
		$queries_before = $wpdb->num_queries;

		$repository = $this->repository();
		$generator  = $repository->get_ids( true, 10 );

		$this->assertEqualSets( $ids, iterator_to_array( $generator ) );
		// One query to fetch, one to get found rows, one to check if there are more.
		$this->assertEquals( $queries_before + 3, $wpdb->num_queries );
	}

	/**
	 * It should throw if batch size is not integer greater than 0 to get all_posts
	 *
	 * @test
	 * @dataProvider wrong_batch_size_provider
	 */
	public function should_throw_if_batch_size_is_not_integer_greater_than_0_to_get_all_posts( int $wrong_batch_size ): void {
		$this->expectException( \Tribe__Repository__Usage_Error::class );

		$this->repository()->all( true, $wrong_batch_size )->next();
	}

	/**
	 * It should get all posts with one query when found less than batch size
	 *
	 * @test
	 */
	public function should_get_all_posts_with_one_query_when_found_less_than_batch_size(): void {
		$ids = static::factory()->post->create_many( 10, [ 'post_type' => 'book' ] );
		global $wpdb;
		$queries_before = $wpdb->num_queries;

		$repository = $this->repository();
		$generator  = $repository->all( true, 20 );

		$all_books = iterator_to_array( $generator );
		$this->assertContainsOnlyInstancesOf( \WP_Post::class, $all_books );
		$this->assertEqualSets( $ids, wp_list_pluck( $all_books, 'ID' ) );
		// One query to fetch, one to get found rows.
		$this->assertEquals( $queries_before + 2, $wpdb->num_queries );
	}

	/**
	 * It should get all posts with multiple queries when found more than batch size
	 *
	 * @test
	 */
	public function should_get_all_posts_with_multiple_queries_when_found_more_than_batch_size(): void {
		$ids = static::factory()->post->create_many( 10, [ 'post_type' => 'book' ] );
		global $wpdb;
		$queries_before = $wpdb->num_queries;

		$repository = $this->repository();
		$generator  = $repository->all( true, 3 );

		$all_books = iterator_to_array( $generator );
		$this->assertContainsOnlyInstancesOf( \WP_Post::class, $all_books );
		$this->assertEqualSets( $ids, wp_list_pluck( $all_books, 'ID' ) );
		// Four queries to fetch, one to get found rows.
		$this->assertEquals( $queries_before + 5, $wpdb->num_queries );
	}

	/**
	 * It should get all posts correctly when found equals batch size
	 *
	 * @test
	 */
	public function should_get_all_posts_correctly_when_found_equals_batch_size(): void {
		$ids = static::factory()->post->create_many( 10, [ 'post_type' => 'book' ] );
		global $wpdb;
		$queries_before = $wpdb->num_queries;

		$repository = $this->repository();
		$generator  = $repository->all( true, 10 );

		$all_books = iterator_to_array( $generator );
		$this->assertContainsOnlyInstancesOf( \WP_Post::class, $all_books );
		$this->assertEqualSets( $ids, wp_list_pluck( $all_books, 'ID' ) );
		// One query to fetch, one to get found rows, one to check if there are more.
		$this->assertEquals( $queries_before + 3, $wpdb->num_queries );
	}

	public function offset_limit_provider(): array {
		return [
			'OFFSET 5 LIMIT 7'  => [ 5, 7 ],
			'OFFSET 0 LIMIT 5'  => [ 0, 5 ],
			'OFFSET 2, LIMIT 3' => [ 2, 3 ],
		];
	}

	/**
	 * It should handle query offset and limit correctly
	 *
	 * A query for "all" means "all the matching ones": limit and offset should be respected.
	 *
	 * @test
	 * @dataProvider offset_limit_provider
	 */
	public function should_handle_query_offset_and_limit_correctly( int $offset, int $limit ): void {
		$ids        = static::factory()->post->create_many( 10, [ 'post_type' => 'book' ] );
		$expected   = array_slice( $ids, $offset, $limit );
		$repository = $this->repository();
		$repository->offset( $offset );
		$repository->per_page( $limit );
		// Get the posts 2 at a time.
		$generator = $repository->all( true, 2 );

		$fetched = iterator_to_array( $generator );

		$this->assertEqualSets( $expected, wp_list_pluck( $fetched, 'ID' ) );
	}
}