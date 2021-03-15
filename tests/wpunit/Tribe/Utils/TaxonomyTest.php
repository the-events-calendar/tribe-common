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

		$tax_query = Taxonomy::translate_to_repository_args( 'category', [ $term_4['term_id'], 'event-category-2', 'event-category-3' ] );
		$this->assertEquals(
			[
				'taxonomy' => 'category',
				'field'    => 'term_id',
				'terms'    => [ $term_4['term_id'], $term_5['term_id'], $term_6['term_id'], ],
				'operator' => 'IN',
			],
			$tax_query['category_term_id_in']
		);

		$tax_query = Taxonomy::translate_to_repository_args( 'post_tag', [ $term_1['term_id'], 'event-tag-2', 'event-tag-3' ] );
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

		$tax_query = Taxonomy::translate_to_repository_args( 'category', [ 948192, 'non-existent-category-foo' ], Taxonomy::OPERAND_AND );
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
		$term_1 = wp_insert_term( 'Event Tag 4', 'post_tag', [ 'slug' => 'event-tag-4' ] );
		$term_2 = wp_insert_term( 'Event Tag 5', 'post_tag', [ 'slug' => 'event-tag-5' ] );
		$term_3 = wp_insert_term( 'Event Tag 6', 'post_tag', [ 'slug' => 'event-tag-6' ] );

		$term_4 = wp_insert_term( 'Event Category 4', 'category', [ 'slug' => 'event-category-4' ] );
		$term_5 = wp_insert_term( 'Event Category 5', 'category', [ 'slug' => 'event-category-5' ] );
		$term_6 = wp_insert_term( 'Event Category 6', 'category', [ 'slug' => 'event-category-6' ] );

		$tax_query = Taxonomy::normalize_to_term_ids( [ $term_4['term_id'], 'event-category-5', 'event-category-6' ], 'category' );
		$this->assertEquals(
			[ $term_4['term_id'], $term_5['term_id'], $term_6['term_id'], ],
			$tax_query
		);

		$tax_query = Taxonomy::normalize_to_term_ids( [ $term_1['term_id'], 'event-tag-5', 'event-tag-6' ], 'post_tag' );
		$this->assertEquals(
			[ $term_1['term_id'], $term_2['term_id'], $term_3['term_id'], ],
			$tax_query
		);

		$tax_query = Taxonomy::normalize_to_term_ids( [ 948192, 'non-existent-category-foo' ], 'post_tag' );
		$this->assertEquals(
			[],
			$tax_query
		);
	}

}
