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

		$tax_query = Taxonomy::translate_to_repo( 'category', [ $term_4->term_id, 'event-category-2', 'event-category-3' ] );
		codecept_debug( $tax_query );

//		$this->assertEquals(
//			[
//				'tax_query' => [
//					'' => [
//						'taxonomy' => 'category',
//						'field'    => 'term_id',
//						'terms'    => $terms,
//						'operator' => Taxonomy::OPERAND_OR,
//					],
//				],
//			],
//			$tax_query
//		);

	}

}
