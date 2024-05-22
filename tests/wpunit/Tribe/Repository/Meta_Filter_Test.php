<?php

namespace TEC\Tribe\Repository;

require_once __DIR__ . '/ReadTestBase.php';

use Tribe\Repository\ReadTestBase;

class Meta_Filter_Test extends ReadTestBase {
	/**
	 * It should allow filtering by meta key not by NULL
	 *
	 * @test
	 */
	public function should_allow_filtering_by_meta_key_not_by_null(): void {
		$book_1 = static::factory()->post->create( [
			'post_type'  => 'book',
			'meta_input' => [
				'_has_review' => '1'
			]
		] );
		// Books with no reviews.
		$book_2 = static::factory()->post->create( [ 'post_type' => 'book' ] );
		$book_3 = static::factory()->post->create( [
			'post_type'  => 'book',
			'meta_input' => [
				'_has_review' => '0'
			]
		] );

		/** @var \Tribe__Repository $repository */
		$repository = $this->repository();
		$repository->add_schema_entry( 'has_no_review', static function () use ( $repository ): void {
			$repository->filter_query->meta_not( '_has_review' );
		} );
		$found = $repository->where( 'has_no_review' )->get_ids();

		$this->assertEqualSets( [ $book_2 ], $found );
	}

	/**
	 * It should allow filtering by meta key not in value set
	 *
	 * @test
	 */
	public function should_allow_filtering_by_meta_key_not_in_value_set(): void {
		$book_1 = static::factory()->post->create( [
			'post_type'  => 'book',
			'meta_input' => [
				'_has_review' => '1'
			]
		] );
		// Books with no reviews.
		$book_2 = static::factory()->post->create( [ 'post_type' => 'book' ] );
		$book_3 = static::factory()->post->create( [
			'post_type'  => 'book',
			'meta_input' => [
				'_has_review' => '0'
			]
		] );

		/** @var \Tribe__Repository $repository */
		$repository = $this->repository();
		$repository->add_schema_entry( 'has_no_review', static function () use ( $repository ): void {
			$repository->filter_query->meta_not( '_has_review', 1 );
		} );
		$found = $repository->where( 'has_no_review' )->get_ids();

		$this->assertEqualSets( [ $book_2, $book_3 ], $found );
	}

	/**
	 * It should allow filtering by meta key not with key and value sets
	 *
	 * @test
	 */
	public function should_allow_filtering_by_meta_key_not_with_key_and_value_sets(): void {
		$book_1 = static::factory()->post->create( [
			'post_type'  => 'book',
			'meta_input' => [
				'_has_reader_review' => '1',
				'_has_writer_review' => '1'
			]
		] );
		$book_2 = static::factory()->post->create( [ 'post_type' => 'book' ] );
		$book_3 = static::factory()->post->create( [
			'post_type'  => 'book',
			'meta_input' => [
				'_has_review' => '0'
			]
		] );

		/** @var \Tribe__Repository $repository */
		$repository = $this->repository();
		$repository->add_schema_entry( 'has_no_review', static function () use ( $repository ): void {
			$repository->filter_query->meta_not( [ '_has_reader_review', '_has_writer_review' ], 1 );
		} );
		$found = $repository->where( 'has_no_review' )->get_ids();

		$this->assertEqualSets( [ $book_2, $book_3 ], $found );
	}

	/**
	 * It should allow filtering by meta key not with key set and NULL values
	 *
	 * @test
	 */
	public function should_allow_filtering_by_meta_key_not_with_key_set_and_null_values(): void {
		$book_1 = static::factory()->post->create( [
			'post_type'  => 'book',
			'meta_input' => [
				'_has_reader_review' => '1',
				'_has_writer_review' => '1'
			]
		] );
		$book_2 = static::factory()->post->create( [ 'post_type' => 'book' ] );
		$book_3 = static::factory()->post->create( [
			'post_type'  => 'book',
			'meta_input' => [
				'_has_review' => '0'
			]
		] );

		/** @var \Tribe__Repository $repository */
		$repository = $this->repository();
		$repository->add_schema_entry( 'has_no_review', static function () use ( $repository ): void {
			$repository->filter_query->meta_not( [ '_has_reader_review', '_has_writer_review' ] );
		} );
		$found = $repository->where( 'has_no_review' )->get_ids();

		$this->assertEqualSets( [ $book_2, $book_3 ], $found );
	}
}