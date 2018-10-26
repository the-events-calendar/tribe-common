<?php

namespace Tribe;

use Tribe__Terms as Terms;

class TermsTest extends \Codeception\TestCase\WPTestCase {
	public function translate_terms_to_ids_bad_inputs() {
		return [
			[ '' ],
			[ 23 ],
			[ [ 23, 89 ] ],
			[ '23' ],
			[ [ '23', '89' ] ],
		];
	}

	/**
	 * Test translate_terms_to_ids with bad inputs
	 *
	 * @test
	 * @dataProvider translate_terms_to_ids_bad_inputs
	 */
	public function test_translate_terms_to_ids_with_bad_inputs( $input ) {
		$this->assertEquals( [], Terms::translate_terms_to_ids( $input, 'post_tag' ) );
	}

	/**
	 * Test translate_terms_to_ids should create not found terms
	 *
	 * @test
	 */
	public function test_translate_terms_to_ids_should_create_not_found_terms() {
		$this->assertFalse( (bool) term_exists( 'foo', 'post_tag' ) );

		$created = Terms::translate_terms_to_ids( 'foo', 'post_tag' );
		$this->assertCount( 1, $created );

		$this->assertTrue( (bool) term_exists( 'foo', 'post_tag' ) );
	}

	/**
	 * Test translate_terms_to_ids does not create existing terms
	 *
	 * @test
	 */
	public function test_translate_terms_to_ids_does_not_create_existing_terms() {
		$foo = $this->factory()->term->create( [ 'slug' => 'foo', 'taxonomy' => 'post_tag' ] );
		$bar = $this->factory()->term->create( [ 'slug' => 'bar', 'taxonomy' => 'post_tag' ] );

		$this->assertTrue( (bool) term_exists( 'foo', 'post_tag' ) );
		$this->assertTrue( (bool) term_exists( 'bar', 'post_tag' ) );
		$this->assertFalse( (bool) term_exists( 'baz', 'post_tag' ) );

		$terms = [ 'foo', 'bar', 'baz' ];
		$created = Terms::translate_terms_to_ids( $terms, 'post_tag' );

		$this->assertCount( 3, $created );
		$this->assertContains( $foo, $created );
		$this->assertContains( $bar, $created );
		$this->assertTrue( (bool) term_exists( 'foo', 'post_tag' ) );
		$this->assertTrue( (bool) term_exists( 'bar', 'post_tag' ) );
		$this->assertTrue( (bool) term_exists( 'baz', 'post_tag' ) );
	}

	/**
	 * Test translate_terms_to_ids detects existing terms by id and slug
	 *
	 * @test
	 */
	public function test_translate_terms_to_ids_detects_existing_terms_by_id_and_slug() {
		$foo = $this->factory()->term->create( [ 'slug' => 'foo', 'taxonomy' => 'post_tag' ] );

		$this->assertTrue( (bool) term_exists( 'foo', 'post_tag' ) );

		$created = Terms::translate_terms_to_ids( $foo, 'post_tag' );

		$this->assertCount( 1, $created );
		$this->assertEquals( [ $foo ], $created );

		$created = Terms::translate_terms_to_ids( 'foo', 'post_tag' );
		$this->assertCount( 1, $created );
		$this->assertEquals( [ $foo ], $created );

		$created = Terms::translate_terms_to_ids( [ 'foo', $foo ], 'post_tag' );
		$this->assertCount( 1, $created );
		$this->assertEquals( [ $foo ], $created );
	}

	/**
	 * Test translate_term_to_ids does not create terms for non valid taxonomy
	 *
	 * @test
	 */
	public function test_translate_term_to_ids_does_not_create_terms_for_non_valid_taxonomy() {
		$created = Terms::translate_terms_to_ids( 'foo', 'bar' );
		$this->assertCount( 0, $created );
	}

	/**
	 * Test translate_terms_to_ids accepts comma separated strings of slugs ans IDs
	 *
	 * @test
	 */
	public function test_translate_terms_to_ids_accepts_comma_separated_strings_of_slugs_ans_i_ds() {
		$foo = $this->factory()->term->create( [ 'slug' => 'foo', 'taxonomy' => 'post_tag' ] );
		$bar = $this->factory()->term->create( [ 'slug' => 'bar', 'taxonomy' => 'post_tag' ] );

		$this->assertTrue( (bool) term_exists( 'foo', 'post_tag' ) );
		$this->assertTrue( (bool) term_exists( 'bar', 'post_tag' ) );
		$this->assertFalse( (bool) term_exists( 'baz', 'post_tag' ) );

		$created = Terms::translate_terms_to_ids( 'foo,bar,baz', 'post_tag' );

		$this->assertCount( 3, $created );
		$this->assertContains( $foo, $created );
		$this->assertContains( $bar, $created );
		$this->assertTrue( (bool) term_exists( 'foo', 'post_tag' ) );
		$this->assertTrue( (bool) term_exists( 'bar', 'post_tag' ) );
		$this->assertTrue( (bool) term_exists( 'baz', 'post_tag' ) );
		$baz = end( $created );

		$created = Terms::translate_terms_to_ids( implode( ',', [ $foo, 'bar', 'baz' ] ), 'post_tag' );

		$this->assertCount( 3, $created );
		$this->assertContains( $foo, $created );
		$this->assertContains( $bar, $created );
		$this->assertContains( $baz, $created );
	}

	/**
	 * Test translate_terms_to_ids does not create missing terms if told
	 *
	 * @test
	 */
	public function test_translate_terms_to_ids_does_not_create_missing_terms_if_told() {
		$foo = $this->factory()->term->create( [ 'slug' => 'foo', 'taxonomy' => 'post_tag' ] );
		$bar = $this->factory()->term->create( [ 'slug' => 'bar', 'taxonomy' => 'post_tag' ] );

		$this->assertTrue( (bool) term_exists( 'foo', 'post_tag' ) );
		$this->assertTrue( (bool) term_exists( 'bar', 'post_tag' ) );
		$this->assertFalse( (bool) term_exists( 'baz', 'post_tag' ) );

		$created = Terms::translate_terms_to_ids( 'foo,bar,baz', 'post_tag', false );

		$this->assertCount( 2, $created );
		$this->assertContains( $foo, $created );
		$this->assertContains( $bar, $created );
		$this->assertTrue( (bool) term_exists( 'foo', 'post_tag' ) );
		$this->assertTrue( (bool) term_exists( 'bar', 'post_tag' ) );
		$this->assertFalse( (bool) term_exists( 'baz', 'post_tag' ) );

		$created = Terms::translate_terms_to_ids( implode( ',', [ $foo, 'bar', 'baz' ] ), 'post_tag',false );

		$this->assertCount( 2, $created );
		$this->assertContains( $foo, $created );
		$this->assertContains( $bar, $created );
	}

	/**
	 * Test translate_terms_to_ids handles WP_Term objects
	 *
	 * @test
	 */
	public function test_translate_terms_to_ids_handles_wp_term_objects() {
		$foo = $this->factory()->term->create( [ 'slug' => 'foo', 'taxonomy' => 'post_tag' ] );
		$bar = $this->factory()->term->create( [ 'slug' => 'bar', 'taxonomy' => 'post_tag' ] );

		$term_ids = Terms::translate_terms_to_ids( [ get_term( $foo ), get_term( $bar ) ], 'post_tag', false );

		$this->assertCount( 2, $term_ids );
		$this->assertContains( $foo, $term_ids );
		$this->assertContains( $bar, $term_ids );
	}
}