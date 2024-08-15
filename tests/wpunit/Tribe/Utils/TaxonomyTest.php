<?php

namespace Tribe\Utils;

use Codeception\TestCase\WPTestCase;

class TaxonomyTest extends WPTestCase {
	/**
	 * @test
	 *
	 * @group utils
	 */
	public function it_should_be_instantiatable() {
		$tax = new Taxonomy;
		$this->assertInstanceOf( Taxonomy::class, $tax );
	}

	/**
	 * @test
	 * @group        utils
	 */
	public function it_should_translate_terms_to_tax_query() {
		$term_1 = wp_insert_term( 'Event Tag 1', 'post_tag', [ 'slug' => 'event-tag-1' ] );
		$term_2 = wp_insert_term( 'Event Tag 2', 'post_tag', [ 'slug' => 'event-tag-2' ] );
		$term_3 = wp_insert_term( 'Event Tag 3', 'post_tag', [ 'slug' => 'event-tag-3' ] );

		$term_4 = wp_insert_term( 'Event Category 1', 'category', [ 'slug' => 'event-category-1' ] );
		$term_5 = wp_insert_term( 'Event Category 2', 'category', [ 'slug' => 'event-category-2' ] );
		$term_6 = wp_insert_term( 'Event Category 3', 'category', [ 'slug' => 'event-category-3' ] );

		$tax_query = Taxonomy::translate_to_repository_args( 'category', [
			$term_4['term_id'],
			'event-category-2',
			'event-category-3'
		] );
		$this->assertEquals(
			[
				'taxonomy' => 'category',
				'field'    => 'term_id',
				'terms'    => [ $term_4['term_id'], $term_5['term_id'], $term_6['term_id'], ],
				'operator' => 'IN',
			],
			$tax_query['category_term_id_in']
		);

		$tax_query = Taxonomy::translate_to_repository_args( 'post_tag', [
			$term_1['term_id'],
			'event-tag-2',
			'event-tag-3'
		] );
		$this->assertEquals(
			[
				'taxonomy' => 'post_tag',
				'field'    => 'term_id',
				'terms'    => [ $term_1['term_id'], $term_2['term_id'], $term_3['term_id'], ],
				'operator' => 'IN',
			],
			$tax_query['post_tag_term_id_in']
		);

		$tax_query = Taxonomy::translate_to_repository_args( 'category', [ $term_4['term_id'] ], Taxonomy::OPERAND_AND );
		$this->assertEquals(
			[
				'taxonomy' => 'category',
				'field'    => 'term_id',
				'terms'    => [ $term_4['term_id'] ],
				'operator' => 'AND',
			],
			$tax_query['category_term_id_and']
		);

		$tax_query = Taxonomy::translate_to_repository_args( 'category', [
			948192,
			'non-existent-category-foo'
		], Taxonomy::OPERAND_AND );
		$this->assertEquals(
			[
				'taxonomy' => 'category',
				'field'    => 'term_id',
				'terms'    => [],
				'operator' => 'AND',
			],
			$tax_query['category_term_id_and']
		);

	}

	/**
	 * @test
	 * @group        utils
	 */
	public function it_should_normalize_to_term_ids() {
		$term_0 = wp_insert_term( '2024', 'post_tag', [ 'slug' => '2024' ] );
		$term_1 = wp_insert_term( 'Event Tag 4', 'post_tag', [ 'slug' => 'event-tag-4' ] );
		$term_2 = wp_insert_term( 'Event Tag 5', 'post_tag', [ 'slug' => 'event-tag-5' ] );
		$term_3 = wp_insert_term( 'Event Tag 6', 'post_tag', [ 'slug' => 'event-tag-6' ] );

		$term_4 = wp_insert_term( 'Event Category 4', 'category', [ 'slug' => 'event-category-4' ] );
		$term_5 = wp_insert_term( 'Event Category 5', 'category', [ 'slug' => 'event-category-5' ] );
		$term_6 = wp_insert_term( 'Event Category 6', 'category', [ 'slug' => 'event-category-6' ] );

		$tax_query = Taxonomy::normalize_to_term_ids( [
			$term_4['term_id'],
			'event-category-5',
			'event-category-6'
		], 'category' );
		$this->assertEquals(
			[ $term_4['term_id'], $term_5['term_id'], $term_6['term_id'], ],
			$tax_query
		);

		$tax_query = Taxonomy::normalize_to_term_ids( [
			'2024',
			$term_1['term_id'],
			'event-tag-5',
			'event-tag-6'
		], 'post_tag' );
		$this->assertEquals(
			[ $term_0['term_id'], $term_1['term_id'], $term_2['term_id'], $term_3['term_id'], ],
			$tax_query
		);

		$tax_query = Taxonomy::normalize_to_term_ids( [ 948192, 'non-existent-category-foo' ], 'post_tag' );
		$this->assertEquals(
			[],
			$tax_query
		);
	}

	/**
	 * @test
	 * @group        utils
	 */
	public function it_should_properly_prime_taxonomy_cache() {
		$tag_1 = wp_insert_term( uniqid( 'Tag-', true ), 'post_tag' )['term_id'];
		$tag_2 = wp_insert_term( uniqid( 'Tag-', true ), 'post_tag' )['term_id'];
		$tag_3 = wp_insert_term( uniqid( 'Tag-', true ), 'post_tag' )['term_id'];
		$tag_4 = wp_insert_term( uniqid( 'Tag-', true ), 'post_tag' )['term_id'];

		$cat_1 = wp_insert_term( uniqid( 'Category-', true ), 'category' )['term_id'];
		$cat_2 = wp_insert_term( uniqid( 'Category-', true ), 'category' )['term_id'];
		$cat_3 = wp_insert_term( uniqid( 'Category-', true ), 'category' )['term_id'];
		$cat_4 = wp_insert_term( uniqid( 'Category-', true ), 'category' )['term_id'];

		$event_1 = ( new \WP_UnitTest_Factory_For_Post() )->create( [] );

		wp_set_post_terms( $event_1, [ $tag_1, $tag_2 ], 'post_tag' );
		wp_set_post_terms( $event_1, [ $cat_1, $cat_2 ], 'category' );

		$event_2 = ( new \WP_UnitTest_Factory_For_Post() )->create( [] );

		wp_set_post_terms( $event_2, [ $tag_3 ], 'post_tag' );

		$event_3 = ( new \WP_UnitTest_Factory_For_Post() )->create( [] );

		wp_set_post_terms( $event_3, [ $cat_3 ], 'category' );

		// Clean taxonomy cache for testing.
		clean_taxonomy_cache( 'post_tag' );
		clean_taxonomy_cache( 'category' );

		Taxonomy::prime_term_cache( [ $event_1, $event_2 ], [ 'post_tag', 'category' ] );

		$cache_1_cat = wp_cache_get( $event_1, 'category_relationships' );
		$cache_1_tag = wp_cache_get( $event_1, 'post_tag_relationships' );

		$this->assertContains( $cat_1, $cache_1_cat );
		$this->assertContains( $cat_2, $cache_1_cat );
		$this->assertNotContains( $cat_3, $cache_1_cat );
		$this->assertNotContains( $cat_4, $cache_1_cat );

		$this->assertContains( $tag_1, $cache_1_tag );
		$this->assertContains( $tag_2, $cache_1_tag );
		$this->assertNotContains( $tag_3, $cache_1_tag );
		$this->assertNotContains( $tag_4, $cache_1_tag );

		$cache_2_cat = wp_cache_get( $event_2, 'category_relationships' );
		$cache_2_tag = wp_cache_get( $event_2, 'post_tag_relationships' );

		$this->assertNotContains( $cat_1, $cache_2_cat );
		$this->assertNotContains( $cat_2, $cache_2_cat );
		$this->assertNotContains( $cat_3, $cache_2_cat );
		$this->assertNotContains( $cat_4, $cache_2_cat );

		$this->assertNotContains( $tag_1, $cache_2_tag );
		$this->assertNotContains( $tag_2, $cache_2_tag );
		$this->assertContains( $tag_3, $cache_2_tag );
		$this->assertNotContains( $tag_4, $cache_2_tag );

		$cache_3_cat = wp_cache_get( $event_3, 'category_relationships' );
		$cache_3_tag = wp_cache_get( $event_3, 'post_tag_relationships' );

		$this->assertEmpty( $cache_3_cat );
		$this->assertEmpty( $cache_3_tag );
	}

	/**
	 * It should correctly handle missing taxonomy terms
	 *
	 * @test
	 */
	public function should_correctly_handle_missing_taxonomy_terms() {
		[ $post_1, $post_2, $post_3 ] = static::factory()->post->create_many( 3 );
		[ $cat_1, $cat_2, $cat_3 ] = static::factory()->category->create_many( 3 );
		wp_set_object_terms( $post_1, $cat_1, 'category' );
		wp_set_object_terms( $post_2, $cat_2, 'category' );
		wp_set_object_terms( $post_3, $cat_3, 'category' );
		// Filter the `get_terms` query reult to return some values that are not terms.
		add_filter( 'get_terms', static function ( $terms ) {
			$terms[1] = new \WP_Error( 'something', 'something' );
			$terms[2] = null;

			return $terms;
		} );

		$primed = Taxonomy::prime_term_cache( [ $post_1, $post_2, $post_3 ], [ 'post_tag', 'category' ] );
		$this->assertEquals( [
			$post_1 => [ 'post_tag' => [], 'category' => [ $cat_1 ], ],
			$post_2 => [ 'post_tag' => [], 'category' => [], ],
			$post_3 => [ 'post_tag' => [], 'category' => [], ],
		], $primed );
	}
}
