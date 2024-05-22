<?php

namespace Tribe\Repository;

require_once __DIR__ . '/ReadTestBase.php';

use Tribe__Repository__Usage_Error as Usage_Error;

class ReadMultipleFieldsTest extends ReadTestBase {

	protected $genre = [];
	protected $user_genre = [];

	/**
	 * It should allow querying posts by two post fields
	 *
	 * @test
	 */
	public function should_allow_querying_posts_by_two_post_fields() {
		list( $one, $two, $three ) = $this->given_some_books();

		$repository = $this->repository();
		$matches = $repository->where_multi(
			[ 'post_title', 'post_excerpt' ],
			'LIKE',
			'%jump%'
		)->get_ids();
		codecept_debug( 'Query request SQL: ' . $repository->get_last_built_query()->request );

		$this->assertEquals( [ $one, $three ], $matches );

		$repository = $this->repository();
		$matches = $repository->where_multi(
			[ 'post_title', 'post_excerpt' ],
			'LIKE',
			'jump%'
		)->get_ids();
		codecept_debug( 'Query request SQL: ' . $repository->get_last_built_query()->request );

		$this->assertEquals( [ $one ], $matches );

		$repository = $this->repository();
		$matches = $repository->where_multi(
			[ 'post_title', 'post_excerpt' ],
			'LIKE',
			'%jump'
		)->get_ids();
		codecept_debug( 'Query request SQL: ' . $repository->get_last_built_query()->request );

		$this->assertEmpty( $matches );

		$repository = $this->repository();
		$matches = $repository->where_multi(
			[ 'post_title', 'post_excerpt' ],
			'NOT LIKE',
			'%jump%',
			'AND'
		)->get_ids();
		codecept_debug( 'Query request SQL: ' . $repository->get_last_built_query()->request );

		$this->assertEquals( [ $two ], $matches );
	}

	protected function given_some_books(): array {
		$this->given_some_genres();
		$admin = self::factory()->user->create( [ 'role' => 'administrator' ] );
		// To insert taxonomies along with the post.
		wp_set_current_user( $admin );
		$one = self::factory()->post->create( [
			'post_type'    => 'book',
			'post_title'   => 'The quick brown fox',
			'post_excerpt' => 'Jumps over the lazy dog',
			'tax_input'    => [
				'genre'      => [ $this->genre['fiction'] ],
				'user_genre' => [ $this->user_genre['trolling'] ],
			],
			'meta_input' => [
				'karma' => 'good',
				'emotion' => 'awesome',
			]
		] );
		$two = self::factory()->post->create( [
			'post_type'    => 'book',
			'post_title'   => 'Sphinx of black quartz',
			'post_excerpt' => 'Judge my vow',
			'tax_input'    => [
				'genre'      => [
					$this->genre['fiction'],
					$this->genre['history'],
				],
				'user_genre' => [
					$this->user_genre['bleh'],
					$this->user_genre['trolling'],
				],
			],
			'meta_input' => [
				'karma' => 'not so good',
				'emotion' => 'horrible, really bad',
			]
		] );
		$three = self::factory()->post->create( [
			'post_type'    => 'book',
			'post_title'   => 'The five boxing',
			'post_excerpt' => 'wizards jump quickly',
			'tax_input'    => [
				'genre' => [
					$this->genre['theatre'],
				],
			],
			'user_genre'   => [
				$this->user_genre['undefined'],
			],
			'meta_input'   => [
				'karma' => 'pretty good',
				'emotion' => 'not so bad',
			],
		] );

		wp_set_current_user( 0 );

		return [ $one, $two, $three ];
	}

	protected function given_some_genres(): array {
		$this->genre['fiction'] = self::factory()->term->create( [
			'taxonomy' => 'genre',
			'name'     => 'Fantasy and Fiction',
			'slug'     => 'genre--fiction',
		] );
		$this->genre['history'] = self::factory()->term->create( [
			'taxonomy' => 'genre',
			'name'     => 'History',
			'slug'     => 'genre--history',
		] );
		$this->genre['theatre'] = self::factory()->term->create( [
			'taxonomy' => 'genre',
			'name'     => 'Theatre',
			'slug'     => 'genre--theatre',
		] );
		$this->user_genre['bleh'] = self::factory()->term->create( [
			'taxonomy' => 'user_genre',
			'name'     => 'Bleh',
			'slug'     => 'ug--bleh',
		] );
		$this->user_genre['trolling'] = self::factory()->term->create( [
			'taxonomy' => 'user_genre',
			'name'     => 'Trolling',
			'slug'     => 'ug--trolling',
		] );
		$this->user_genre['undefined'] = self::factory()->term->create( [
			'taxonomy' => 'user_genre',
			'name'     => 'Undefined',
			'slug'     => 'ug--undefined',
		] );

		return [ $this->genre ];
	}

	/**
	 * It should prefix and postfix like strings if no placeholder provided
	 *
	 * @test
	 */
	public function should_prefix_and_postfix_like_strings_if_no_placeholder_provided() {
		list( $one, $two, $three ) = $this->given_some_books();

		$repository = $this->repository();
		$matches = $repository->where_multi(
			[ 'post_title', 'post_excerpt' ],
			'LIKE',
			'jump'
		)->get_ids();
		codecept_debug( 'Query request SQL: ' . $repository->get_last_built_query()->request );

		$this->assertEquals( [ $one, $three ], $matches );

		$repository = $this->repository();
		$matches = $repository->where_multi(
			[ 'post_title', 'post_excerpt' ],
			'NOT LIKE',
			'%jump%',
			'AND'
		)->get_ids();
		codecept_debug( 'Query request SQL: ' . $repository->get_last_built_query()->request );

		$this->assertEquals( [ $two ], $matches );
	}

	/**
	 * It should allow multiple values with logic OR and AND
	 *
	 * @test
	 */
	public function should_allow_multiple_values_with_logic_or_and_and() {
		list( $one, $two, $three ) = $this->given_some_books();

		$repository = $this->repository();
		$matches = $repository->where_multi(
			[ 'post_title', 'post_excerpt' ],
			'LIKE',
			[ 'jump', 'quartz' ],
			'OR',
			'OR'
		)->get_ids();
		codecept_debug( 'Query request SQL: ' . $repository->get_last_built_query()->request );
		$this->assertEquals( [ $one, $two, $three ], $matches );

		$repository = $this->repository();
		$matches = $repository->where_multi(
			[ 'post_title', 'post_excerpt' ],
			'LIKE',
			[ 'jump', 'quartz' ],
			'OR',
			'AND'
		)->get_ids();
		codecept_debug( 'Query request SQL: ' . $repository->get_last_built_query()->request );
		$this->assertEmpty( $matches );

		$repository = $this->repository();
		$matches = $repository->where_multi(
			[ 'post_title', 'post_excerpt' ],
			'LIKE',
			[ 'wizards', 'quickly' ]
		)->get_ids();
		codecept_debug( 'Query request SQL: ' . $repository->get_last_built_query()->request );
		$this->assertEquals( [ $three ], $matches );
	}

	/**
	 * It should throw if where relation is not supported
	 *
	 * @test
	 */
	public function should_throw_if_where_relation_is_not_supported() {
		$this->expectException( Usage_Error::class );

		$repository = $this->repository();
		$matches = $repository->where_multi(
			[ 'post_title', 'post_excerpt' ],
			'LIKE',
			[ 'wizards', 'quickly' ],
			'not a relation',
			'AND'
		)->get_ids();
	}

	/**
	 * It should throw if the value relation is not supported
	 *
	 * @test
	 */
	public function should_throw_if_the_value_relation_is_not_supported() {
		$this->expectException( Usage_Error::class );

		$repository = $this->repository();
		$matches = $repository->where_multi(
			[ 'post_title', 'post_excerpt' ],
			'LIKE',
			[ 'wizards', 'quickly' ],
			'AND',
			'not a relation'
		)->get_ids();
	}

	/**
	 * It should allow comparing on taxonomy term names
	 *
	 * @test
	 */
	public function should_allow_comparing_on_taxonomy_term_name() {
		list( $one, $two, $three ) = $this->given_some_books();

		$repository = $this->repository();
		$matches = $repository->where_multi(
			[ 'genre' ],
			'LIKE',
			'fiction'
		)->get_ids();
		codecept_debug( 'Query request SQL: ' . $repository->get_last_built_query()->request );
		$this->assertEquals( [ $one, $two ], $matches );

		$repository = $this->repository();
		$matches = $repository->where_multi(
			[ 'genre' ],
			'NOT LIKE',
			[ 'fiction', 'history' ],
			'AND',
			'AND'
		)->get_ids();
		codecept_debug( 'Query request SQL: ' . $repository->get_last_built_query()->request );
		$this->assertEquals( [ $three ], $matches );

		$repository = $this->repository();
		$matches = $repository->where_multi(
			[ 'genre', 'user_genre' ],
			'NOT LIKE',
			[ 'trolling' ],
			'AND'
		)->get_ids();
		codecept_debug( 'Query request SQL: ' . $repository->get_last_built_query()->request );
		$this->assertEquals( [ $one, $two, $three ], $matches );

		$repository = $this->repository();
		$matches = $repository->where_multi(
			[ 'genre', 'user_genre' ],
			'LIKE',
			[ 'trolling' ]
		)->get_ids();
		codecept_debug( 'Query request SQL: ' . $repository->get_last_built_query()->request );
		$this->assertEquals( [ $one, $two ], $matches );
	}

	/**
	 * It should allow searching by multiple custom fields
	 *
	 * @test
	 */
	public function should_allow_searching_by_multiple_custom_fields() {
		list( $one, $two, $three ) = $this->given_some_books();

		$repository = $this->repository();
		$matches = $repository->where_multi(
			[ 'karma' ],
			'LIKE',
			[ 'good' ]
		)->get_ids();
		codecept_debug( 'Query request SQL: ' . $repository->get_last_built_query()->request );
		$this->assertEquals( [ $one, $two, $three ], $matches );

		$repository = $this->repository();
		$matches = $repository->where_multi(
			[ 'karma' ],
			'LIKE',
			[ 'pretty good' ]
		)->get_ids();
		codecept_debug( 'Query request SQL: ' . $repository->get_last_built_query()->request );
		$this->assertEquals( [ $three ], $matches );

		$repository = $this->repository();
		$matches = $repository->where_multi(
			[ 'karma','emotion' ],
			'LIKE',
			[ 'bad', 'pretty bad' ]
		)->get_ids();
		codecept_debug( 'Query request SQL: ' . $repository->get_last_built_query()->request );
		$this->assertEquals( [ $two, $three ], $matches );
	}

	/**
	 * It should allow searching by post and custom fields and taxonomy fields
	 *
	 * @test
	 */
	public function should_allow_searching_by_post_and_custom_fields_and_taxonomy_fields() {
		list( $one, $two, $three ) = $this->given_some_books();

		$repository = $this->repository();
		$matches = $repository->where_multi(
			[ 'post_title', 'post_content', 'karma','emotion', 'genre', 'user_genre' ],
			'LIKE',
			[ 'fox', 'good' ]
		)->get_ids();
		codecept_debug( 'Query request SQL: ' . $repository->get_last_built_query()->request );
		$this->assertEquals( [ $one, $two, $three ], $matches );

		$repository = $this->repository();
		$matches = $repository->where_multi(
			[ 'post_title', 'post_content', 'karma','emotion', 'genre', 'user_genre' ],
			'LIKE',
			[ 'fox' ]
		)->get_ids();
		codecept_debug( 'Query request SQL: ' . $repository->get_last_built_query()->request );
		$this->assertEquals( [ $one ], $matches );
	}
}
